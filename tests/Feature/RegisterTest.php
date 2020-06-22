<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private static $USER_DATA = [
        'name'  =>  'andriy',
        'email' =>  'andriy@email.com',
        'password'              =>  'password',
        'password_confirmation' =>  'password'
    ];
    public static $ROUTE = '/register';

    /** @test */
    public function a_user_can_be_created()
    {
        $this->post(self::$ROUTE, self::$USER_DATA);
        $this->assertCount(1, User::all());
    }

    /** @test */
    public function a_user_name_is_required()
    {
        unset(self::$USER_DATA['name']);
        $response = $this->post(self::$ROUTE, self::$USER_DATA);
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_user_name_length()
    {
        self::$USER_DATA['name'] = Str::random(256);
        $response = $this->post(self::$ROUTE, self::$USER_DATA);
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_user_email_is_required()
    {
        unset(self::$USER_DATA['email']);
        $response = $this->post(self::$ROUTE, self::$USER_DATA);
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function a_user_email_length()
    {
        self::$USER_DATA['email'] = Str::random(255) . '@email.com';
        $response = $this->post(self::$ROUTE, self::$USER_DATA);
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function a_user_email_is_valid()
    {
        self::$USER_DATA['email'] = Str::random(25);
        $response = $this->post(self::$ROUTE, self::$USER_DATA);
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function a_user_password_is_required()
    {
        unset(self::$USER_DATA['password']);
        $response = $this->post(self::$ROUTE, self::$USER_DATA);
        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function a_user_password_min_length()
    {
        $invalidPassword = Str::random(5);
        self::$USER_DATA['password'] = $invalidPassword;
        self::$USER_DATA['password_confirmation'] = $invalidPassword;
        $response = $this->post(self::$ROUTE, self::$USER_DATA);
        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function a_user_password_is_not_confirmed()
    {
        unset(self::$USER_DATA['password_confirmation']);
        $response = $this->post(self::$ROUTE, self::$USER_DATA);
        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function a_user_password_have_invalid_confirmation()
    {
        self::$USER_DATA['password_confirmation'] = Str::random(10);
        $response = $this->post(self::$ROUTE, self::$USER_DATA);
        $response->assertSessionHasErrors('password');
    }
}
