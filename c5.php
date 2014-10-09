<?php
include 'crypt.php';

$plain_text = "Burning 'em, if you ain't quick and nimble\nI go crazy when I hear a cymbal";
$key = "ICE";
print(bin2hex(recrypt($plain_text, $key)) . "\r\n");
