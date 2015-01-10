<?php
include 'crypt.php';

$cipher_hex = '1b37373331363f78151b7f2b783431333d78397828372d363c78373e783a393b3736';

$cipher_text = hex2bin($cipher_hex);
$keys = english_chars();
$plain_text = brute_force($cipher_text, $keys);

pp_text($plain_text);
assert_equal('X', $plain_text['key']);