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
    <title>Σύνδεση Χρήστη - Μητροπολιτικό Κολλέγιο</title>
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
            <h2>Σύνδεση Χρήστη</h2>
            <p>Συμπληρώστε τα στοιχεία σας για να συνδεθείτε.</p>
            
            <?php
            // Εμφάνιση μηνυμάτων σφάλματος/επιτυχίας
            if (!empty($_GET['error'])) {
                echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
            }
            if (!empty($_GET['success'])) {
                echo '<div class="alert alert-success">Η εγγραφή ολοκληρώθηκε! Μπορείτε να συνδεθείτε.</div>';
            }
            ?>

            <form action="includes/login_handler.php" method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Κωδικός Πρόσβασης</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn">Σύνδεση</button>
                </div>
            </form>
            <p>Δεν έχετε λογαριασμό; <a href="register.php">Εγγραφείτε εδώ</a>.</p>
        </div>
    </main>

    <footer class="main-footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Μητροπολιτικό Κολλέγιο.</p>
        </div>
    </footer>
</body>
</html>