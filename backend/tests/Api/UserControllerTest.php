<?php

namespace App\Tests\Api;

use App\Providers\Tests\UserProvider;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends ApiTestCase
{
    const UNEXISTENT_USERNAME = 'dhgafsviah.rjsfh@jrdkshn.com';
    const UNEXISTENT_ID = 0;

    private function getToken($user)
    {
        $this->post('/login_check', null, [
            'username' => $user['username'] ?? null,
            'password' => $user['password'] ?? null,
        ]);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $body = $this->getBody($response);
        $this->assertArrayHasKey('token', $body);
        return $body['token'];
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::additionalUsers()
     * @param $user
     */
    public function testFetchTokenOnly($user)
    {
        $this->getToken($user);
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::additionalUsers()
     * @param $user
     */
    public function testFetchTokenWithBadUsername($user)
    {
        $this->post('/login_check', null, [
            'username' => 'dummy' . $user['username'],
            'password' => $user['password'],
        ]);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, self::$staticClient->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::additionalUsers()
     * @param $user
     */
    public function testFetchTokenWithBadPassword($user)
    {
        $this->post('/login_check', null, [
            'username' => $user['username'],
            'password' => 'dummy' . $user['password'],
        ]);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, self::$staticClient->getResponse()->getStatusCode());
    }

    private function userResponseAssertions($user, $responseBody, $id = false)
    {
        $this->assertArrayHasKey('id', $responseBody);
        $this->assertArrayHasKey('username', $responseBody);
        $this->assertArrayNotHasKey('password', $responseBody);
        $this->assertArrayNotHasKey('roles', $responseBody);
        $this->assertEquals($user['username'], $responseBody['username']);
        if ($id !== false) {
            $this->assertEquals($id, $responseBody['id']);
        }
    }

    private function fetchUser($token, $user)
    {
        // Getting the user by username
        $this->get('/users/username/' . $user['username'], $token);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $body = $this->getBody($response);
        $this->userResponseAssertions($user, $body);

        // Getting the user by ID
        $id = $body['id'];
        $this->get('/users/' . $id, $token);
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->userResponseAssertions($user, $this->getBody($response), $id);
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::mainUser()
     * @dataProvider \App\Providers\Tests\UserProvider::additionalUsers()
     * @param $user
     */
    public function testGetSelfUser($user)
    {
        $this->fetchUser($this->getToken($user), $user);
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::mainUser()
     * @dataProvider \App\Providers\Tests\UserProvider::additionalUsers()
     * @param $user
     */
    public function testGetOtherUser($user)
    {
        $this->fetchUser($this->getToken(UserProvider::otherUser()['other']['user']), $user);
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::otherUser()
     * @param $user
     */
    public function testGetUnexistentUserById($user)
    {
        $this->get('/users/' . self::UNEXISTENT_ID, $this->getToken($user));
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::otherUser()
     * @param $user
     */
    public function testGetUnexistentUserByUsername($user)
    {
        $this->get('/users/username/' . self::UNEXISTENT_USERNAME, $this->getToken($user));
        $response = self::$staticClient->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::usersToCreate()
     * @param $user
     */
    public function testCreateUser($user)
    {
        $response = $this->apiRequest('post', '/users/new', null, [
            'json' => $user,
        ]);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->userResponseAssertions($user, $this->getBody($response));
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::invalidUsers()
     * @param $user
     */
    public function testCreateInvalidUser($user)
    {
        $response = $this->apiRequest('post', '/users/new', null, [
            'json' => $user,
        ]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::usersToSelfDelete()
     * @param $user
     */
    public function testDeleteSelf($user)
    {
        $token = $this->getToken($user);

        $response = $this->apiRequest('get', '/users/username/' . $user['username'], $token);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $body = $this->getBody($response);
        $this->assertArrayHasKey('id', $body);

        $response = $this->apiRequest('delete', '/users/' . $body['id'], $token);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::usersToSelfDeleteByUsername()
     * @param $user
     */
    public function testDeleteSelfByUsername($user)
    {
        $response = $this->apiRequest('delete', '/users/username/' . $user['username'], $this->getToken($user));
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::usersToDelete()
     * @param $user
     */
    public function testDeleteUser($user)
    {
        $token = $this->getToken(UserProvider::mainUser()['main']['user']);

        $response = $this->apiRequest('get', '/users/username/' . $user['username'], $token);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $body = $this->getBody($response);
        $this->assertArrayHasKey('id', $body);

        $response = $this->apiRequest('delete', '/users/' . $body['id'], $token);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::usersToDeleteByUsername()
     * @param $user
     */
    public function testDeleteUserByUsername($user)
    {
        $response = $this->apiRequest(
            'delete',
            '/users/username/' . $user['username'],
            $this->getToken(UserProvider::mainUser()['main']['user'])
        );
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::mainUser()
     * @param $user
     */
    public function testDeleteUnexistentUser($user)
    {
        $response = $this->apiRequest('delete', '/users/' . self::UNEXISTENT_ID, $this->getToken($user));
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::mainUser()
     * @param $user
     */
    public function testDeleteUnexistentUserByUsername($user)
    {
        $response = $this->apiRequest('delete', '/users/username/' . self::UNEXISTENT_USERNAME, $this->getToken($user));
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::mainUser()
     * @dataProvider \App\Providers\Tests\UserProvider::additionalUsers()
     * @param $user
     */
    public function testDeleteOtherUser($user)
    {
        $token = $this->getToken(UserProvider::otherUser()['other']['user']);

        $response = $this->apiRequest('get', '/users/username/' . $user['username'], $token);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $body = $this->getBody($response);
        $this->assertArrayHasKey('id', $body);

        $response = $this->apiRequest('delete', '/users/' . $body['id'], $token);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::mainUser()
     * @dataProvider \App\Providers\Tests\UserProvider::additionalUsers()
     * @param $user
     */
    public function testDeleteOtherUserByUsername($user)
    {
        $response = $this->apiRequest(
            'delete',
            '/users/username/' . $user['username'],
            $this->getToken(UserProvider::otherUser()['other']['user'])
        );
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::usersToSelfModify()
     * @param $oldUser
     * @param $newUser
     */
    public function testUpdateSelf($oldUser, $newUser)
    {
        $token = $this->getToken($oldUser);
        $response = $this->apiRequest('get', '/users/username/' . $oldUser['username'], $token);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $body = $this->getBody($response);
        $this->assertArrayHasKey('id', $body);

        $response = $this->apiRequest(
            'put',
            '/users/' . $body['id'],
            $token,
            ['json' => $newUser,]
        );
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->userResponseAssertions($newUser, $this->getBody($response));
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::usersToSelfModifyByUsername()
     * @param $oldUser
     * @param $newUser
     */
    public function testUpdateSelfByUsername($oldUser, $newUser)
    {
        $response = $this->apiRequest(
            'put',
            '/users/username/' . $oldUser['username'],
            $this->getToken($oldUser),
            ['json' => $newUser,]
        );
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->userResponseAssertions($newUser, $this->getBody($response));
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::usersToModify()
     * @param $oldUser
     * @param $newUser
     */
    public function testUpdateUser($oldUser, $newUser)
    {
        $requester = UserProvider::mainUser()['main']['user'];
        $token = $this->getToken($requester);
        $response = $this->apiRequest('get', '/users/username/' . $oldUser['username'], $token);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $body = $this->getBody($response);
        $this->assertArrayHasKey('id', $body);

        $response = $this->apiRequest('put', '/users/' . $body['id'], $token, ['json' => $newUser,]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->userResponseAssertions($newUser, $this->getBody($response));
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::usersToModifyByUsername()
     * @param $oldUser
     * @param $newUser
     */
    public function testUpdateUserByUsername($oldUser, $newUser)
    {
        $requester = UserProvider::mainUser()['main']['user'];
        $response = $this->apiRequest(
            'put',
            '/users/username/' . $oldUser['username'],
            $this->getToken($requester),
            ['json' => $newUser,]
        );
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->userResponseAssertions($newUser, $this->getBody($response));
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::mainUser()
     * @dataProvider \App\Providers\Tests\UserProvider::additionalUsers()
     * @param $user
     */
    public function testUpdateOtherUser($user)
    {
        $requester = UserProvider::otherUser()['other']['user'];
        $token = $this->getToken($requester);
        $response = $this->apiRequest('get', '/users/username/' . $user['username'], $token);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $body = $this->getBody($response);
        $this->assertArrayHasKey('id', $body);

        $response = $this->apiRequest(
            'put',
            '/users/' . $body['id'],
            $token,
            ['json' => $requester,]
        );
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::mainUser()
     * @dataProvider \App\Providers\Tests\UserProvider::additionalUsers()
     * @param $user
     */
    public function testUpdateOtherUserByUsername($user)
    {
        $requester = UserProvider::otherUser()['other']['user'];
        $response = $this->apiRequest(
            'put',
            '/users/username/' . $user['username'],
            $this->getToken($requester),
            ['json' => $requester,]
        );
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::mainUser()
     * @param $user
     */
    public function testUpdateUnexistentUser($user)
    {
        $response = $this->apiRequest(
            'put',
            '/users/' . self::UNEXISTENT_ID,
            $this->getToken($user),
            ['json' => [],]
        );
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::mainUser()
     * @param $user
     */
    public function testUpdateUnexistentUserByUsername($user)
    {
        $response = $this->apiRequest(
            'put',
            '/users/username/' . self::UNEXISTENT_USERNAME,
            $this->getToken($user),
            ['json' => [],]
        );
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::invalidUsers()
     * @param $invalidData
     */
    public function testUpdateUserWithInvalidData($invalidData)
    {
        $user = UserProvider::otherUser()['other']['user'];
        $token = $this->getToken($user);

        $response = $this->apiRequest('get', '/users/username/' . $user['username'], $token);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $body = $this->getBody($response);
        $this->assertArrayHasKey('id', $body);

        $response = $this->apiRequest(
            'put',
            '/users/' . $body['id'],
            $token,
            ['json' => $invalidData,]
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * @dataProvider \App\Providers\Tests\UserProvider::invalidUsers()
     * @param $invalidData
     */
    public function testUpdateUserWithInvalidDataByUsername($invalidData)
    {
        $user = UserProvider::otherUser()['other']['user'];
        $response = $this->apiRequest(
            'put',
            '/users/username/' . $user['username'],
            $this->getToken($user),
            ['json' => $invalidData,]
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
