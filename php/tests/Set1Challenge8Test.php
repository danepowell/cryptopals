<?php
namespace Danepowell\Cryptopals\Tests\Set1Challenge1;

use PHPUnit\Framework\TestCase;
use Danepowell\Cryptopals\Crypt;

class Set1Challenge8Test extends TestCase {

  public function testChallenge() {
    $cipher_hexes = file('../resources/8.txt', FILE_IGNORE_NEW_LINES);

    $aes_ciphers = array();
    foreach ($cipher_hexes as $cipher_hex) {
      if (Crypt::detect_dupe($cipher_hex)) {
        $aes_ciphers[] = $cipher_hex;
      }
    }

    /**
    echo "Possible AES ECB ciphers found:", PHP_EOL;
    foreach ($aes_ciphers as $aes_cipher) {
      echo substr($aes_ciphers[0], 0, 16), '...', PHP_EOL;
    }**/
    $expected = 'd880619740a8a19b7840a8a31c810a3d08649af70dc06f4fd5d2d69c744cd283e2dd052f6b641dbf9d11b0348542bb5708649af70dc06f4fd5d2d69c744cd2839475c9dfdbc1d46597949d9c7e82bf5a08649af70dc06f4fd5d2d69c744cd28397a93eab8d6aecd566489154789a6b0308649af70dc06f4fd5d2d69c744cd283d403180c98c8f6db1f2a3f9c4040deb0ab51b29933f2c123c58386b06fba186a';
    $this->assertEquals($expected, $aes_ciphers[0]);
  }
}
