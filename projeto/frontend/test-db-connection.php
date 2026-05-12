<?php
// Test various MySQL credentials combinations

$credentials = [
    ['host' => 'localhost', 'user' => 'root', 'pass' => ''],
    ['host' => '127.0.0.1', 'user' => 'root', 'pass' => ''],
    ['host' => 'localhost', 'user' => 'root', 'pass' => 'root'],
    ['host' => 'localhost', 'user' => 'root', 'pass' => 'password'],
];

foreach ($credentials as $cred) {
    try {
        $pdo = new PDO(
            "mysql:host={$cred['host']}:3306",
            $cred['user'],
            $cred['pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        echo "✓ SUCCESS: host={$cred['host']}, user={$cred['user']}, pass='{$cred['pass']}'\n";
        $pdo = null;
    } catch (PDOException $e) {
        echo "✗ FAILED: host={$cred['host']}, user={$cred['user']}, pass='{$cred['pass']}' - " . $e->getMessage() . "\n";
    }
}
?>
