<?php
// Ενεργοποίηση αναφοράς σφαλμάτων για σιγουριά
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) { die("Πρέπει να συνδεθείτε."); }

$assignment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];
$message = "";

// 1. Λήψη πληροφοριών εργασίας
$sql = "SELECT title, instructions FROM assignments WHERE assignment_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $assignment_id);
$stmt->execute();
$assignment = $stmt->get_result()->fetch_assoc();

if (!$assignment) { die("Η εργασία δεν βρέθηκε."); }

// 2. ΛΟΓΙΚΗ ΑΠΟΘΗΚΕΥΣΗΣ
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $text = $_POST['submission_text'];
    
    // ΔΙΟΡΘΩΜΕΝΟ SQL: Χρήση 'comment' αντί για 'submission_text'
    $insert = "INSERT INTO submissions (assignment_id, student_id, comment) VALUES (?, ?, ?)";
    $stmt_in = $db->prepare($insert);
    $stmt_in->bind_param("iis", $assignment_id, $user_id, $text);
    
    if ($stmt_in->execute()) {
        $message = "<div class='alert alert-success shadow-sm'>Η εργασία υποβλήθηκε επιτυχώς!</div>";
    } else {
        $message = "<div class='alert alert-danger shadow-sm'>Σφάλμα: " . $db->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Υποβολή Εργασίας</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow border-0">
            <div class="card-header bg-success text-white p-3">
                <h4 class="mb-0">Υποβολή: <?php echo htmlspecialchars($assignment['title']); ?></h4>
            </div>
            <div class="card-body p-4">
                <div class="bg-light p-3 rounded mb-4 border-start border-4 border-success">
                    <h6 class="fw-bold text-success">Οδηγίες Καθηγητή:</h6>
                    <p class="mb-0 text-muted"><?php echo nl2br(htmlspecialchars($assignment['instructions'])); ?></p>
                </div>

                <?php echo $message; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Η Απάντησή σας:</label>
                        <textarea name="submission_text" class="form-control" rows="8" 
                                  placeholder="Γράψτε εδώ την απάντησή σας ή επικολλήστε έναν σύνδεσμο..." required></textarea>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="view_assignments.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Πίσω στις Εργασίες
                        </a>
                        <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm">
                            Οριστική Υποβολή
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>