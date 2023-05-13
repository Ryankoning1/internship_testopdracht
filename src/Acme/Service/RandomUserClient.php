<?php declare(strict_types=1);

namespace App\Acme\Service;

use App\Acme\Exceptions\RandomUserClientException;
use GuzzleHttp\Client as GuzzleClient;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

class RandomUserClient
{

    private GuzzleClient|null $client;

    public function __construct
    (
        private readonly string $apiUrl

    )
    {
        $this->setClient();
    }

    public function setClient(?GuzzleClient $client = null): void
    {
        if($client !== null) {
            $this->client = $client;
            return;
        }

        $this->client = new GuzzleClient([
            'base_uri' => $this->apiUrl,
            'timeout' => 30.0
        ]);
    }

    public function getClient(): GuzzleClient {
        return $this->client;
    }

    public function getUsers(int $limit = 25): array {
        try {
            $result = $this->send('GET', '?results='.$limit);

            // @todo Validate the result and throw exception if needed.

            return $result['results'];
        } catch (Exception $e) {
            throw new RandomUserClientException(500, $e->getMessage());
        }
    }

    public function send(string $method = 'GET', string $urlPath = '', ?array $data = null, array $headers = []): array
    {
        try {
            $headers = [
                ...[
                    'Content-Type' => 'application/json'
                ],
                ... $headers
            ];

            $options = [
                'headers' => $headers,
                'http_errors' => false,
            ];

            if($data !== null) {
                $options['json'] = $data;
            }

            $requestResult = $this->getClient()->request($method, $urlPath, $options);

            $body = $requestResult->getBody();
            $content = $body->getContents();
            $result = json_decode($content, true);

            // Validate the apiResponse
            $statusCode = $requestResult->getStatusCode();

            // $statusCode >= 400 AND $statusCode < 500 | Issues with the data in the response.
            if ($statusCode >= 400 && $statusCode < 500) {
                throw new RandomUserClientException($statusCode, $content);
            }

            // $statusCode >= 500 | Error in backend.
            if ($statusCode >= 500 || $result === null) {
                throw new RandomUserClientException($statusCode, $content);
            }

            // $statusCode < 400 | Valid Response.
            if ($statusCode > 100 && $statusCode < 400) {
                return $result;
            }

            // Valid response code but there is no valid response: $statusCode < 400 and $result is invalid
            throw new RandomUserClientException($statusCode, $content);
        } catch (RandomUserClientException $e) {
            throw $e;
        } catch (GuzzleException $e) {
            throw new RandomUserClientException(($statusCode ?? 500), $e->getMessage(), $e);
        } catch (Exception $e) {
            throw new RandomUserClientException(($statusCode ?? 500), $e->getMessage(), $e);
        }
    }

}
