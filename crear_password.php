<?php

$password = 'Admin123';

$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h3>Hash generado:</h3>";
echo "<p>" . htmlspecialchars($hash) . "</p>";