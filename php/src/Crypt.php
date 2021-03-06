<?php

namespace Danepowell\Cryptopals;

use mysql_xdevapi\Exception;

class Crypt {

  /**
   * Convert hex to base 64.
   *
   * @param $hex
   *
   * @return string
   */
  public static function hex2b64($hex) {
    $bin = hex2bin($hex);
    return base64_encode($bin);
  }

  /**
   * XOR two binary strings
   *
   * @param $str1
   * @param $str2
   *
   * @return int
   */
  public static function fxor($str1, $str2) {
    if (strlen($str1) != strlen($str2)) {
      throw new Exception('Strings must be same length');
    }
    return $str1 ^ $str2;
  }

  /**
   * @return array of english characters.
   */
  public static function english_chars() {
    return array_map('chr', range(32, 126));
  }

  /**
   * Brute force a ciphertext.
   *
   * @param $cipher_text
   * @param $keys
   *
   * @return mixed
   */
  public static function brute_force($cipher_text, $keys) {
    $possibles = array();
    foreach ($keys as $key) {
      $possible = array();
      $plain_text = self::recrypt($cipher_text, $key);
      $possible['text'] = $plain_text;
      $possible['score'] = self::smart_score($plain_text);
      $possible['key'] = $key;
      $possibles[] = $possible;
    }
    usort($possibles, 'self::sort_by_score');
    return $possibles[0];
  }

  /**
   * Encrypt or decrypt a string using repeating-key xor.
   *
   * @param $text
   * @param $key
   *
   * @return int
   */
  public static function recrypt($text, $key) {
    $key = str_pad("", strlen($text), $key);
    return self::fxor($text, $key);
  }

  public static function detect_dupe($cipher_text) {
    $blocks = str_split($cipher_text, 16);
    $unique_blocks = array_unique($blocks);
    if (count($blocks) != count($unique_blocks)) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Encrypts data under a random key.
   *
   * @param $plain_text
   *
   * @return array
   */
  public static function encryption_oracle($plain_text) {
    $key = self::random_aes_key();
    $pre_byte_count = rand(5,10);
    $post_byte_count = rand(5,10);
    $plain_text = openssl_random_pseudo_bytes($pre_byte_count) . $plain_text;
    $plain_text .= openssl_random_pseudo_bytes($post_byte_count);
    if ((bool)rand(0,1)) {
      $mode = 'ECB';
      $cipher_text = self::ecb_encrypt($plain_text, $key);
    }
    else {
      $iv = self::random_aes_key();
      $mode = 'CBC';
      $cipher_text = self::cbc_encrypt($plain_text, $key, $iv);
    }
    return array('mode' => $mode, 'cipher_text' => $cipher_text);
  }

  /**
   * Returns a random AES key.
   */
  public static function random_aes_key() {
    return openssl_random_pseudo_bytes(16);
  }

  public static function cbc_encrypt($plain_text, $key, $iv) {
    $block_size = strlen($key);
    $last_block_text = $iv;
    $cipher_text = "";
    for ($i = 0; $i < strlen($plain_text); $i = $i + $block_size) {
      $block_text = substr($plain_text, $i, $block_size);
      $intermediate = self::fxor($block_text, $last_block_text);
      $cipher_block = self::ecb_encrypt($intermediate, $key);
      $last_block_text = $cipher_block;
      $cipher_text .= $cipher_block;
    }
    return $cipher_text;
  }

  /**
   * Decrypt string using CBC.
   *
   * @param $cipher_text
   * @param $key
   * @param $iv
   *
   * @return string
   */
  public static function cbc_decrypt($cipher_text, $key, $iv) {
    $block_size = strlen($key);
    $last_block_text = $iv;
    $plain_text = "";
    for ($i = 0; $i < strlen($cipher_text); $i = $i + $block_size) {
      $block_text = substr($cipher_text, $i, $block_size);
      $intermediate = self::ecb_decrypt($block_text, $key);
      $plain_text .= self::fxor($intermediate, $last_block_text);
      $last_block_text = $block_text;
    }
    return $plain_text;
  }

  /**
   * Decrypt string using openssl ECB mode.
   *
   * Cipher text and key must be binary strings.
   *
   * @param $cipher_text
   * @param $key
   *
   * @return bool|string
   */
  public static function ecb_decrypt($cipher_text, $key) {
    if (strlen($cipher_text) % strlen($key) != 0) {
      throw new Exception('Cipher length must be even multiple of key length.');
    }
    return openssl_decrypt($cipher_text, 'AES-128-ECB', $key, OPENSSL_RAW_DATA+OPENSSL_ZERO_PADDING);
  }

  /**
   * Encrypt string using openssl ECB mode.
   *
   * @param $plain_text
   * @param $key
   *
   * @return string
   */
  public static function ecb_encrypt($plain_text, $key) {
    $block_length = strlen($key);
    $text_length = strlen($plain_text);
    $remainder = $text_length % $block_length;
    $num_blocks = ceil($text_length / $block_length);
    if ($remainder != 0) {
      $plain_text = self::pad_pkcs7($plain_text, $num_blocks * $block_length);
    }
    return openssl_encrypt($plain_text, 'AES-128-ECB', $key, OPENSSL_RAW_DATA+OPENSSL_ZERO_PADDING);
  }

  /**
   * Pad string using pkcs7
   *
   * @param $plain_text
   * @param $pad_length
   *
   * @return string
   */
  public static function pad_pkcs7($plain_text, $pad_length) {
    return str_pad($plain_text, $pad_length, "\x04");
  }

  /**
   * Guess keysizes.
   *
   * @param $cipher_text
   * @param $key_sizes
   * @param $n
   *
   * @return array
   */
  public static function brute_keysize($cipher_text, $key_sizes, $n) {
    $distances = array();
    foreach ($key_sizes as $key_size) {
      $blocks = array();
      for ($i = 0; $i < 4; $i++) {
        $blocks[$i] = substr($cipher_text, $i * $key_size, $key_size);
      }
      $norm_distances = array();
      for ($i = 0; $i < 4; $i++) {
        for ($j = 0; $j < $i; $j++) {
          if ($i != $j) {
            $norm_distances[] = self::hamming(self::str2bin($blocks[$i]), self::str2bin($blocks[$j])) / $key_size;
          }
        }
      }
      $distance = array_sum($norm_distances) / count($norm_distances);
      $distances[] = array("key_size" => $key_size, "distance" => $distance);
    }

    usort($distances, 'self::sort_by_distance');

    $key_sizes = array();

    for ($i=0; $i < $n; $i++) {
      $key_sizes[] = $distances[$i]['key_size'];
    }
    return $key_sizes;
  }

  /**
   * Compute hamming distance between two strings.
   *
   * @param $str1
   * @param $str2
   *
   * @return int
   */
  public static function hamming($str1, $str2) {
    $arr1 = str_split($str1);
    $arr2 = str_split($str2);
    return count(array_diff_assoc($arr1, $arr2));
  }

  /**
   * Converts string to binary equivalent.
   *
   * @param $str
   *
   * @return string
   */
  public static function str2bin($str) {
    $bin = "";
    $arr = str_split($str);
    foreach ($arr as $char) {
      $bin .= sprintf("%08d", decbin(ord($char)));
    }
    return $bin;
  }

  /**
   * Test results.
   *
   * @param $expected
   * @param $actual
   */
  public static function assert_equal($expected, $actual) {
    print("\r\n");
    if ($expected == $actual) {
      print("PASS\r\n");
    }
    else {
      print("FAIL\r\n");
      print("Expected: " . $expected . "\r\n");
      print("Actual: " . $actual . "\r\n");
    }
  }

  /**
   * Pretty print possible plain text.
   *
   * @param $possible
   */
  public static function pp_text($possible) {
    print ($possible['score'] . ' ' . $possible['key'] . ' ' . $possible['text'] . "\r\n");
  }

  /**
   * Score based on number of 'e's.
   *
   * @param $text
   *
   * @return int
   */
  public static function dumb_score($text) {
    $text = strtolower($text);
    return substr_count($text, 'e');
  }

  /**
   * Smart score based on character frequency.
   *
   * @param $text
   *
   * @return float|int
   */
  public static function smart_score($text) {
    $text = strtolower($text);
    $frequencies = self::letter_frequency();
    $ss = 0.0;
    $length = strlen($text);
    foreach ($frequencies as $letter => $frequency) {
      $actual = substr_count($text, $letter) / $length;
      $ss += pow($actual - $frequency, 2);
    }
    return $ss;
  }

  /**
   * Helper function to sort array by score.
   *
   * @param $a
   * @param $b
   *
   * @return int
   */
  public static function sort_by_score($a, $b) {
    return self::sort_array_by_value($a, $b, 'score');
  }

  /**
   * Helper function to sort array by distance.
   *
   * @param $a
   * @param $b
   *
   * @return int
   */
  public static function sort_by_distance($a, $b) {
    return self::sort_array_by_value($a, $b, 'distance');
  }

  /**
   * Sort arrays by value.
   */
  public static function sort_array_by_value($a, $b, $key) {
    $diff = $a[$key] - $b[$key];

    if ($diff < 0) {
      return -1;
    }
    elseif ($diff > 0) {
      return 1;
    }
    else {
      return 0;
    }
  }

  /**
   * Returns letter frequencies.
   */
  public static function letter_frequency() {
    return array(
      'a' => 0.08167,
      'b' => 0.01492,
      'c' => 0.02782,
      'd' => 0.04253,
      'e' => 0.12702,
      'f' => 0.02228,
      'g' => 0.02015,
      'h' => 0.06094,
      'i' => 0.06966,
      'j' => 0.00153,
      'k' => 0.00772,
      'l' => 0.04025,
      'm' => 0.02406,
      'n' => 0.06749,
      'o' => 0.07507,
      'p' => 0.01929,
      'q' => 0.00095,
      'r' => 0.05987,
      's' => 0.06327,
      't' => 0.09056,
      'u' => 0.02758,
      'v' => 0.00978,
      'w' => 0.02360,
      'x' => 0.00150,
      'y' => 0.01974,
      'z' => 0.00074,
      ' ' => 0.21,
    );
  }
}
