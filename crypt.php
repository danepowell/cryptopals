<?php
// TODO
// Add tests
// Profile and speed up

/**
 * Pretty print possible plain text.
 */
function pp_text($possible) {
  print ($possible['score'] . ' ' . $possible['text'] . "\r\n");
}

/**
 * Brute force a ciphertext.
 */

function brute_force($cipher_text, $keys) {
  $possibles = array();
  foreach ($keys as $key) {
    $possible = array();
    $key = str_pad("", strlen($cipher_text), $key);
    $plain_text = fxor($cipher_text, $key);
    $possible['text'] = $plain_text;
    $possible['score'] = smart_score($plain_text);
    $possible['key'] = $key;
    $possibles[] = $possible;
  }
  return $possibles;
}

/**
 * Score based on number of 'e's.
 */
function dumb_score($text) {
  $text = strtolower($text);
  return substr_count($text, 'e');
}

/**
 * Smart score based on character frequency.
 */
function smart_score($text) {
  // Quickly rule out non-standard characters.
  if (!preg_match('/^[\w\s"\']+$/', $text)) {
    return 1;
  }
  $text = strtolower($text);
  $frequencies = letter_frequency();
  $ss = 0.0;
  $length = strlen($text);
  foreach ($frequencies as $letter => $frequency) {
    $actual = substr_count($text, $letter) / $length;
    $ss += pow($actual - $frequency, 2);
  }
  return $ss;
}

/**
 * Convert hex to base 64.
 */
function hex2b64($hex) {
  $bin = hex2bin($hex);
  return base64_encode($bin);
}

/**
 * XOR two binary strings
 */
function fxor($str1, $str2) {
  if (strlen($str1) != strlen($str2)) {
    return FALSE;
  }
  return $str1 ^ $str2;
}

/**
 * Helper function to sort array by score.
 */
function sort_by_score($a, $b) {
  $diff = $b['score'] - $a['score'];
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
function letter_frequency() {
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
