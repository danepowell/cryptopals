<?php
include 'crypt.php';

$cipher_text = file_get_contents('10.txt');
$key = 'YELLOW SUBMARINE';

$plain_text = openssl_decrypt($cipher_text, 'AES-128-ECB', 'YELLOW SUBMARINE');

echo $plain_text, PHP_EOL;