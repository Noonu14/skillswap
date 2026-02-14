<?php
require 'db.php';

try {
    // Read the new schema file
    $sql = file_get_contents('schema_v3.sql');
    
    // Execute schema
    $pdo->exec($sql);
    
    echo "<h1>Database V3 Setup Complete</h1>";
    echo "<p>All tables have been reset and upgraded to the new structure.</p>";
    echo "<a href='register.php'>Go to Registration</a>";

} catch (PDOException $e) {
    die("Setup failed: " . $e->getMessage());
}
?>
