<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Client;
use Tests\TestCase;

/**
 *
 */
class UserTest extends TestCase
{

    use DatabaseMigrations;


    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // create a passport client
        $this->client = Client::factory()->create([
            'password_client' => true,
            'revoked' => false,
        ]);
    }


    /**
     * User tests
     *
     * @return void
     */
    public function test_user_login()
    {
        $user = User::factory()->create();

        $response = $this->post('/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret
        ]);

        $response->assertStatus(200);

        // get the access token from the response
        $accessToken = $response->json('access_token');

        // make the request to the user login endpoint with the Authorization header set
        $response = $this->post('/api/user/login', [
            'email' => $user->email,
            'password' => 'password'
        ], [
            'Authorization' => 'Bearer '.$accessToken,
        ]);

        $response->assertStatus(200);
        $this->assertAuthenticated();
        $this->assertAuthenticatedAs($user);
    }

    /**
     * @return void
     */
    public function test_user_register()
    {
        $userData = [
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post('/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret
        ]);

        $response->assertStatus(200);

        // get the access token from the response
        $accessToken = $response->json('access_token');

        $response = $this->post('api/user/register',
            $userData,
            [
                'Authorization' => 'Bearer '.$accessToken,
            ]
        );

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => $userData['email']]);
    }

}
