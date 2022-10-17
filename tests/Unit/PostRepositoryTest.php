<?php

namespace Tests\Unit;

use App\Repositories\PostRepository;
use PHPUnit\Framework\TestCase;

class PostRepositoryTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create()
    {
        $repository = $this->app->make(PostRepository::class);
        $payload = [
            'title' => 'heyaa',
            'body' => []
        ];
        $result = $repository->create($payload);
        $this->assertSame($payload['title'], $result->title, 'Different Titles');
    }

  

}
