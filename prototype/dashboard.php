<?php
/**
 * @desc Προστατευμένη σελίδα (Dashboard). Ελέγχει αν ο χρήστης είναι συνδεδεμένος (session)
 * και εμφανίζει περιεχόμενο ανάλογα με τον ρόλο του (RBAC).
 */
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php"); // Ανακατεύθυνση στη σύνδεση
    exit;
}

// Λήψη ρόλου από το session
$role_id = $_SESSION["role_id"]; // 1 = Student, 2 = Professor
$username = htmlspecialchars($_SESSION["username"]);

?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Πίνακας Ελέγχου - Μητροπολιτικό Κολλέγιο</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header class="main-header">
        <div class="container">
            <a href="index.php" class="logo-link">
                <img src="pictures/logo.png" alt="Λογότυπο Μητροπολιτικό Κολλέγιο" class="logo">
            </a>
            <nav class="main-nav">
                <span>Καλώς ήρθες, <strong><?php echo $username; ?></strong>!</span>
                <a href="logout.php" class="btn btn-secondary">Αποσύνδεση</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="container content-section">
            <h1>Πίνακας Ελέγχου</h1>
            
            <?php if ($role_id == 1): // Περιεχόμενο για Φοιτητή ?>
                
                <div class="dashboard-welcome alert alert-success">
                     <h3>Καλωσήρθες, Φοιτητή!</h3>
                     <p>Αυτή είναι η προσωπική σου σελίδα. Εδώ θα μπορείς να δεις τις βαθμολογίες σου, τα μαθήματα και τις ανακοινώσεις.</p>

                     <hr>
                     <div class="mt-3">
                         <p class="fw-bold">Γρήγορες Ενέργειες:</p>
                         <a href="student/view_courses.php" class="btn btn-success shadow-sm">
                            <i class="bi bi-book"></i> Προβολή Διαθέσιμων Μαθημάτων
                         </a>
                         <a href="student/view_assignments_list.php" class="btn btn-info text-white shadow-sm">
                            <i class="bi bi-card-list"></i> Προβολή Εργασιών
                            </a>
                         <a href="student/view_assignments.php" class="btn btn-outline-success shadow-sm ms-2">
                            <i class="bi bi-pencil-square"></i> Προβολή Βαθμολογιών
                        </a>
                     </div>
                </div>
                
                <?php elseif ($role_id == 2): // Περιεχόμενο για Καθηγητή ?>
    
                    <div class="dashboard-welcome alert alert-info">
                         <h3>Καλωσήρθατε, Καθηγητή!</h3>
                        <p>Αυτή είναι η προσωπική σας σελίδα. Από εδώ μπορείτε να διαχειριστείτε τα μαθήματά σας και να καταχωρήσετε βαθμολογίες.</p>
        
                        <hr>
                        <div class="mt-3">
                             <p class="fw-bold">Γρήγορες Ενέργειες:</p>
                             <div class="d-flex gap-2">
                                <a href="professor/view_courses.php" class="btn btn-outline-primary shadow-sm">
                                     <i class="bi bi-journal-bookmark"></i> Προβολή/Διαχείριση Μαθημάτων
                                </a>
                                <a href="professor/add_course.php" class="btn btn-primary shadow-sm">
                                     <i class="bi bi-plus-circle"></i> Δημιουργία Νέου Μαθήματος
                                </a>
                                <a href="professor/add_assignment.php" class="btn btn-outline-primary shadow-sm">
                                    <i class="bi bi-file-earmark-plus"></i> Ανάθεση Νέας Εργασίας
                                </a>
                                <a href="professor/view_submissions.php" class="btn btn-primary shadow-sm ms-2">
                                    <i class="bi bi-star"></i> Βαθμολόγηση Εργασιών
                                </a>
                            </div>
                        </div>
                     </div>

                <?php endif; ?>

            <p>Αυτή η σελίδα είναι προσβάσιμη μόνο σε συνδεδεμένους χρήστες. Το περιεχόμενο που βλέπεις αλλάζει ανάλογα με τον ρόλο σου (Role-Based Access Control).</p>

        </div>
    </main>

    <footer class="main-footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Μητροπολιτικό Κολλέγιο.</p>
        </div>
    </footer>
</body>
</html>