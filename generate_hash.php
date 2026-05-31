<?php
// Generate password hash
$password = 'adminwarframe052904';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Password: " . $password . "\n";
echo "Hash: " . $hash . "\n";
echo "\nCopy this hash and update your database:\n";
echo "UPDATE admins SET password = '" . $hash . "' WHERE email = 'dante@gawangpinas.com';";
