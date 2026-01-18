<?php
/**
 * @desc Διαχειρίζεται τη λογική της σύνδεσης, ελέγχει τα διαπιστευτήρια, επαληθεύει τον κωδικό και δημιουργεί το PHP Session.
 */
// Συμπερίληψη αρχείου σύνδεσης (ξεκινάει και το session)
require_once "db.php";

$email = "";
$password = "";
$error_msg = "";

// Επεξεργασία φόρμας
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        $error_msg = "Συμπληρώστε email και κωδικό.";
    } else {
        // 1. Εύρεση χρήστη με βάση το email
        $sql = "SELECT user_id, username, password, role_id FROM users WHERE email = ?";
        
        if ($stmt = $db->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            // 2. Έλεγχος αν ο χρήστης βρέθηκε
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($user_id, $username, $hashed_password, $role_id);
                if ($stmt->fetch()) {
                    // 3. Επαλήθευση κωδικού
                    if (password_verify($password, $hashed_password)) {
                        // Επιτυχής σύνδεση!
                        // 4. Δημιουργία Session
                        $_SESSION["loggedin"] = true;
                        $_SESSION["user_id"] = $user_id;
                        $_SESSION["username"] = $username;
                        $_SESSION["role_id"] = $role_id; // 1=Student, 2=Professor

                        // Ανακατεύθυνση στο dashboard
                        header("location: ../dashboard.php");
                        exit;
                    } else {
                        $error_msg = "Λάθος email ή κωδικός.";
                    }
                }
            } else {
                $error_msg = "Λάθος email ή κωδικός.";
            }
            $stmt->close();
        }
    }

    // Αν υπάρχει σφάλμα, επιστροφή στη σελίδα login
    if (!empty($error_msg)) {
        header("location: ../login.php?error=" . urlencode($error_msg));
        exit;
    }
    $db->close();
}
?>