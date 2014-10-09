<?php
include 'crypt.php';

$str1 = '1c0111001f010100061a024b53535009181c';
$str2 = '686974207468652062756c6c277320657965';
print(bin2hex(fxor(hex2bin($str1), hex2bin($str2))));
