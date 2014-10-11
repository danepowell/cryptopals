<?php
include 'crypt.php';

// PART 1: Test hamming distance computation.
$test_str1 = "this is a test";
$test_str2 = "wokka wokka!!!";

$test_bin1 = str2bin($test_str1);
$test_bin2 = str2bin($test_str2);

$distance = hamming($test_bin1, $test_bin2);
echo $distance;
assert_equal(37, $distance);

// PART 2: Decrypt file.
$cipher_b64 = file_get_contents('6.txt');
$cipher_text = base64_decode($cipher_b64);

$key_sizes = range(2,40);
$key_sizes = brute_keysize($cipher_text, $key_sizes, 4);

foreach ($key_sizes as $key_size) {
  $cipher_arr = str_split($cipher_text, $key_size);

  $blocks = array();
  for ($i = 0; $i < $key_size; $i++) {
    $blocks[$i] = "";
    foreach ($cipher_arr as $block) {
      $blocks[$i] .= substr($block, $i, 1);
    }
  }

  $keys = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));
  foreach ($blocks as $cipher_str) {
    $possibles = brute_force($cipher_str, $keys);
    usort($possibles, 'sort_by_score');
    $candidate = array_pop($possibles);
    pp_text($candidate);
  }
}