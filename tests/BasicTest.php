<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase
{
    private string $apiKey;
    private ?string $baseUrl;
    private YieldTech\SdkPhp\Client $client;

    protected function setUp(): void
    {
        $this->apiKey = getenv('YIELD_API_KEY') ?: '';
        $this->baseUrl = getenv('YIELD_API_BASE_URL') ?: null;

        $verifyTlsCertificates = $this->baseUrl === null || !str_contains($this->baseUrl, 'localhost');
        $httpClient = (new Symfony\Component\HttpClient\Psr18Client())
            ->withOptions(['verify_peer' => $verifyTlsCertificates]);
        // $httpClient = new GuzzleHttp\Client(['verify' => $verifyTlsCertificates]);

        $this->client = new YieldTech\SdkPhp\Client($this->apiKey, [
            'base_url' => $this->baseUrl,
            'http_client' => $httpClient,
        ]);
    }

    public function testConnection(): void
    {
        $info = $this->client->self->info();
        $this->assertSame(explode(':', $this->apiKey)[0], $info->id);
    }
}
