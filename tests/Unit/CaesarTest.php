<?php

namespace Tests\Unit;

use App\Libs\Caesar;
use PHPUnit\Framework\TestCase;

class CaesarTest extends TestCase
{
	private $clear = "Weit hinten, hinter den Wortbergen, fern der Länder Vokalien und Konsonantien leben die Blindtexte. Abgeschieden wohnen sie in Buchstabhausen an der Küste des Semantik, eines großen Sprachozeans.";
	private $encrypted = "Iquf tuzfqz, tuzfqd pqz Iadfnqdsqz, rqdz pqd Xäzpqd Hawmxuqz gzp Wazeazmzfuqz xqnqz puq Nxuzpfqjfq. Mnsqeotuqpqz iatzqz euq uz Ngotefmntmgeqz mz pqd Wüefq pqe Eqymzfuw, quzqe sdaßqz Ebdmotalqmze.";

	public function testEncrypt()
	{
		$encrypted = (new Caesar)->encrypt($this->clear, 12);
		$this->assertEquals($this->encrypted, $encrypted);
	}

	public function testDecrypt()
	{
		$decrypted = (new Caesar())->decrypt($this->encrypted,12);
		$this->assertEquals($this->clear, $decrypted);
	}

	public function testCrack()
	{
		$key = rand(1,24);
		$encrypted = (new Caesar())->encrypt($this->clear, $key);
		$crackedKey = (new Caesar())->crack($encrypted);

		$this->assertEquals($key, $crackedKey);
	}
}