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
}
