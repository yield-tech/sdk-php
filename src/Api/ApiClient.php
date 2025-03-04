<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Api;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use YieldTech\SdkPhp\Utils\Base64UrlUtils;
use YieldTech\SdkPhp\Version;

class ApiClient
{
    private readonly ClientInterface $httpClient;
    private readonly RequestFactoryInterface&StreamFactoryInterface $httpFactory;

    private readonly string $baseUrl;
    private readonly bool $useSandbox;

    private readonly string $apiKeyId;
    private readonly string $apiKeySecretToken;
    private readonly string $apiKeyHmacKey;

    /**
     * @param array{
     *   base_url?: string,
     *   force_sandbox?: bool,
     *   http_client?: ClientInterface,
     *   http_factory?: RequestFactoryInterface&StreamFactoryInterface,
     * } $options
     */
    public function __construct(string $apiKeyId, string $apiKeySecret, array $options = [])
    {
        $this->baseUrl = $options['base_url'] ?? 'https://integrate.withyield.com/api/v1';
        $this->useSandbox = ($options['force_sandbox'] ?? false) || str_starts_with($apiKeyId, 'sb_');

        $this->apiKeyId = $apiKeyId;
        $secret_parts = explode('$', $apiKeySecret);
        if (\count($secret_parts) !== 3 || $secret_parts[0] !== 'secret') {
            throw new \Exception('Invalid API key secret');
        }
        $this->apiKeySecretToken = $secret_parts[1];
        $this->apiKeyHmacKey = Base64UrlUtils::decode($secret_parts[2]);

        if (isset($options['http_client'])) {
            $this->httpClient = $options['http_client'];
        } elseif (class_exists('GuzzleHttp\Client')) {
            $this->httpClient = new \GuzzleHttp\Client();
        } elseif (class_exists('Symfony\Component\HttpClient\Psr18Client')) {
            $this->httpClient = new \Symfony\Component\HttpClient\Psr18Client();
        } else {
            throw new \Exception('Could not find any PSR-18 HTTP client');
        }

        if (isset($options['http_factory'])) {
            $this->httpFactory = $options['http_factory'];
        } elseif (class_exists('GuzzleHttp\Psr7\HttpFactory')) {
            $this->httpFactory = new \GuzzleHttp\Psr7\HttpFactory();
        } elseif (class_exists('Nyholm\Psr7\Factory\Psr17Factory')) {
            $this->httpFactory = new \Nyholm\Psr7\Factory\Psr17Factory();
        } else {
            throw new \Exception('Could not find any PSR-17 HTTP factory');
        }
    }

    /**
     * @template T
     *
     * @param callable(array): T     $fromPayload
     * @param ?array<string, string> $params
     *
     * @return ApiResult<T>
     */
    public function runQuery(callable $fromPayload, string $path, ?array $params = null): mixed
    {
        $fullPath = $path;
        if ($params !== null) {
            $fullPath .= '?'.http_build_query($params);
        }

        return $this->call($fromPayload, 'GET', $fullPath, null);
    }

    /**
     * @template T
     *
     * @param callable(array): T $fromPayload
     *
     * @return ApiResult<T>
     */
    public function runCommand(callable $fromPayload, string $path, mixed $reqPayload)
    {
        return $this->call($fromPayload, 'POST', $path, $reqPayload);
    }

    /**
     * @template T
     *
     * @param callable(array): T $fromPayload
     *
     * @return ApiResult<T>
     */
    private function call(callable $fromPayload, string $method, string $path, mixed $reqPayload): mixed
    {
        $req = $this->httpFactory->createRequest($method, $this->baseUrl.$path);

        $req = $req->withHeader('X-Yield-Client', self::getClientVersion());

        if ($this->useSandbox) {
            $req = $req->withHeader('X-Yield-Sandbox', '1');
        }

        $timestamp = gmdate('Y-m-d\TH:i:s\Z');
        $chunks = [$timestamp, $path];

        if ($reqPayload !== null) {
            $reqBody = json_encode($reqPayload, \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE | \JSON_THROW_ON_ERROR);
            $chunks[] = $reqBody;

            $req = $req
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->httpFactory->createStream($reqBody));
        }

        $messageString = implode("\n", $chunks);
        $signatureBytes = hash_hmac('sha512', $messageString, $this->apiKeyHmacKey, true);
        $signatureString = Base64UrlUtils::encode($signatureBytes);
        $authData = implode('$', [
            $this->apiKeyId,
            $this->apiKeySecretToken,
            $timestamp,
            $signatureString,
        ]);
        $req = $req->withHeader('Authorization', "Yield-Sig {$authData}");

        $response = $this->httpClient->sendRequest($req);
        $statusCode = $response->getStatusCode();

        $requestId = $response->getHeaderLine('X-Request-Id');

        if (200 <= $statusCode && $statusCode <= 299) {
            $body = $response->getBody()->getContents();
            $payload = json_decode($body, true);
            // @phpstan-ignore argument.type
            $data = $fromPayload($payload);

            return ApiResult::createSuccess($data, ['request_id' => $requestId]);
        } else {
            $error = new ApiErrorDetails($statusCode, $requestId);

            // @phpstan-ignore return.type
            return ApiResult::createFailure($error, ['request_id' => $requestId]);
        }
    }

    private static function getClientVersion(): string
    {
        $sdkVersion = Version::NUMBER;

        preg_match('/^\d+\.\d+?/', \PHP_VERSION, $m);
        $phpVersion = $m[0] ?? null;
        $platform = $phpVersion === null ? 'PHP' : "PHP {$phpVersion}";

        return "Yield-SDK-PHP/{$sdkVersion} ({$platform})";
    }
}
