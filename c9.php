<?php
include 'crypt.php';

$plain_text = "YELLOW SUBMARINE";
echo pad_pkcs7($plain_text, 20);