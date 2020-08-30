<?php

namespace Tests\Feature;

use Tests\TestCase;

class PathTest extends TestCase
{
	public function testCaesarPath()
	{
		$response = $this->get('/caesar');

		$response->assertStatus(200);
	}
}