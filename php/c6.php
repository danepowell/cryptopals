<?php
include 'crypt.php';

// PART 1: Test hamming distance computation.
$test_str1 = "this is a test";
$test_str2 = "wokka wokka!!!";

$test_bin1 = str2bin($test_str1);
$test_bin2 = str2bin($test_str2);

$distance = hamming($test_bin1, $test_bin2);
echo "Testing Hamming distance", $distance;
assert_equal(37, $distance);

// PART 2: Decrypt file.
$cipher_b64 = file_get_contents('6.txt');
$cipher_text = base64_decode($cipher_b64);

$key_sizes = range(2,50);

$key_sizes = brute_keysize($cipher_text, $key_sizes, 1);
echo "Probable keysizes: ", implode(', ', $key_sizes), PHP_EOL;

assert_equal(29, $key_sizes[0]);
$key_chars = english_chars();
foreach ($key_sizes as $key_size) {
  $blocks = str_split($cipher_text, $key_size);

  $t_blocks = array();
  for ($i = 0; $i < $key_size; $i++) {
    $t_block = "";
    foreach ($blocks as $block) {
      $t_block .= substr($block, $i, 1);
    }
    $t_blocks[$i] = $t_block;
  }

  $key = "";
  foreach ($t_blocks as $cipher_str) {
    $candidate = brute_force($cipher_str, $key_chars);
    $key .= $candidate['key'];
  }
  echo $key, PHP_EOL;
  echo recrypt($cipher_text, $key), PHP_EOL;

  assert_equal("Terminator X: Bring the noise", $key);
}
