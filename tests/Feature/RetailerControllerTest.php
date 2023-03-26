<?php

namespace Tests\Feature;

use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Client;
use Tests\TestCase;

/**
 * Test Retailer Controller
 */
class RetailerControllerTest extends TestCase
{

    use RefreshDatabase;

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
     * Test Store Retailer API
     *
     * @return void
     */
    public function test_can_create_retailer()
    {
        $faker = Faker::create();
        $lastVerified = $faker->dateTime;
        $data = [
            'name' => $faker->sentence,
            'url' => $faker->url,
            'affiliate_network' => $faker->company,
            'short_text' => $faker->sentence,
            'long_text' => $faker->paragraph,
            'link_status' => $faker->boolean,
            'last_verified' => $lastVerified->format('Y-m-d H:i:s'),
            'featured' => $faker->boolean,
        ];

        $response = $this->post('/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret
        ]);

        $response->assertStatus(200);

        // get the access token from the response
        $accessToken = $response->json('access_token');

        $this->postJson('/api/retailer',
            $data,
            [
                'Authorization' => 'Bearer '.$accessToken,
            ]
        );

        $this->assertDatabaseHas('retailers', $data);
    }

    /**
     * @return void
     */
    public function test_can_show_retailer()
    {
        $faker = Faker::create();
        $lastVerified = $faker->dateTime;
        $data = [
            'name' => $faker->sentence,
            'url' => $faker->url,
            'affiliate_network' => $faker->company,
            'short_text' => $faker->sentence,
            'long_text' => $faker->paragraph,
            'link_status' => $faker->boolean,
            'last_verified' => $lastVerified->format('Y-m-d H:i:s'),
            'featured' => $faker->boolean,
        ];

        $response = $this->post('/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret
        ]);

        $response->assertStatus(200);

        // get the access token from the response
        $accessToken = $response->json('access_token');

        $result = $this->postJson(
            '/api/retailer',
            $data,
            [
                'Authorization' => 'Bearer '.$accessToken,
            ]
        );
        $responseArray = $result->json();

        $id =  $responseArray['id'];

        // make a GET request to the show endpoint for the retailer
        $response = $this->get('/api/retailer/' . $id,
            [
                'Authorization' => 'Bearer '.$accessToken,
            ]
        );

        // check that the response has a 200 status code
        $response->assertStatus(200);

        // check that the response has the correct JSON structure and contains the retailer data
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'url',
                'affiliate_network',
                'short_text',
                'long_text',
                'link_status',
                'last_verified',
                'featured',
                'created_at',
                'updated_at',
            ]
        ])->assertJson([
            'data' => [
                'id' => $id,
                'name' => $data['name'],
                'url' => $data['url'],
                'affiliate_network' => $data['affiliate_network'],
                'short_text' => $data['short_text'],
                'long_text' => $data['long_text'],
                'link_status' => $data['link_status'],
                'last_verified' => $data['last_verified'],
                'featured' => $data['featured'],
            ]
        ]);
    }
}
