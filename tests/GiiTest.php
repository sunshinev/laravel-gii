<?php

namespace Sunshinev\Gii\Test;

class GiiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCrud()
    {
        $response = $this->get('/gii/crud');

        $response->assertStatus(200);
    }

    public function testModel()
    {
        $response = $this->get('/gii/model');

        $response->assertStatus(200);
    }
}
