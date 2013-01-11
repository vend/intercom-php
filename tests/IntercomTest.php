<?php
require_once 'Intercom.php';

class IntercomTest extends PHPUnit_Framework_TestCase
{
    public function testGetAllUsers()
    {
        $intercom = new Intercom('dummy-app-id', 'dummy-api-key');

        $users = $intercom->getAllUsers();

        $this->assertTrue(is_object($users));
        $this->assertObjectHasAttribute('users', $users);
    }

    public function testCreateUser()
    {
        $intercom = new Intercom('dummy-app-id', 'dummy-api-key');

        $userId = 'userId001';
        $email = 'email@example.com';
        $res = $intercom->createUser('userId001', $email);

        $this->assertTrue(is_object($res));
        $this->assertObjectHasAttribute('email', $res);
        $this->assertEquals($email, $res->email);
        $this->assertObjectHasAttribute('user_id', $res);
        $this->assertEquals($userId, $res->user_id);
    }

    public function testCreateImpression()
    {
        $intercom = new Intercom('dummy-app-id', 'dummy-api-key');

        $res = $intercom->createImpression('userId001');

        $this->assertTrue(is_object($res));
        $this->assertObjectHasAttribute('unread_messages', $res);
    }
}
?>