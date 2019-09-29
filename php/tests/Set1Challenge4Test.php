<?php
namespace Danepowell\Cryptopals\Tests\Set1Challenge1;

use PHPUnit\Framework\TestCase;
use Danepowell\Cryptopals\Crypt;

class Set1Challenge4Test extends TestCase {
  public function testChallenge() {
    $cipher_hexes = file('../resources/4.txt', FILE_IGNORE_NEW_LINES);

    $keys = Crypt::english_chars();
    $possibles = array();
    foreach ($cipher_hexes as $cipher_hex) {
      $cipher_text = hex2bin($cipher_hex);
      $possibles[] = Crypt::brute_force($cipher_text, $keys);
    }
    usort($possibles, 'Danepowell\Cryptopals\Crypt::sort_by_score');
    $candidate = $possibles[0];

    //Crypt::pp_text($candidate);
    $this->assertEquals("Now that the party is jumping\n", $candidate['text']);
  }
}
