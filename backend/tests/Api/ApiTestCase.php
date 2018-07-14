<?php

namespace App\Tests\Api;


use App\Providers\Tests\UserProvider;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class ApiTestCase extends TestCase
{

    /**
     * @var Client
     */
    private static $staticClient;

    private static $staticToken;

    private static $staticError;

    /**
     * @var Client
     */
    protected $client;

    protected $token;

    public function setUp()
    {
        if (is_null(self::$staticToken)) {
            $this->fail('Could not set up the JWT properly : '.self::$staticError);
        } else {
            $this->client = self::$staticClient;
            $this->token = self::$staticToken;
        }
    }

    /**
     * @param ResponseInterface $response
     * @return mixed
     */
    protected function getBody(ResponseInterface $response)
    {
        $jsonBody = (string) $response->getBody();
        $this->assertJson($jsonBody);
        $body = json_decode($jsonBody, true);
        return $body;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param string $token
     * @param array $options
     * @return ResponseInterface
     */
    protected function apiRequest(string $method, string $uri, string $token = null, array $options = [])
    {
        $allowedMethods = ['get', 'post', 'put', 'delete'];

        if (!in_array($method, $allowedMethods)) {
            throw new MethodNotAllowedException($allowedMethods);
        }

        if (!is_null($token)) {
            $options['headers']['Authorization'] = 'Bearer ' . $token;
        }

        $response = $this->client->$method($uri, $options);

        return $response;
    }

    public static function setUpBeforeClass()
    {
        $handler = HandlerStack::create();
        $handler->push(Middleware::mapRequest(function (RequestInterface $request) {
            $path = $request->getUri()->getPath();
            if (strpos($path, '/test.php') !== 0) {
                $path = '/test.php' . $path;
            }
            $uri = $request->getUri()->withPath($path);
            return $request->withUri($uri);
        }));

        $apiUrl = getenv('TEST_API_URL');

        self::$staticClient = new Client([
            'base_uri' => $apiUrl,
            'defaults' => [
                'exceptions' => false,
            ],
            'http_errors' => false,
            'handler' => $handler
        ]);

        // Trying to fetch the main user for the tests
        $response = self::$staticClient->post('/login_check', [
            'json' => UserProvider::mainUser()['main']['user'],
        ]);

        if ($response->getStatusCode() === 200) {

            $body = json_decode((string) $response->getBody(), true);

            if (is_array($body) && key_exists('token', $body)) {
                self::$staticToken = $body['token'];
            } else {
                self::$staticError = 'Could not fetch token.';
            }

        } elseif ($response->getStatusCode() === 401) {
            self::$staticError = 'Bad credentials.';
        } else {
            self::$staticError = 'Status code != 200 and 401 :'.$response->getStatusCode();
        }
    }
}