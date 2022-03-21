<?php

namespace Tests\Unit;

use App\Models\posts;
use App\Models\User;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class PostsTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreatePostsWithAuthenication()
    {
        $data = [
            'author' => "Ajadi Paul",
            'description' => "This is a test post",
            'topic' => "Fashion post",
            'image' => "https://via.placeholder.com/640x480.png/00ff22?text=sed&h=650&w=940"
        ];

        $response = $this->json('POST', '/api/post/create',$data);
        $response->assertStatus(401);
        $response->assertJson(['message' => "Unauthenticated."]);
    }

    public function testPopulateModels()
    {
        $user = User::factory()->make();
        $posts = posts::factory()->count(25)->make();
        $this->assertTrue(true);
    }

    /**
    public function testCreatePosts()
    {
        $post = [
            'author' => "Ajadi Paul",
            'description' => "This is a test post",
            'topic' => "Fashion post",
            'image' => "https://via.placeholder.com/640x480.png/00ff22?text=sed&h=650&w=940"
        ];

        $user = User::factory()->make();
        $response = $this->actingAs($user, 'web')->json('POST',  '/api/post/create',$post);
        $response->assertStatus(500);
        $response->assertJson(['status_code' => 200]);
        $response->assertJson(['message' => "Post created successfully"]);
        $response->assertJson(['post' => $post]);
    }
    */

    public function testGettingAllPosts()
    {
            $response = $this->json('GET', '/api/feed/posts');
            $response->assertStatus(200);

            $response->assertJsonStructure([
                "current_page",
                "data" => [
                    [
                        'id',
                        'author',
                        'topic',
                        'description',
                        'image',
                        'likes',
                        'created_at',
                        'post_like_users'
                    ]
                ]
            ]);
    }

    public function testDeletePost()
    {
        $response = $this->json('GET', '/api/feed/posts');
        $response->assertStatus(200);

        $post = $response->getData()->data[0];

        $user = User::factory()->make();
        $delete = $this->actingAs($user, 'web')->json('DELETE', '/api/post/delete/'.$post->id);
        
        $delete->assertStatus(200);
    }

}
