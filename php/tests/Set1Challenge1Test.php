<?php
namespace Danepowell\Cryptopals\Tests\Set1Challenge1;

use PHPUnit\Framework\TestCase;
use Danepowell\Cryptopals\Crypt;

class Set1Challenge1Test extends TestCase {
  public function testChallenge() {
    $hex = '49276d206b696c6c696e6720796f757220627261696e206c696b65206120706f69736f6e6f7573206d757368726f6f6d';
    $expected = 'SSdtIGtpbGxpbmcgeW91ciBicmFpbiBsaWtlIGEgcG9pc29ub3VzIG11c2hyb29t';

    $result = Crypt::hex2b64($hex);

    $this->assertEquals($expected, $result);
  }
}
