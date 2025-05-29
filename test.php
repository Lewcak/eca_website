<?php
// test_db.php - Database Connection and Insert Test

// Enable full error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>Database Connection Test</h2>";

// 1. Test database connection
include('assets/server/db.php');

if (!$conn) {
    die("<p style='color:red;'>Failed to include db.php or connection failed</p>");
}

echo "<p style='color:green;'>✓ Database connection successful</p>";

// 2. Check if users table exists
try {
    $result = $conn->query("SELECT 1 FROM users LIMIT 1");
    echo "<p style='color:green;'>✓ Users table exists</p>";
} catch (PDOException $e) {
    die("<p style='color:red;'>Users table doesn't exist or isn't accessible. Error: " . $e->getMessage() . "</p>");
}

// 3. Test inserting a user
$test_username = "testuser_" . rand(100, 999);
$test_email = $test_username . "@test.com";
$test_password = password_hash("test123", PASSWORD_DEFAULT);

try {
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $insert_result = $stmt->execute([$test_username, $test_email, $test_password]);
    
    if ($insert_result) {
        $last_id = $conn->lastInsertId();
        echo "<p style='color:green;'>✓ Successfully inserted test user with ID: $last_id</p>";
        
        // Display the inserted user
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$last_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<h3>Inserted User Data:</h3>";
        echo "<pre>" . print_r($user, true) . "</pre>";
        
        // Clean up (optional)
        $conn->exec("DELETE FROM users WHERE id = $last_id");
        echo "<p>Test user removed (cleanup)</p>";
    } else {
        echo "<p style='color:red;'>Failed to insert test user</p>";
        echo "<pre>Error info: " . print_r($stmt->errorInfo(), true) . "</pre>";
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>Error inserting test user: " . $e->getMessage() . "</p>";
}

// 4. Show all current users (for verification)
echo "<h3>Current Users in Database:</h3>";
try {
    $stmt = $conn->query("SELECT id, username, email, created_at FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($users) > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Created At</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($user['id']) . "</td>";
            echo "<td>" . htmlspecialchars($user['username']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>" . htmlspecialchars($user['created_at']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No users found in the database</p>";
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>Error fetching users: " . $e->getMessage() . "</p>";
}

echo "<h3>Test Complete</h3>";
?>

<img src="assets/imgs/arknova.jpg" alt="Ark Nova Image" style="width:300px;">