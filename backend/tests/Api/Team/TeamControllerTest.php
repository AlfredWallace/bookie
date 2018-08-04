<?php

namespace App\Tests\Api\Team;

use App\Tests\Api\ApiTestCase;
use App\Tests\DataProviders\PlayerProvider;
use Symfony\Component\HttpFoundation\Response;

class TeamControllerTest extends ApiTestCase
{
    private function teamResponseAssertions($team, $responseBody, $id = false)
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
    public function testGetTeamByAdmin($team)
    {
        $token = $this->getToken(PlayerProvider::mainPlayer());

        $id = $team['id'];
        $this->get('/teams/' . $id, $token);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->teamResponseAssertions($team, $this->getBody($response), $id);
    }

    /**
     * @dataProvider \App\Tests\DataProviders\TeamProvider::basicTeams()
     * @param $team
     */
    public function testGetTeamByNonAdmin($team)
    {
        $token = $this->getToken(PlayerProvider::otherPlayer());

        $id = $team['id'];
        $this->get('/teams/' . $id, $token);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->teamResponseAssertions($team, $this->getBody($response), $id);
    }

    /**
     * @dataProvider \App\Tests\DataProviders\TeamProvider::teamsToCreate()
     * @param $team
     */
    public function testCreateTeamsByNonAdmin($team)
    {
        $token = $this->getToken(PlayerProvider::otherPlayer());

        $this->post('/teams', $token, $team);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\TeamProvider::teamsToCreate()
     * @param $team
     */
    public function testCreateTeamsByAdmin($team)
    {
        $token = $this->getToken(PlayerProvider::mainPlayer());

        $this->post('/teams', $token, $team);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->teamResponseAssertions($team, $this->getBody($response));
    }

    /**
     * @dataProvider \App\Tests\DataProviders\TeamProvider::teamsToModify()
     * @param $old
     * @param $new
     */
    public function testUpdateTeamsByNonAdmin($old, $new)
    {
        $token = $this->getToken(PlayerProvider::otherPlayer());

        $this->put('/teams/' . $old['id'], $token, $new);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Tests\DataProviders\TeamProvider::teamsToModify()
     * @param $old
     * @param $new
     */
    public function testUpdateTeamsByAdmin($old, $new)
    {
        $token = $this->getToken(PlayerProvider::mainPlayer());

        $this->put('/teams/' . $old['id'], $token, $new);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->teamResponseAssertions($new, $this->getBody($response));
    }
}
