<?php
include 'crypt.php';

$plain_text = "YELLOW SUBMARINE";
$padded_text = pad_pkcs7($plain_text, 20);

assert_equal("YELLOW SUBMARINE\x04\x04\x04\x04", $padded_text);