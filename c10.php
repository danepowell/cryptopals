<?php
include 'crypt.php';

$cipher_text = file_get_contents('10.txt');
$cipher_text = base64_decode($cipher_text);
$key = 'YELLOW SUBMARINE';
$block_size = strlen($key);
$iv = str_pad("", $block_size, "\x00");

$last_block_text = $iv;
for ($i = 0; $i < strlen($cipher_text); $i = $i + $block_size) {
  $block_text = substr($cipher_text, $i, $block_size);
  $intermediate = ecb_decrypt($block_text, $key);
  $plain_text = fxor($intermediate, $last_block_text);
  $last_block_text = $block_text;
  echo $plain_text;
}
