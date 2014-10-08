<?php
// TODO
// Add tests
// Profile and speed up
// Separate challenges and function library

// CHALLENGE 1
/**
$hex = '49276d206b696c6c696e6720796f757220627261696e206c696b65206120706f69736f6e6f7573206d757368726f6f6d';
print(hex2b64($hex));
**/

// CHALLENGE 2
/**
$str1 = '1c0111001f010100061a024b53535009181c';
$str2 = '686974207468652062756c6c277320657965';
print(bin2hex(fxor(hex2bin($str1), hex2bin($str2))));
**/

// CHALLENGE 3
/**
$hex = '1b37373331363f78151b7f2b783431333d78397828372d363c78373e783a393b3736';
$bin = hex2bin($hex);
$possibles = brute_force($bin);
usort($possibles, 'sort_by_score');
$possibles = array_reverse($possibles);
pp_text($possibles[0]);
**/

// CHALLENGE 4
// TODO use array_pop or something to shorten this

$ciphers = file('4.txt');
$possibles = array();
foreach ($ciphers as $cipher) {
  $cipher = trim($cipher);
  $possibles = array_merge($possibles, brute_force(hex2bin($cipher)));
}
usort($possibles, 'sort_by_score');
pp_text(array_pop($possibles));

/**
 * Pretty print possible plain text.
 */
function pp_text($possible) {
  print ($possible['score'] . ' ' . $possible['text'] . "\r\n");
}

/**
 * Brute force a ciphertext.
 */
function brute_force($bin) {
  $possibles = array();
  $possibles = array_merge($possibles, brute_force_range($bin, 'A', 'Z'));
  $possibles = array_merge($possibles, brute_force_range($bin, 'a', 'z'));
  $possibles = array_merge($possibles, brute_force_range($bin, '0', '9'));
  return $possibles;
}

function brute_force_range($bin, $r1, $r2) {
  $possibles = array();
  foreach (range($r1, $r2) as $char) {
    $possible = array();
    $key = array_fill(0, strlen($bin), $char);
    $key = implode($key);
    $text = fxor($bin, $key);
    $possible['text'] = $text;
    $possible['score'] = smart_score($text);
    $possible['key'] = $char;
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
