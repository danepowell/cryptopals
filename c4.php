<?php
include 'crypt.php';

$cipher_hexes = file('4.txt');

$keys = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));
$possibles = array();
foreach ($cipher_hexes as $cipher_hex) {
  $cipher_text = hex2bin(trim($cipher_hex));
  $possibles = array_merge($possibles, brute_force($cipher_text, $keys));
}
usort($possibles, 'sort_by_score');
$candidate = array_pop($possibles);

pp_text($candidate);
assert_equal("Now that the party is jumping\n", $candidate['text']);