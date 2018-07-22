<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiTestCase extends WebTestCase
{
    /** @var Client */
    protected static $staticClient;

//    /** @var Client */
//    protected $client;

//    private static $staticToken;
//
//    private static $staticError;

    public static function setUpBeforeClass()
    {
        self::$staticClient = static::createClient();

//        $handler = HandlerStack::create();
//        $handler->push(Middleware::mapRequest(function (RequestInterface $request) {
//            $path = $request->getUri()->getPath();
//            if (strpos($path, '/test.php') !== 0) {
//                $path = '/test.php' . $path;
//            }
//            $uri = $request->getUri()->withPath($path);
//            return $request->withUri($uri);
//        }));

//        $apiUrl = getenv('TEST_API_URL');

//        self::$staticClient = new Client([
//            'base_uri' => $apiUrl,
//            'defaults' => [
//                'exceptions' => false,
//            ],
//            'http_errors' => false,
//            'handler' => $handler
//        ]);
//
//        // Trying to fetch the main user for the tests
//        $response = self::$staticClient->post('/login_check', [
//            'json' => UserProvider::mainUser()['main']['user'],
//        ]);
//
//        if ($response->getStatusCode() === 200) {
//
//            $body = json_decode((string) $response->getBody(), true);
//
//            if (is_array($body) && key_exists('token', $body)) {
//                self::$staticToken = $body['token'];
//            } else {
//                self::$staticError = 'Could not fetch token.';
//            }
//
//        } elseif ($response->getStatusCode() === 401) {
//            self::$staticError = 'Bad credentials.';
//        } else {
//            self::$staticError = 'Status code != 200 and 401 :'.$response->getStatusCode();
//        }
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

    protected function getBody(Response $response)
    {
        $jsonBody = $response->getContent();
        $this->assertJson($jsonBody);
        $body = json_decode($jsonBody, true);
        return $body;
    }

//
//    protected $token;
//
//    public function setUp()
//    {
//        if (is_null(self::$staticToken)) {
//            $this->fail('Could not set up client: ' . self::$staticError);
//        } else {
//            $this->client = self::$staticClient;
//            $this->token = self::$staticToken;
//        }
//    }

//    /**
//     * @param string $method
//     * @param string $uri
//     * @param string $token
//     * @param array $options
//     * @return ResponseInterface
//     */
//    protected function apiRequest(string $method, string $uri, string $token = null, array $options = [])
//    {
//        $allowedMethods = ['get', 'post', 'put', 'delete'];
//
//        if (!in_array($method, $allowedMethods)) {
//            throw new MethodNotAllowedException($allowedMethods);
//        }
//
//        if (!is_null($token)) {
//            $options['headers']['Authorization'] = 'Bearer ' . $token;
//        }
//
//        $response = $this->client->$method($uri, $options);
//
//        return $response;
//    }
}
