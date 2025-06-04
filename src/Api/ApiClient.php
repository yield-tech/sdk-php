<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Api;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use YieldTech\SdkPhp\Utils\Base64UrlUtils;
use YieldTech\SdkPhp\Utils\TypeUtils;
use YieldTech\SdkPhp\Version;

/**
 * @phpstan-type ClientOptions array{
 *   base_url?: string,
 *   http_client?: ClientInterface,
 *   http_factory?: RequestFactoryInterface&StreamFactoryInterface,
 * }
 */
class ApiClient
{
    private readonly string $baseUrl;

    private readonly string $apiKeyToken;
    private readonly string $apiKeyHmacKey;

    private readonly ClientInterface $httpClient;
    private readonly RequestFactoryInterface&StreamFactoryInterface $httpFactory;

    private readonly string $clientVersion;

    /**
     * @param ClientOptions $options
     */
    public function __construct(string $apiKey, array $options = [])
    {
        $this->baseUrl = $options['base_url'] ?? 'https://integrate.withyield.com/api/v1';
        [$this->apiKeyToken, $this->apiKeyHmacKey] = self::extractApiKey($apiKey);
        $this->httpClient = $options['http_client'] ?? self::findHttpClient();
        $this->httpFactory = $options['http_factory'] ?? self::findHttpFactory();
        $this->clientVersion = Version::getClientVersion();
    }

    /** @return array{string, string} */
    public static function extractApiKey(string $key): array
    {
        // "$" is the old separator, supported for backwards compatibility
        $keyParts = explode(':', str_replace('$', ':', $key));
        if (\count($keyParts) !== 3) {
            throw new \InvalidArgumentException('Invalid Yield API key');
        }

        $token = "{$keyParts[0]}\${$keyParts[1]}";
        $hmacKey = Base64UrlUtils::decode($keyParts[2]);

        return [$token, $hmacKey];
    }

    public static function findHttpClient(): ClientInterface
    {
        if (class_exists('Symfony\Component\HttpClient\Psr18Client')) {
            return new \Symfony\Component\HttpClient\Psr18Client();
        } elseif (class_exists('GuzzleHttp\Client')) {
            return new \GuzzleHttp\Client();
        } else {
            throw new \Exception('Could not find any PSR-18 HTTP client');
        }
    }

    public static function findHttpFactory(): RequestFactoryInterface&StreamFactoryInterface
    {
        if (class_exists('Nyholm\Psr7\Factory\Psr17Factory')) {
            return new \Nyholm\Psr7\Factory\Psr17Factory();
        } elseif (class_exists('GuzzleHttp\Psr7\HttpFactory')) {
            return new \GuzzleHttp\Psr7\HttpFactory();
        } else {
            throw new \Exception('Could not find any PSR-17 HTTP factory');
        }
    }

    public function buildSignature(string $hmacKey, string $timestamp, string $path, ?string $body = null): string
    {
        $parts = $body === null ? [$timestamp, $path] : [$timestamp, $path, $body];
        $message = implode("\n", $parts);
        $sigBytes = hash_hmac('sha512', $message, $hmacKey, true);

        return Base64UrlUtils::encode($sigBytes);
    }

    /**
     * @template T
     *
     * @param callable(array<string, mixed>): T $fromPayload
     *
     * @return ApiResult<T>
     */
    public static function processResponse(ResponseInterface $response, callable $fromPayload): mixed
    {
        $statusCode = $response->getStatusCode();
        $statusOk = 200 <= $statusCode && $statusCode <= 299;

        $requestId = $response->getHeaderLine('X-Request-Id');

        if (!$statusOk) {
            $errorType = 'unexpected_error';
            $payload = null;
            try {
                $body = $response->getBody()->getContents();
                $payload = TypeUtils::expectRecord(json_decode($body, true));
                if (isset($payload['error']) && \is_string($payload['error'])) {
                    $errorType = $payload['error'];
                }
            } catch (\Exception $e) {
                // ignore
            }

            // @phpstan-ignore return.type
            return ApiResult::createFailure($statusCode, $requestId, $errorType, $payload, null);
        }

        try {
            $body = $response->getBody()->getContents();
            $payload = TypeUtils::expectRecord(json_decode($body, true));
            $data = $fromPayload($payload);
        } catch (\Exception $e) {
            // @phpstan-ignore return.type
            return ApiResult::createFailure($statusCode, $requestId, 'invalid_response', null, $e);
        }

        return ApiResult::createSuccess($statusCode, $requestId, $data);
    }

    /**
     * @param ?array<string, string|int|null> $params
     */
    public function runQuery(string $path, ?array $params = null): ResponseInterface
    {
        $fullPath = $path;
        if ($params !== null) {
            $queryString = http_build_query($params);
            if ($queryString !== '') {
                $fullPath .= '?'.$queryString;
            }
        }

        return $this->callEndpoint('GET', $fullPath, null);
    }

    public function runCommand(string $path, mixed $payload): ResponseInterface
    {
        return $this->callEndpoint('POST', $path, $payload);
    }

    private function callEndpoint(string $method, string $path, mixed $payload): ResponseInterface
    {
        $req = $this->httpFactory->createRequest($method, $this->baseUrl.$path);

        $req = $req->withHeader('X-Yield-Client', $this->clientVersion);

        $body = $payload === null ? null : json_encode($payload, \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE | \JSON_THROW_ON_ERROR);
        if ($body !== null) {
            $req = $req
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->httpFactory->createStream($body));
        }

        $timestamp = gmdate('Y-m-d\TH:i:s\Z', time());
        $signature = self::buildSignature($this->apiKeyHmacKey, $timestamp, $path, $body);
        $req = $req->withHeader('Authorization', "Yield-Sig {$this->apiKeyToken}\${$timestamp}\${$signature}");

        return $this->httpClient->sendRequest($req);
    }
}
