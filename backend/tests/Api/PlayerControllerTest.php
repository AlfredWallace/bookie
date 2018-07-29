<?php

namespace App\Tests\Api;

use App\Tests\DataProviders\PlayerProvider;
use Symfony\Component\HttpFoundation\Response;

class PlayerControllerTest extends ApiTestCase
{
    const UNEXISTENT_ID = 0;

    private function getToken($player)
    {
        $this->post('/login_check', null, [
            'username' => $player['username'] ?? null,
            'password' => $player['password'] ?? null,
        ]);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $body = $this->getBody($response);
        $this->assertArrayHasKey('token', $body);
        return $body['token'];
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::additionalPlayers()
     * @param $player
     */
    public function testFetchTokenOnly($player)
    {
        $this->getToken($player);
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::additionalPlayers()
     * @param $player
     */
    public function testFetchTokenWithBadPlayername($player)
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
    public function testFetchTokenWithBadPassword($player)
    {
        $this->post('/login_check', null, [
            'username' => $player['username'],
            'password' => 'dummy' . $player['password'],
        ]);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, self::$staticClient->getResponse()->getStatusCode());
    }

    private function playerResponseAssertions($player, $responseBody, $id = false)
    {
        $this->assertArrayHasKey('id', $responseBody);
        $this->assertArrayHasKey('username', $responseBody);
        $this->assertArrayNotHasKey('password', $responseBody);
        $this->assertEquals($player['username'], $responseBody['username']);
        if ($id !== false) {
            $this->assertEquals($id, $responseBody['id']);
        }
    }

    private function fetchPlayer($token, $player)
    {
        $id = $player['id'];
        $this->get('/players/' . $id, $token);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->playerResponseAssertions($player, $this->getBody($response), $id);
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::mainPlayer()
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::additionalPlayers()
     * @param $player
     */
    public function testGetSelfPlayer($player)
    {
        $this->fetchPlayer($this->getToken($player), $player);
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::mainPlayer()
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::additionalPlayers()
     * @param $player
     */
    public function testGetOtherPlayer($player)
    {
        $this->fetchPlayer($this->getToken(PlayerProvider::otherPlayer()['other']['player']), $player);
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::otherPlayer()
     * @param $player
     */
    public function testGetUnexistentPlayer($player)
    {
        $this->get('/players/' . self::UNEXISTENT_ID, $this->getToken($player));
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::PlayersToCreate()
     * @param $player
     */
    public function testCreatePlayer($player)
    {
        $this->post('/players/new', null, [
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
    public function testCreateInvalidPlayer($player)
    {
        $this->post('/players/new', null, [
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
    public function testDeleteSelf($player)
    {
        $token = $this->getToken($player);

        $this->delete('/players/' . $player['id'], $token);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::PlayersToDelete()
     * @param $player
     */
    public function testDeletePlayerByAdmin($player)
    {
        $token = $this->getToken(PlayerProvider::mainPlayer()['main']['player']);

        $this->delete('/players/' . $player['id'], $token);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::mainPlayer()
     * @param $player
     */
    public function testDeleteUnexistentPlayer($player)
    {
        $this->delete('/players/' . self::UNEXISTENT_ID, $this->getToken($player));
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::mainPlayer()
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::additionalPlayers()
     * @param $player
     */
    public function testDeleteOtherPlayer($player)
    {
        $token = $this->getToken(PlayerProvider::otherPlayer()['other']['player']);

        $this->delete('/players/' . $player['id'], $token);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::PlayersToSelfModify()
     * @param $oldPlayer
     * @param $newPlayer
     */
    public function testUpdateSelf($oldPlayer, $newPlayer)
    {
        $token = $this->getToken($oldPlayer);

        $this->put('/players/' . $oldPlayer['id'], $token, [
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
    public function testUpdatePlayerByAdmin($oldPlayer, $newPlayer)
    {
        $token = $this->getToken(PlayerProvider::mainPlayer()['main']['player']);

        $this->put('/players/' . $oldPlayer['id'], $token, [
            'username' => $newPlayer['username'] ?? null,
            'password' => $newPlayer['password'] ?? null,
        ]);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->playerResponseAssertions($newPlayer, $this->getBody($response));
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::mainPlayer()
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::additionalPlayers()
     * @param $player
     */
    public function testUpdateOtherPlayer($player)
    {
        $requester = PlayerProvider::otherPlayer()['other']['player'];
        $token = $this->getToken($requester);

        $this->put('/players/' . $player['id'], $token, [
            'username' => $requester['username'] ?? null,
            'password' => $requester['password'] ?? null,
        ]);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::mainPlayer()
     * @param $player
     */
    public function testUpdateUnexistentPlayer($player)
    {
        $this->put('/players/' . self::UNEXISTENT_ID, $this->getToken($player), []);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\PlayerProvider::invalidPlayers()
     * @param $invalidData
     */
    public function testUpdatePlayerWithInvalidData($invalidData)
    {
        $player = PlayerProvider::otherPlayer()['other']['player'];
        $token = $this->getToken($player);

        $this->put('/players/' . $player['id'], $token, $invalidData);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
