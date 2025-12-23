<?php
// The password you want to test
$password = "expert123";  // Example password you want to hash

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Display the hashed password
echo "Hashed Password: " . $hashedPassword . "<br>";

// For testing, you can also check if the hashed password matches the plaintext one
if (password_verify($password, $hashedPassword)) {
    echo "Password verified successfully!";
} else {
    echo "Password verification failed.";
}
?>
