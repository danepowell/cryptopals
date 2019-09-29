<?php
namespace Danepowell\Cryptopals\Tests\Set1Challenge1;

use PHPUnit\Framework\TestCase;
use Danepowell\Cryptopals\Crypt;

class Set2Challenge10Test extends TestCase {

  public function testChallenge() {
    $cipher_text = file_get_contents('../resources/10.txt');
    $cipher_text = base64_decode($cipher_text);
    $key = 'YELLOW SUBMARINE';
    $block_size = strlen($key);
    $iv = str_pad("", $block_size, "\x00");

    $plain_text = Crypt::cbc_decrypt($cipher_text, $key, $iv);

    $this->assertEquals("I'm back and I'm ringin' the bell ", explode("\n", $plain_text)[0]);

  }
}
