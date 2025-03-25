<?php

namespace VirtualQueue\TokenVerifier\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

/**
 * HTTP Client for making API requests.
 */
class HttpClient
{
    /**
     * @var GuzzleClient
     */
    private $httpClient;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * Constructor.
     *
     * @param string $baseUrl
     * @param array $options
     */
    public function __construct(string $baseUrl = 'https://app.virtual-queue.com', array $options = [])
    {
        $this->baseUrl = $baseUrl;

        $clientOptions = [
            'base_uri' => $this->baseUrl,
            'timeout' => $options['timeout'] ?? 30,
            'headers' => [
                'User-Agent' => 'VirtualQueue-TokenVerifier/1.0',
                'Content-Type' => 'application/json'
            ],
        ];

        if (isset($options['headers'])) {
            $clientOptions['headers'] = array_merge($clientOptions['headers'], $options['headers']);
        }

        $this->httpClient = new GuzzleClient($clientOptions);
    }

    /**
     * Performs an HTTP GET request.
     *
     * @param string $endpoint
     * @param array $queryParams
     * @return array
     * @throws \Exception
     */
    public function get(string $endpoint, array $queryParams = []): array
    {
            $response = $this->httpClient->request('GET', $endpoint, [
                'query' => $queryParams,
            ]);

            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Error decoding JSON response: ' . json_last_error_msg());
            }

            return $data;
    }

    /**
     * Performs an HTTP POST request.
     *
     * @param string $endpoint API endpoint
     * @param array $data Data to send
     * @param array $headers Additional HTTP headers
     * @return array Decoded response
     * @throws \Exception If there is an API or network error
     */
    public function post(string $endpoint, array $data = [], array $headers = []): array
    {
        return $this->request('POST', $endpoint, [
            'json' => $data,
            'headers' => $headers,
        ]);
    }

    /**
     * Performs an HTTP PUT request.
     *
     * @param string $endpoint API endpoint
     * @param array $data Data to send
     * @param array $headers Additional HTTP headers
     * @return array Decoded response
     * @throws \Exception If there is an API or network error
     */
    public function put(string $endpoint, array $data = [], array $headers = []): array
    {
        return $this->request('PUT', $endpoint, [
            'json' => $data,
            'headers' => $headers,
        ]);
    }

    /**
     * Performs an HTTP DELETE request.
     *
     * @param string $endpoint API endpoint
     * @param array $headers Additional HTTP headers
     * @return array Decoded response
     * @throws \Exception If there is an API or network error
     */
    public function delete(string $endpoint, array $headers = []): array
    {
        return $this->request('DELETE', $endpoint, [
            'headers' => $headers,
        ]);
    }

    /**
     * Performs a generic HTTP request.
     *
     * @param string $method HTTP method
     * @param string $endpoint API endpoint
     * @param array $options Request options
     * @return array Decoded response
     * @throws \Exception If there is an API or network error
     */
    private function request(string $method, string $endpoint, array $options = []): array
    {
        try {
            $response = $this->httpClient->request($method, $endpoint, $options);

            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Error decoding JSON response: ' . json_last_error_msg());
            }

            return $data;
        } catch (GuzzleException $e) {
            // Handle network and API errors
            if ($e->getCode() >= 400 && $e->getCode() < 500) {
                throw new \Exception('API error: ' . $e->getMessage(), $e->getCode(), $e);
            } else {
                throw new \Exception('Network error: ' . $e->getMessage(), $e->getCode(), $e);
            }
        }
    }
}
