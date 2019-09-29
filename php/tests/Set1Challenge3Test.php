<?php
namespace Danepowell\Cryptopals\Tests\Set1Challenge1;

use PHPUnit\Framework\TestCase;
use Danepowell\Cryptopals\Crypt;

class Set1Challenge3Test extends TestCase {
  public function testChallenge() {
    $cipher_hex = '1b37373331363f78151b7f2b783431333d78397828372d363c78373e783a393b3736';

    $cipher_text = hex2bin($cipher_hex);
    $keys = Crypt::english_chars();
    $plain_text = Crypt::brute_force($cipher_text, $keys);

    // Crypt::pp_text($plain_text);
    $this->assertEquals('X', $plain_text['key']);
  }
}
