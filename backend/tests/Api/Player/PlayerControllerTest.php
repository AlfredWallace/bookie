<?php

namespace App\Tests\Api\Player;

use App\Tests\Api\ApiTestCase;
use App\Tests\DataProviders\PlayerProvider;
use Symfony\Component\HttpFoundation\Response;

class PlayerControllerTest extends ApiTestCase
{
    const PLAYER_ROUTE = '/players';
    
    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::testTokenPlayers()
     * @param $player
     */
    public function testToken($player): void
    {
        $response = $this->getTokenResponse($player);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $body = $this->getBody($response);
        $this->assertArrayHasKey('token', $body);

        $splitToken = explode('.', $body['token']);
        $this->assertCount(3, $splitToken);

        array_shift($splitToken);
        $payload = json_decode(base64_decode(array_shift($splitToken), true), true);
        $this->assertArrayHasKey('playerId', $payload);
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::badLoginData()
     * @param $player
     */
    public function testFetchTokenWithBadLoginData($player): void
    {
        $this->post('/login_check', null, $player);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, self::$staticClient->getResponse()->getStatusCode());
    }

    private function playerResponseAssertions($player, $responseBody, $id = false): void
    {
        $this->assertArrayHasKey('id', $responseBody);
        $this->assertArrayHasKey('username', $responseBody);
        $this->assertArrayNotHasKey('password', $responseBody);
        $this->assertEquals($player['username'], $responseBody['username']);
        if ($id !== false) {
            $this->assertEquals($id, $responseBody['id']);
        }
    }

    private function fetchPlayer($token, $player): void
    {
        $id = $player['id'];
        $this->get(self::PLAYER_ROUTE . '/' . $id, $token);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->playerResponseAssertions($player, $this->getBody($response), $id);
    }

    public function testGetPlayerByAdmin(): void
    {
        $this->fetchPlayer($this->getToken(PlayerProvider::mainPlayer()), PlayerProvider::otherPlayer());
    }

    public function testGetPlayerByNonAdmin(): void
    {
        $this->fetchPlayer($this->getToken(PlayerProvider::otherPlayer()), PlayerProvider::mainPlayer());
    }

    public function testGetUnexistentPlayer(): void
    {
        $this->get(self::PLAYER_ROUTE . '/' . self::UNEXISTENT_ID, $this->getToken(PlayerProvider::mainPlayer()));
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testCreatePlayer(): void
    {
        $player = [
            'username' => 'testCreatePlayer',
            'password' => 'R4nd0mP455w0rd',
        ];

        $this->post(self::PLAYER_ROUTE . '/new', null, $player);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->playerResponseAssertions($player, $this->getBody($response));
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::invalidPlayers()
     * @param $player
     */
    public function testCreateInvalidPlayer($player): void
    {
        $this->post(self::PLAYER_ROUTE . '/new', null, $player);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testDeletePlayerByAdmin(): void
    {
        $this->delete(self::PLAYER_ROUTE . '/' . PlayerProvider::playerToDelete()['id'], $this->getToken(PlayerProvider::mainPlayer()));
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeletePlayerByNonAdmin(): void
    {
        $this->delete(self::PLAYER_ROUTE . '/' . PlayerProvider::mainPlayer()['id'], $this->getToken(PlayerProvider::otherPlayer()));
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testDeleteUnexistentPlayer(): void
    {
        $this->delete(self::PLAYER_ROUTE . '/' . self::UNEXISTENT_ID, $this->getToken(PlayerProvider::mainPlayer()));
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

//    /**
//     * @dataProvider \App\Tests\DataProviders\PlayerProvider::playersToModify()
//     * @param $old
//     * @param $new
//     */
//    public function testUpdateSelf($old, $new): void
//    {
//        $this->put(self::PLAYER_ROUTE . '/' . $old['id'], $this->getToken($old), $new);
//        $response = self::$staticClient->getResponse();
//        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
//        $this->playerResponseAssertions($new, $this->getBody($response));
//    }
//
//    /**
//     * @dataProvider \App\Tests\DataProviders\PlayerProvider::PlayersToModify()
//     * @param $old
//     * @param $self
//     * @param $admin
//     */
//    public function testUpdatePlayerByAdmin($old, $self, $admin): void
//    {
//        $token = $this->getToken(PlayerProvider::mainPlayer());
//
//        $this->put(self::PLAYER_ROUTE . '/' . $old['id'], $token, $admin);
//        $response = self::$staticClient->getResponse();
//        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
//        $this->playerResponseAssertions($admin, $this->getBody($response));
//    }
//
//    public function testUpdateOtherPlayer(): void
//    {
//        $requester = PlayerProvider::otherPlayer();
//
//        $this->put(self::PLAYER_ROUTE . '/' . PlayerProvider::mainPlayer()['id'], $this->getToken($requester), $requester);
//        $response = self::$staticClient->getResponse();
//        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
//    }
//
//    public function testUpdateUnexistentPlayer(): void
//    {
//        $this->put(self::PLAYER_ROUTE . '/' . self::UNEXISTENT_ID, $this->getToken(PlayerProvider::mainPlayer()), []);
//        $response = self::$staticClient->getResponse();
//        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
//    }
//
//    /**
//     * @dataProvider \App\Tests\DataProviders\PlayerProvider::invalidPlayers()
//     * @param $invalidData
//     */
//    public function testUpdatePlayerWithInvalidData($invalidData): void
//    {
//        $player = PlayerProvider::mainPlayer();
//
//        $this->put(self::PLAYER_ROUTE . '/' . $player['id'], $this->getToken($player), $invalidData);
//        $response = self::$staticClient->getResponse();
//        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
//    }
}
