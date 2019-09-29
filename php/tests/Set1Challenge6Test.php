<?php
namespace Danepowell\Cryptopals\Tests\Set1Challenge1;

use PHPUnit\Framework\TestCase;
use Danepowell\Cryptopals\Crypt;

class Set1Challenge6Test extends TestCase {

  public function testHamming() {
    $test_str1 = "this is a test";
    $test_str2 = "wokka wokka!!!";

    $test_bin1 = Crypt::str2bin($test_str1);
    $test_bin2 = Crypt::str2bin($test_str2);

    $distance = Crypt::hamming($test_bin1, $test_bin2);

    $this->assertEquals(37, $distance);
  }

  public function testChallenge() {
    $cipher_b64 = file_get_contents('../resources/6.txt');
    $cipher_text = base64_decode($cipher_b64);

    $key_sizes = range(2,50);

    $key_sizes = Crypt::brute_keysize($cipher_text, $key_sizes, 1);
    // echo "Probable keysizes: ", implode(', ', $key_sizes), PHP_EOL;

    $this->assertEquals(29, $key_sizes[0]);
    $key_chars = Crypt::english_chars();
    foreach ($key_sizes as $key_size) {
      $blocks = str_split($cipher_text, $key_size);

      $t_blocks = [];
      for ($i = 0; $i < $key_size; $i++) {
        $t_block = "";
        foreach ($blocks as $block) {
          $t_block .= substr($block, $i, 1);
        }
        $t_blocks[$i] = $t_block;
      }

      $key = "";
      foreach ($t_blocks as $cipher_str) {
        $candidate = Crypt::brute_force($cipher_str, $key_chars);
        $key .= $candidate['key'];
      }
      // echo $key, PHP_EOL;
      // echo recrypt($cipher_text, $key), PHP_EOL;

      $this->assertEquals("Terminator X: Bring the noise", $key);
    }
  }
}
