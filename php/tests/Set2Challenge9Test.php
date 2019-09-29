<?php
namespace Danepowell\Cryptopals\Tests\Set1Challenge1;

use PHPUnit\Framework\TestCase;
use Danepowell\Cryptopals\Crypt;

class Set2Challenge9Test extends TestCase {

  public function testChallenge() {
    $plain_text = "YELLOW SUBMARINE";
    $padded_text = Crypt::pad_pkcs7($plain_text, 20);

    $this->assertEquals("YELLOW SUBMARINE\x04\x04\x04\x04", $padded_text);
  }
}
