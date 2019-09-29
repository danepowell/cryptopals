<?php
namespace Danepowell\Cryptopals\Tests\Set1Challenge1;

use PHPUnit\Framework\TestCase;
use Danepowell\Cryptopals\Crypt;

class Set1Challenge5Test extends TestCase {
  public function testChallenge() {
    $plain_text = "Burning 'em, if you ain't quick and nimble\nI go crazy when I hear a cymbal";
    $key = "ICE";

    $result = bin2hex(Crypt::recrypt($plain_text, $key));

    $this->assertEquals('0b3637272a2b2e63622c2e69692a23693a2a3c6324202d623d63343c2a26226324272765272a282b2f20430a652e2c652a3124333a653e2b2027630c692b20283165286326302e27282f', $result);
  }
}
