<?php

namespace App\Tests\Api\Player;

use App\Tests\Api\ApiTestCase;
use App\Tests\DataProviders\PlayerProvider;
use Symfony\Component\HttpFoundation\Response;

class PlayerControllerTest extends ApiTestCase
{
    const PLAYER_ROUTE = '/players';
    
    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::mainPlayerDataProvider()
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::additionalPlayers()
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
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::additionalPlayers()
     * @param $player
     */
    public function testFetchTokenWithBadPlayername($player): void
    {
        $this->post('/login_check', null, [
            'username' => 'dummy' . $player['username'],
            'password' => $player['password'],
        ]);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, self::$staticClient->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::additionalPlayers()
     * @param $player
     */
    public function testFetchTokenWithBadPassword($player): void
    {
        $this->post('/login_check', null, [
            'username' => $player['username'],
            'password' => 'dummy' . $player['password'],
        ]);
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

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::mainPlayerDataProvider()
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::additionalPlayers()
     * @param $player
     */
    public function testGetSelfPlayer($player): void
    {
        $this->fetchPlayer($this->getToken($player), $player);
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::mainPlayerDataProvider()
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::additionalPlayers()
     * @param $player
     */
    public function testGetOtherPlayer($player): void
    {
        $this->fetchPlayer($this->getToken(PlayerProvider::otherPlayer()), $player);
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::otherPlayerDataProvider()
     * @param $player
     */
    public function testGetUnexistentPlayer($player): void
    {
        $this->get(self::PLAYER_ROUTE . '/' . self::UNEXISTENT_ID, $this->getToken($player));
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::PlayersToCreate()
     * @param $player
     */
    public function testCreatePlayer($player): void
    {
        $this->post(self::PLAYER_ROUTE . '/new', null, [
            'username' => $player['username'] ?? null,
            'password' => $player['password'] ?? null,
        ]);
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
        $this->post(self::PLAYER_ROUTE . '/new', null, [
            'username' => $player['username'] ?? null,
            'password' => $player['password'] ?? null,
        ]);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::PlayersToSelfDelete()
     * @param $player
     */
    public function testDeleteSelf($player): void
    {
        $token = $this->getToken($player);

        $this->delete(self::PLAYER_ROUTE . '/' . $player['id'], $token);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::PlayersToDelete()
     * @param $player
     */
    public function testDeletePlayerByAdmin($player): void
    {
        $token = $this->getToken(PlayerProvider::mainPlayer());

        $this->delete(self::PLAYER_ROUTE . '/' . $player['id'], $token);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::mainPlayerDataProvider()
     * @param $player
     */
    public function testDeleteUnexistentPlayer($player): void
    {
        $this->delete(self::PLAYER_ROUTE . '/' . self::UNEXISTENT_ID, $this->getToken($player));
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::mainPlayerDataProvider()
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::additionalPlayers()
     * @param $player
     */
    public function testDeleteOtherPlayer($player): void
    {
        $token = $this->getToken(PlayerProvider::otherPlayer());

        $this->delete(self::PLAYER_ROUTE . '/' . $player['id'], $token);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::PlayersToSelfModify()
     * @param $oldPlayer
     * @param $newPlayer
     */
    public function testUpdateSelf($oldPlayer, $newPlayer): void
    {
        $token = $this->getToken($oldPlayer);

        $this->put(self::PLAYER_ROUTE . '/' . $oldPlayer['id'], $token, [
            'username' => $newPlayer['username'] ?? null,
            'password' => $newPlayer['password'] ?? null,
        ]);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->playerResponseAssertions($newPlayer, $this->getBody($response));
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::PlayersToModify()
     * @param $oldPlayer
     * @param $newPlayer
     */
    public function testUpdatePlayerByAdmin($oldPlayer, $newPlayer): void
    {
        $token = $this->getToken(PlayerProvider::mainPlayer());

        $this->put(self::PLAYER_ROUTE . '/' . $oldPlayer['id'], $token, [
            'username' => $newPlayer['username'] ?? null,
            'password' => $newPlayer['password'] ?? null,
        ]);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->playerResponseAssertions($newPlayer, $this->getBody($response));
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::mainPlayerDataProvider()
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::additionalPlayers()
     * @param $player
     */
    public function testUpdateOtherPlayer($player): void
    {
        $requester = PlayerProvider::otherPlayer();
        $token = $this->getToken($requester);

        $this->put(self::PLAYER_ROUTE . '/' . $player['id'], $token, [
            'username' => $requester['username'] ?? null,
            'password' => $requester['password'] ?? null,
        ]);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::mainPlayerDataProvider()
     * @param $player
     */
    public function testUpdateUnexistentPlayer($player): void
    {
        $this->put(self::PLAYER_ROUTE . '/' . self::UNEXISTENT_ID, $this->getToken($player), []);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::invalidPlayers()
     * @param $invalidData
     */
    public function testUpdatePlayerWithInvalidData($invalidData): void
    {
        $player = PlayerProvider::otherPlayer();
        $token = $this->getToken($player);

        $this->put(self::PLAYER_ROUTE . '/' . $player['id'], $token, $invalidData);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
