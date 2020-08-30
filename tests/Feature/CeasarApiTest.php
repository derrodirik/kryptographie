<?php

namespace Tests\Feature;

use Tests\TestCase;

class CeasarApiTest extends TestCase
{
	private $clear = "Weit hinten, hinter den Wortbergen, fern der Länder Vokalien und Konsonantien leben die Blindtexte. Abgeschieden wohnen sie in Buchstabhausen an der Küste des Semantik, eines großen Sprachozeans.";
	private $encrypted = "Iquf tuzfqz, tuzfqd pqz Iadfnqdsqz, rqdz pqd Xäzpqd Hawmxuqz gzp Wazeazmzfuqz xqnqz puq Nxuzpfqjfq. Mnsqeotuqpqz iatzqz euq uz Ngotefmntmgeqz mz pqd Wüefq pqe Eqymzfuw, quzqe sdaßqz Ebdmotalqmze.";

	public function testEncrypt()
	{
		$response = $this->post('/api/caesar/encrypt', ['text' => $this->clear, 'key' => 12]);

		$response->assertStatus(200);
	}

	public function testDecrypt()
	{
		$response = $this->post('/api/caesar/decrypt', ['text' => $this->encrypted, 'key' => 12]);

		$response->assertStatus(200);
	}

	public function testCrack()
	{
		$response = $this->post('/api/caesar/crack', ['text' => $this->clear]);

		$response->assertStatus(200);
	}
}