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
        $response = $this->call('PUT','api/v1/users',[
            'id' => '4',
            'full_name' => 'Amir-updated',
            'email' => 'Amirrrupdate@gmail.com',
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
}