<?php

namespace Tests\API\V1\Users;

use Tests\TestCase;

class UsersTest extends TestCase 
{
    public function test_should_it_can_create_a_new_user()
    {
        $response = $this->call('POST','api/v1/users',[
            'full_name' => 'Amir',
            'email' => 'Amir@gmail.com',
            'mobile' => '09121112222',
            'password' => '123456'
        ]);

        $this->assertEquals(201,$response->status());
        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'full_name',
                'email',
                'mobile',
                'password'
            ]
        ]);
    }

    public function test_it_must_throw_a_exception_if_we_dont_sent_parameters()
    {
        $response = $this->call('POST','api/v1/users',[]);

        $this->assertEquals(422,$response->status());
    }

    public function test_should_update_the_inforamtion_of_user()
    {
        $number = rand(1,100);
        $response = $this->call('PUT','api/v1/users',[
            'id' => '4',
            'full_name' => 'Amir-updated-'.$number,
            'email' => 'Amirrrupdate'.$number.'@gmail.com',
            'mobile' => '02121112222',
        ]);

        $this->assertEquals(200,$response->status());
        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'full_name',
                'email',
                'mobile'
            ]
        ]);
    }

    public function test_it_must_throw_a_exception_if_we_dont_sent_parameters_to_update_info()
    {
        $response = $this->call('PUT','api/v1/users',[]);

        $this->assertEquals(422,$response->status());
    }

    public function test_should_update_user_password()
    {
        $response = $this->call('PUT','api/v1/users/change-password',[
            'id' => '2',
            'password' => '@$$LLssoi983',
            'password_reapet' => '@$$LLssoi983'
        ]);

        $this->assertEquals(200,$response->status());
        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'full_name',
                'email',
                'mobile'
            ]
        ]);
    }

    public function test_it_must_throw_a_exception_if_we_dont_sent_parameters_to_update_password()
    {
        $response = $this->call('PUT','api/v1/users/change-password',[]);

        $this->assertEquals(422,$response->status());
    }

    public function test_should_delete_a_user()
    {
        $response = $this->call('DELETE','api/v1/users',[
            'id' => '3'
        ]);

        $this->assertEquals(200,$response->status());
        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    public function test_it_must_throw_a_exception_if_we_dont_sent_parameters_to_delete_user()
    {
        $response = $this->call('DELETE','api/v1/users',[]);

        $this->assertEquals(422,$response->status());
    }

    public function test_should_get_users()
    {
        $pagesize = 3;
        $response = $this->call('GET','api/v1/users',[
            'page' => 1,
            'pagesize' => $pagesize
        ]);

        $data = json_decode($response->getContent(),true);

        $this->assertCount($pagesize,$data['data']);
        $this->assertEquals(200,$response->status());
        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    public function test_should_get_filterd_users()
    {
        $pagesize = 3;
        $userEmail = 'Amir@gmail.com';
        $response = $this->call('GET','api/v1/users',[
            'search' => $userEmail, 
            'page' => 1,
            'pagesize' => $pagesize
        ]);

        $data = json_decode($response->getContent(),true);

        $this->assertEquals($data['data']['email'],$userEmail);
        $this->assertEquals(200,$response->status());
        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }
}