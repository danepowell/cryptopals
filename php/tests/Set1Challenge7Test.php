<?php
namespace Danepowell\Cryptopals\Tests\Set1Challenge1;

use PHPUnit\Framework\TestCase;
use Danepowell\Cryptopals\Crypt;

class Set1Challenge7Test extends TestCase {

  public function testChallenge() {
    $cipher_text = file_get_contents('../resources/7.txt');
    $cipher_text = base64_decode($cipher_text);
    $plain_text = Crypt::ecb_decrypt($cipher_text, 'YELLOW SUBMARINE');

    // echo $plain_text, PHP_EOL;

    $this->assertEquals("I'm back and I'm ringin' the bell ", explode("\n", $plain_text)[0]);
  }
}
