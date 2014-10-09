<?php
include 'crypt.php';

$cipher_hex = '1b37373331363f78151b7f2b783431333d78397828372d363c78373e783a393b3736';
$cipher_text = hex2bin($cipher_hex);
$keys = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));
$possibles = brute_force($cipher_text, $keys);
usort($possibles, 'sort_by_score');
$possibles = array_reverse($possibles);
pp_text($possibles[0]);