<?php
include 'crypt.php';

$cipher_hexes = file('4.txt', FILE_IGNORE_NEW_LINES);

$keys = english_chars();
$possibles = array();
foreach ($cipher_hexes as $cipher_hex) {
  $cipher_text = hex2bin($cipher_hex);
  $possibles[] = brute_force($cipher_text, $keys);
}
usort($possibles, 'sort_by_score');
$candidate = $possibles[0];

pp_text($candidate);
assert_equal("Now that the party is jumping\n", $candidate['text']);