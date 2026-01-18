<?php
/**
 * @file db.php
 * @desc Ορίζει τις σταθερές για τη σύνδεση με τη βάση δεδομένων και δημιουργεί το αντικείμενο σύνδεσης mysqli.
 * Επίσης, ξεκινάει το PHP session για χρήση σε όλα τα αρχεία που το συμπεριλαμβάνουν.
 */

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'university_db');

// Προσπάθεια σύνδεσης
$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Έλεγχος σύνδεσης
if ($db === false) {
    die("ERROR: Could not connect. " . $db->connect_error);
}

// Ορισμός charset σε utf8mb4 για υποστήριξη ελληνικών
$db->set_charset("utf8mb4");

// Έναρξη του session σε κάθε αρχείο που το περιλαμβάνει
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>