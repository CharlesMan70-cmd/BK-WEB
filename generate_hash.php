<?php
// generate_hash.php
$password_plain = '123';  // password asli yang mau di-hash
$hash = password_hash($password_plain, PASSWORD_DEFAULT);
echo "Hash password untuk '$password_plain' adalah: <br>";
echo "<pre>$hash</pre>";
