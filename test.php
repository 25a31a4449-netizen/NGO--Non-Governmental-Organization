<?php
require_once 'config.php';
echo "Config loaded successfully!<br>";
echo "Database connection: " . (isset($conn) ? "Connected" : "Not connected");
?>