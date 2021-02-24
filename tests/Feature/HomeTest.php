<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function testHomePageIsWorkingCorrectly()
    {
        $response = $this->get('/');

        $response->assertSeeText('Home Screen');
    }

    public function testContactPageIsWorkingCorrectly()
    {
        $response = $this->get('/contacts');

        $response->assertSeeText('Contacts');
    }
}
