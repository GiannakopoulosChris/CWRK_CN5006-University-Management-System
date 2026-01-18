<?php
// Αν ο χρήστης είναι ήδη συνδεδεμένος, ανακατεύθυνση στο dashboard
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Εγγραφή Χρήστη - Μητροπολιτικό Κολλέγιο</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <a href="index.php" class="logo-link">
                <img src="pictures/logo.png" alt="Λογότυπο Μητροπολιτικό Κολλέγιο" class="logo">
            </a>
        </div>
    </header>

    <main>
        <div class="form-container">
            <h2>Δημιουργία Λογαριασμού</h2>
            <p>Συμπληρώστε τη φόρμα για να εγγραφείτε.</p>

            <?php
            // Εμφάνιση μηνυμάτων σφάλματος
            if (!empty($_GET['error'])) {
                echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
            }
            ?>

            <form action="includes/register_handler.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Κωδικός Πρόσβασης</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="form-group">
                    <label for="role">Επιλογή Ρόλου</label>
                    <select name="role" id="role" required>
                        <option value="">-- Επιλέξτε --</option>
                        <option value="1">Φοιτητής</option>
                        <option value="2">Καθηγητής</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="reg_code">Ειδικός Κωδικός Εγγραφής</label>
                    <input type="text" name="reg_code" id="reg_code" required>
                    <small>STUD2025 για Φοιτητές, PROF2025 για Καθηγητές</small>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn">Εγγραφή</button>
                </div>
            </form>
            <p>Έχετε ήδη λογαριασμό; <a href="login.php">Συνδεθείτε εδώ</a>.</p>
        </div>
    </main>

    <footer class="main-footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Μητροπολιτικό Κολλέγιο.</p>
        </div>
    </footer>
</body>
</html>