<?php
include 'crypt.php';

$cipher_text = file_get_contents('7.txt');

$plain_text = ecb_decrypt($cipher_text, 'YELLOW SUBMARINE');

echo $plain_text, PHP_EOL;

assert_equal("I'm back and I'm ringin' the bell ", explode("\n", $plain_text)[0]);