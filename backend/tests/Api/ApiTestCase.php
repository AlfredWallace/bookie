<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiTestCase extends WebTestCase
{
    const UNEXISTENT_ID = 0;

    /** @var Client */
    protected static $staticClient;

    public static function setUpBeforeClass()
    {
        self::$staticClient = static::createClient();
    }

    protected function get(string $uri, ?string $token = null, array $parameters = []): Crawler
    {
        return $this->apiRequest('GET', $uri, $token, $parameters, null);
    }

    protected function post(string $uri, ?string $token = null, $content = null, array $parameters = []): Crawler
    {
        return $this->apiRequest('POST', $uri, $token, $parameters, $content);
    }

    protected function put(string $uri, ?string $token = null, $content = null, array $parameters = []): Crawler
    {
        return $this->apiRequest('PUT', $uri, $token, $parameters, $content);
    }

    protected function delete(string $uri, ?string $token = null, array $parameters = []): Crawler
    {
        return $this->apiRequest('DELETE', $uri, $token, $parameters, null);
    }

    private function apiRequest(string $method, string $uri, ?string $token, array $parameters, $content): Crawler
    {
        $server = [
            'CONTENT_TYPE' => 'application/json'
        ];

        if ($token !== null) {
            $server['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;
        }

        $encodedContent = $content;
        if ($encodedContent !== null) {
            $encodedContent = json_encode($encodedContent);
        }

        return self::$staticClient->request($method, $uri, $parameters, [], $server, $encodedContent);
    }

    protected function getTokenResponse($player): Response
    {
        $this->post('/login_check', null, [
            'username' => $player['username'] ?? null,
            'password' => $player['password'] ?? null,
        ]);
        return self::$staticClient->getResponse();
    }

    protected function getToken($player)
    {
        $response = $this->getTokenResponse($player);
        $body = $this->getBody($response);
        return $body['token'];
    }

    protected function getBody(Response $response)
    {
        $jsonBody = $response->getContent();
        $this->assertJson($jsonBody);
        $body = json_decode($jsonBody, true);
        return $body;
    }
}
