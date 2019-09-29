<?php
namespace Danepowell\Cryptopals\Tests\Set1Challenge1;

use PHPUnit\Framework\TestCase;
use Danepowell\Cryptopals\Crypt;

class Set1Challenge2Test extends TestCase {
  public function testChallenge() {
    $str1 = '1c0111001f010100061a024b53535009181c';
    $str2 = '686974207468652062756c6c277320657965';
    $expected = '746865206b696420646f6e277420706c6179';

    $result = bin2hex(Crypt::fxor(hex2bin($str1), hex2bin($str2)));

    $this->assertEquals($expected, $result);
  }
}
