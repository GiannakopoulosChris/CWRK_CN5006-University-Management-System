<?php
/**
 * @desc Διαχειρίζεται τη λογική της εγγραφής χρήστη (Φοιτητής/Καθηγητής) και την εισαγωγή στη βάση δεδομένων.
 */
// Συμπερίληψη αρχείου σύνδεσης
require_once "db.php";

// Ορισμός κωδικών
define('STUDENT_CODE', 'STUD2025');
define('PROFESSOR_CODE', 'PROF2025');

$username = "";
$email = "";
$role_id = "";
$registration_code = "";
$password = "";

// Επεξεργασία δεδομένων φόρμας
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Λήψη και καθαρισμός δεδομένων
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $role_id = trim($_POST["role"]); // 1 = Student, 2 = Professor
    $registration_code = trim($_POST["reg_code"]);
    $password = trim($_POST["password"]);

    $error_msg = "";

    // 2. Έλεγχος Κωδικού Εγγραφής
    if ($role_id == 1 && $registration_code !== STUDENT_CODE) {
        $error_msg = "Λάθος κωδικός εγγραφής για Φοιτητή.";
    } elseif ($role_id == 2 && $registration_code !== PROFESSOR_CODE) {
        $error_msg = "Λάθος κωδικός εγγραφής για Καθηγητή.";
    } elseif ($role_id != 1 && $role_id != 2) {
        $error_msg = "Μη έγκυρος ρόλος.";
    }

    // 3. Έλεγχος αν το email υπάρχει ήδη
    if (empty($error_msg)) {
        $sql_check = "SELECT user_id FROM users WHERE email = ?";
        if ($stmt_check = $db->prepare($sql_check)) {
            $stmt_check->bind_param("s", $email);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows == 1) {
                $error_msg = "Αυτό το email χρησιμοποιείται ήδη.";
            }
            $stmt_check->close();
        }
    }

    // 4. Εισαγωγή χρήστη αν δεν υπάρχουν σφάλματα
    if (empty($error_msg)) {
        // Κρυπτογράφηση κωδικού
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql_insert = "INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)";
        
        if ($stmt_insert = $db->prepare($sql_insert)) {
            $stmt_insert->bind_param("sssi", $username, $email, $hashed_password, $role_id);
            
            if ($stmt_insert->execute()) {
                // Επιτυχής εγγραφή -> Ανακατεύθυνση στη σελίδα login
                header("location: ../login.php?success=1");
                exit;
            } else {
                $error_msg = "Κάτι πήγε στραβά. Προσπαθήστε ξανά.";
            }
            $stmt_insert->close();
        }
    }

    // Αν υπάρχει σφάλμα, ανακατεύθυνση πίσω στη φόρμα εγγραφής με μήνυμα
    if (!empty($error_msg)) {
        header("location: ../register.php?error=" . urlencode($error_msg));
        exit;
    }

    $db->close();
}
?>