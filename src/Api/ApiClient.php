<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Api;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use YieldTech\SdkPhp\Utils\AssertUtils;
use YieldTech\SdkPhp\Utils\Base64UrlUtils;
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

        $keyParts = explode('$', $apiKey);
        if (\count($keyParts) !== 3) {
            throw new \InvalidArgumentException('Invalid Yield API key');
        }
        $this->apiKeyToken = "{$keyParts[0]}\${$keyParts[1]}";
        $this->apiKeyHmacKey = Base64UrlUtils::decode($keyParts[2]);

        if (isset($options['http_client'])) {
            $this->httpClient = $options['http_client'];
        } elseif (class_exists('Symfony\Component\HttpClient\Psr18Client')) {
            $this->httpClient = new \Symfony\Component\HttpClient\Psr18Client();
        } elseif (class_exists('GuzzleHttp\Client')) {
            $this->httpClient = new \GuzzleHttp\Client();
        } else {
            throw new \Exception('Could not find any PSR-18 HTTP client');
        }

        if (isset($options['http_factory'])) {
            $this->httpFactory = $options['http_factory'];
        } elseif (class_exists('Nyholm\Psr7\Factory\Psr17Factory')) {
            $this->httpFactory = new \Nyholm\Psr7\Factory\Psr17Factory();
        } elseif (class_exists('GuzzleHttp\Psr7\HttpFactory')) {
            $this->httpFactory = new \GuzzleHttp\Psr7\HttpFactory();
        } else {
            throw new \Exception('Could not find any PSR-17 HTTP factory');
        }

        $this->clientVersion = Version::getClientVersion();
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
                $payload = AssertUtils::assertAssociativeArray(json_decode($body, true));
                if (isset($payload['error']) && \is_string($payload['error'])) {
                    $errorType = $payload['error'];
                }
            } catch (\Exception $e) {
                // ignore
            }

            // @phpstan-ignore return.type
            return ApiResult::createFailure($statusCode, $requestId, $errorType, $payload);
        }

        try {
            $body = $response->getBody()->getContents();
            $payload = AssertUtils::assertAssociativeArray(json_decode($body, true));
            $data = $fromPayload($payload);
        } catch (\Exception $e) {
            // @phpstan-ignore return.type
            return ApiResult::createFailure($statusCode, $requestId, 'unexpected_payload', null);
        }

        return ApiResult::createSuccess($statusCode, $requestId, $data);
    }

    /**
     * @param ?array<string, string> $params
     */
    public function runQuery(string $path, ?array $params = null): ResponseInterface
    {
        $fullPath = $path;
        if ($params !== null) {
            $fullPath .= '?'.http_build_query($params);
        }

        return $this->callEndpoint('GET', $fullPath, null);
    }

    public function runCommand(string $path, mixed $payload): ResponseInterface
    {
        return $this->callEndpoint('POST', $path, $payload);
    }

    public function buildSignature(string $path, ?string $body = null, ?int $now = null): string
    {
        $timestamp = gmdate('Y-m-d\TH:i:s\Z', $now ?? time());
        $parts = $body === null ? [$timestamp, $path] : [$timestamp, $path, $body];
        $message = implode("\n", $parts);
        $signatureBytes = hash_hmac('sha512', $message, $this->apiKeyHmacKey, true);
        $signatureB64 = Base64UrlUtils::encode($signatureBytes);

        return implode('$', [$this->apiKeyToken, $timestamp, $signatureB64]);
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

        $signature = $this->buildSignature($path, $body);
        $req = $req->withHeader('Authorization', "Yield-Sig {$signature}");

        return $this->httpClient->sendRequest($req);
    }
}
