<?php

namespace App\Tests\Api\Team;

use App\Tests\Api\ApiTestCase;
use App\Tests\DataProviders\PlayerProvider;
use App\Tests\DataProviders\TeamProvider;
use Symfony\Component\HttpFoundation\Response;

class TeamControllerTest extends ApiTestCase
{
    const TEAM_ROUTE = '/teams';

    private function teamResponseAssertions($team, $responseBody, $id = false): void
    {
        $this->assertArrayHasKey('id', $responseBody);
        $this->assertArrayHasKey('name', $responseBody);
        $this->assertArrayHasKey('abbreviation', $responseBody);
        $this->assertEquals($team['name'], $responseBody['name']);
        $this->assertEquals($team['abbreviation'], $responseBody['abbreviation']);
        if ($id !== false) {
            $this->assertEquals($id, $responseBody['id']);
        }
    }

    /**
     * @dataProvider \App\Tests\DataProviders\TeamProvider::basicTeams()
     * @param $team
     */
    public function testGetTeamByAdmin($team): void
    {
        $token = $this->getToken(PlayerProvider::mainPlayer());

        $id = $team['id'];
        $this->get(self::TEAM_ROUTE . '/' . $id, $token);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->teamResponseAssertions($team, $this->getBody($response), $id);
    }

    /**
     * @dataProvider \App\Tests\DataProviders\TeamProvider::basicTeams()
     * @param $team
     */
    public function testGetTeamByNonAdmin($team): void
    {
        $token = $this->getToken(PlayerProvider::otherPlayer());

        $id = $team['id'];
        $this->get(self::TEAM_ROUTE . '/' . $id, $token);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->teamResponseAssertions($team, $this->getBody($response), $id);
    }

    /**
     * @dataProvider \App\Tests\DataProviders\TeamProvider::teamsToCreate()
     * @param $team
     */
    public function testCreateTeamsByNonAdmin($team): void
    {
        $token = $this->getToken(PlayerProvider::otherPlayer());

        $this->post(self::TEAM_ROUTE, $token, $team);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\TeamProvider::teamsToCreate()
     * @param $team
     */
    public function testCreateTeamsByAdmin($team): void
    {
        $token = $this->getToken(PlayerProvider::mainPlayer());

        $this->post(self::TEAM_ROUTE, $token, $team);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->teamResponseAssertions($team, $this->getBody($response));
    }

    /**
     * @dataProvider \App\Tests\DataProviders\TeamProvider::teamsToModify()
     * @param $old
     * @param $new
     */
    public function testUpdateTeamsByNonAdmin($old, $new): void
    {
        $token = $this->getToken(PlayerProvider::otherPlayer());

        $this->put(self::TEAM_ROUTE . '/' . $old['id'], $token, $new);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\TeamProvider::teamsToModify()
     * @param $old
     * @param $new
     */
    public function testUpdateTeamsByAdmin($old, $new): void
    {
        $token = $this->getToken(PlayerProvider::mainPlayer());

        $this->put(self::TEAM_ROUTE . '/' . $old['id'], $token, $new);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->teamResponseAssertions($new, $this->getBody($response));
    }

    /**
     * @dataProvider \App\Tests\DataProviders\TeamProvider::teamsToDelete()
     * @param $team
     */
    public function testDeleteByNonAdmin($team): void
    {
        $token = $this->getToken(PlayerProvider::otherPlayer());

        $this->delete(self::TEAM_ROUTE . '/' . $team['id'], $token);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\TeamProvider::teamsToDelete()
     * @param $team
     */
    public function testDeleteTeamsByAdmin($team): void
    {
        $token = $this->getToken(PlayerProvider::mainPlayer());

        $this->delete(self::TEAM_ROUTE . '/' . $team['id'], $token);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\TeamProvider::invalidTeams()
     * @param $invalidData
     */
    public function testCreateInvalidTeams($invalidData): void
    {
        $token = $this->getToken(PlayerProvider::mainPlayer());

        $this->post(self::TEAM_ROUTE, $token, $invalidData);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\TeamProvider::invalidTeams()
     * @param $invalidData
     */
    public function testUpdateTeamWithInvalidData($invalidData): void
    {
        $token = $this->getToken(PlayerProvider::mainPlayer());

        $this->put(self::TEAM_ROUTE . '/' . TeamProvider::mainTeam()['id'], $token, $invalidData);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
