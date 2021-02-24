<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\Comment;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function testNoBlogPostsWhenNothingInDatabase()
    {
        $response = $this->get('/posts');
        $response->assertSeeText('Nothing!!');
    }

    public function testSee1BlogPostWhereThereIs1WithNoComments()
    {
        $post = $this->createDummyPost();

        $response = $this->get('/posts');

        $response->assertSeeText('New title');
        $response->assertSeeText('No comments yet');

        $this->assertDatabaseHas('posts', [
            'title' => 'New title'
        ]);
    }

    public function testSee1BlogPostWithComments()
    {
        $user = $this->user();

        $post = $this->createDummyPost();
        Comment::factory()->count(4)->create([
            'commentable_id' => $post->id,
            'commentable_type' => 'App\Models\Post',
            'user_id' => $user->id
        ]);

        $response = $this->get('/posts');

        $response->assertSeeText('4 comments');
    }

    public function testStoreValid()
    {
        $params = [
            'title' => 'Valid title',
            'content' => 'At least 10 characters'
        ];

        $this->actingAs($this->user())->post('/posts', $params)->assertStatus(302)->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was created!');
    }

    public function testStoreFail()
    {
        $params = [
            'title' => 'x',
            'content' => 'x'
        ];

        $this->actingAs($this->user())->post('/posts', $params)->assertStatus(302)->assertSessionHas('errors');

        $messages = session('errors')->getMessages();

        $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');
    }

    public function testUpdateValid()
    {
        $user = $this->user();
        $post = $this->createDummyPost($user->id);

        $this->assertDatabaseHas('posts', ['title' => 'New title', 'content' => 'Content of the blog post']);

        $params = [
            'title' => 'A new named title',
            'content' => 'Content was changes'
        ];

        $this->actingAs($user)->put("/posts/{$post->id}", $params)->assertStatus(302)->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was updated!');
        $this->assertDatabaseMissing('posts', ['title' => 'New title', 'content' => 'Content of the blog post']);
        $this->assertDatabaseHas('posts', ['title' => 'A new named title']);
    }

    public function testDelete()
    {
        $user = $this->user();
        $post = $this->createDummyPost($user->id);

        $this->actingAs($user)->delete("/posts/{$post->id}")->assertStatus(302)->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was deleted!');
    }

    private function createDummyPost($userId = null): Post
    {
//        $post = new Post();
//        $post->title = 'New title';
//        $post->content = 'Content of the blog post';
//        $post->save();

        return Post::factory()->suspended()->create([
            'user_id' => $userId ?? $this->user()->id
        ]);

//        return $post;
    }
}
