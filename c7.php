<?php
include 'crypt.php';

$cipher_text = file_get_contents('7.txt');

$plain_text = openssl_decrypt($cipher_text, 'AES-128-ECB', 'YELLOW SUBMARINE');

echo $plain_text, "\r\n";