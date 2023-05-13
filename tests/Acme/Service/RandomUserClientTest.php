<?php declare(strict_types=1);

namespace App\Tests\Acme\Service;

use App\Acme\Exceptions\RandomUserClientException;
use App\Acme\Service\RandomUserClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RandomUserClientTest extends TestCase
{
    private const API_URL = 'https://randomuser.me/api/';

    /** @var RandomUserClient */
    private RandomUserClient $client;

    /** @var GuzzleClient|MockObject */
    private $guzzleClient;

    public function setUp(): void
    {
        $this->guzzleClient = $this->createMock(GuzzleClient::class);
        $this->client = new RandomUserClient(self::API_URL);

        $this->client->setClient($this->guzzleClient);
    }

    public function testGetClientReturnsGuzzleClient(): void
    {
        $this->assertInstanceOf(GuzzleClient::class, $this->client->getClient());
    }

    public function testSendHandlesValidResponse(): void
    {
        $this->guzzleClient->expects($this->once())
            ->method('request')
            ->with('GET', 'users')
            ->willReturn(new Response(200, [], '{"results": []}'));

        $response = $this->client->send('GET', 'users');

        $this->assertIsArray($response);
        $this->assertArrayHasKey('results', $response);
    }

    public function testSendHandlesInvalidJsonResponse(): void
    {
        $this->guzzleClient->expects($this->once())
            ->method('request')
            ->with('GET', 'users')
            ->willReturn(new Response(200, [], 'invalid-json'));

        $this->expectException(RandomUserClientException::class);
        $this->expectExceptionCode(200);

        $this->client->send('GET', 'users');
    }

    public function testSendHandlesInvalidDataResponse(): void
    {
        $this->guzzleClient->expects($this->once())
            ->method('request')
            ->with('GET', 'users')
            ->willReturn(new Response(400, [], '{"error": "invalid data"}'));

        $this->expectException(RandomUserClientException::class);
        $this->expectExceptionCode(400);

        $this->client->send('GET', 'users');
    }

    public function testSendHandlesBackendErrorResponse(): void
    {
        $this->guzzleClient->expects($this->once())
            ->method('request')
            ->with('GET', 'users')
            ->willReturn(new Response(500, [], '{"error": "backend error"}'));

        $this->expectException(RandomUserClientException::class);
        $this->expectExceptionCode(500);

        $this->client->send('GET', 'users');
    }

    public function testSendHandlesGuzzleException(): void
    {
        $this->guzzleClient->expects($this->once())
            ->method('request')
            ->with('GET', 'users')
            ->willThrowException(
                new RequestException(
                    'request failed',
                    new Request('GET', "users"),
                    new Response(500, [], '{"error": "backend error"}')
                )
            );

        $this->expectException(RandomUserClientException::class);
        $this->expectExceptionCode(500);

        $this->client->send('GET', 'users');
    }
}
