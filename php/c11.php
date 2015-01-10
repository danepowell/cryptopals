<?php
include 'crypt.php';

$plain_text = str_pad("", 48, '1');
$oracle = encryption_oracle($plain_text);

if (detect_dupe($oracle['cipher_text'])) {
  $mode = 'ECB';
}
else {
  $mode = 'CBC';
}

assert_equal($oracle['mode'], $mode);