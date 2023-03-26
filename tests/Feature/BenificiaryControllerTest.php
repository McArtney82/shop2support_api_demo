<?php

namespace Tests\Feature;

use App\Models\Benificiary;
use App\Models\Retailer;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Client;
use Tests\TestCase;

class BenificiaryControllerTest extends TestCase
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
     * Test for creating a new benificiary.
     *
     * @return void
     */
    public function test_can_create_benificiary()
    {
        $faker = Faker::create();

        $data = [
            'name' => $faker->company(),
            'about' => $faker->text(),
            'color' => $faker->hexcolor(),
            'logo' => $faker->imageUrl
        ];

        $response = $this->post('/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret
        ]);

        $response->assertStatus(200);

        // get the access token from the response
        $accessToken = $response->json('access_token');

        $response = $this->postJson('/api/benificiary',
            $data,
            [
                'Authorization' => 'Bearer '.$accessToken,
            ]
        );

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'id',
            ]);
    }

    /**
     * Test for retrieving a benificiary.
     *
     * @return void
     */
    public function test_can_retrieve_benificiary()
    {
        $faker = Faker::create();

        $benificiary = Benificiary::factory()->create([
            'name' => $faker->company(),
            'about' => $faker->text(),
            'color' => $faker->hexcolor(),
            'logo' => $faker->imageUrl
        ]);

        $response = $this->post('/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret
        ]);

        $response->assertStatus(200);

        // get the access token from the response
        $accessToken = $response->json('access_token');

        $response = $this->get(
            '/api/benificiary/' . $benificiary->id,
            [
                'Authorization' => 'Bearer '.$accessToken,
            ]
        );

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $benificiary->id,
                    'name' => $benificiary->name,
                    'about' => $benificiary->about,
                    'color' => $benificiary->color,
                    'logo' => $benificiary->logo,
                ]
            ]);
    }

    /**
     * Test for updating a benificiary.
     *
     * @return void
     */
    public function test_can_update_benificiary()
    {
        $faker = Faker::create();

        $benificiary = Benificiary::factory()->create(
            [
            'name' => $faker->company(),
            'about' => $faker->text(),
            'color' => $faker->hexcolor(),
            'logo' => $faker->imageUrl
        ],

        );

        $data = [
            'name' => $faker->name(),
            'about' => $faker->text(),
            'color' => $faker->hexcolor(),
        ];

        $response = $this->post('/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret
        ]);

        $response->assertStatus(200);

        // get the access token from the response
        $accessToken = $response->json('access_token');

        $response = $this->put('/api/benificiary/' . $benificiary->id,
            $data,
            [
                'Authorization' => 'Bearer '.$accessToken,
            ]
        );

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Benificiary updated successfully',
                'id' => $benificiary->id
            ]);
    }


    /**
     * Test addRetailers endpoint.
     *
     * @return void
     */
    public function test_can_add_retailers()
    {
        $faker = Faker::create();
        $benificiary = Benificiary::factory()->create([
            'name' => $faker->name,
            'about'=> $faker->paragraph,
            'color' => $faker->hexColor,
            'logo' => $faker->imageUrl
        ]);
        $lastVerified = $faker->dateTime;
        $retailers = Retailer::factory()->count(2)->create([
            'name' => $faker->sentence,
            'url' => $faker->url,
            'affiliate_network' => $faker->company,
            'short_text' => $faker->sentence,
            'long_text' => $faker->paragraph,
            'link_status' => $faker->boolean,
            'last_verified' => $lastVerified->format('Y-m-d H:i:s'),
            'featured' => $faker->boolean,
        ]);

        $data = [
            'retailer_ids' => $retailers->pluck('id')->toArray(),
        ];

        $url = route('benificiary.addRetailers', ['benificiary' => $benificiary->id]);

        $response = $this->post('/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret
        ]);

        $response->assertStatus(200);

        // get the access token from the response
        $accessToken = $response->json('access_token');

        $response = $this->postJson(
            $url,
            $data,
            [
                'Authorization' => 'Bearer '.$accessToken,
            ]
        );
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['message' => 'Retailers added to benificiary successfully']);

        $this->assertDatabaseHas('benificiary_retailer', [
            'benificiary_id' => $benificiary->id,
            'retailer_id' => $retailers->first()->id,
        ]);

        $this->assertDatabaseHas('benificiary_retailer', [
            'benificiary_id' => $benificiary->id,
            'retailer_id' => $retailers->last()->id,
        ]);
    }
}
