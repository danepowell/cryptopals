<?php
include 'crypt.php';

$cipher_text = file_get_contents('10.txt');
$cipher_text = base64_decode($cipher_text);
$key = 'YELLOW SUBMARINE';
$block_size = strlen($key);
$iv = str_pad("", $block_size, "\x00");

$plain_text = cbc_decrypt($cipher_text, $key, $iv);

assert_equal("I'm back and I'm ringin' the bell ", explode("\n", $plain_text)[0]);
