<?php

namespace VirtualQueue\TokenVerifier;

use VirtualQueue\TokenVerifier\Http\HttpClient;

/**
 * Client to verify virtual queue tokens.
 */
class TokenVerifier
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * Constructor.
     *
     * @param string|null $baseUrl API base URL (optional)
     * @param array $options Additional options
     */
    public function __construct(?string $baseUrl = null, array $options = [])
    {
        $this->httpClient = new HttpClient($baseUrl ?? 'https://app.virtual-queue.com', $options);
    }

    /**
     * Verifies a virtual queue token and return success + details.
     *
     * @param string $token Token to verify
     * @return array Verified token data with details
     */
    public function verifyToken(string $token): array
    {
        if (empty($token)) {
            return [
                'success' => false,
                'message' => "Token cannot be empty"
            ];
        }

        try {
            $response = $this->httpClient->get('/api/v1/queue/verify', ['token' => $token]);

            return [
                'success' => true,
                'data' => $response['data']
            ];
            // API returns 404 if token is invalid, and this is taken as an exception
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return [
                'success' => false,
                'message' => $e->getResponse()->getBody()->getContents()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Error contacting the API. Check URL in config/token-verifier.php"
            ];
        }
    }

    /**
     * Checks if a token is valid only true or false.
     *
     * @param string $token Token to verify
     * @return bool True if the token is valid, false otherwise
     */
    public function isTokenValid(string $token): bool
    {
        $response = $this->verifyToken($token);
        return $response['success'];
    }
}
