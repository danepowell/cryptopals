<?php
include 'crypt.php';

$cipher_text = file_get_contents('10.txt');
$key = 'YELLOW SUBMARINE';
$cipher_text = base64_decode($cipher_text);
$cipher_text = substr($cipher_text, 0, 16);
$intermediate = ecb_decrypt($cipher_text, $key);
echo $intermediate;
//$plain_text = fxor($intermediate, $key);
