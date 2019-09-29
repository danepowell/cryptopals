<?php
namespace Danepowell\Cryptopals\Tests\Set1Challenge1;

use PHPUnit\Framework\TestCase;
use Danepowell\Cryptopals\Crypt;

class Set2Challenge11Test extends TestCase {

  public function testChallenge() {
    $plain_text = str_pad("", 48, '1');
    $oracle = Crypt::encryption_oracle($plain_text);

    if (Crypt::detect_dupe($oracle['cipher_text'])) {
      $mode = 'ECB';
    }
    else {
      $mode = 'CBC';
    }

    $this->assertEquals($oracle['mode'], $mode);
  }
}
