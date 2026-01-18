<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once '../includes/db.php';

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος και αν είναι καθηγητής (role_id = 2)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    die("Πρόσβαση μόνο για καθηγητές.");
}

$prof_id = $_SESSION['user_id'];
$message = "";

// Ενημέρωση βαθμολογίας και σχολίων αν σταλεί η φόρμα
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['grade_submission'])) {
    $sub_id = $_POST['submission_id'];
    $grade = $_POST['grade'];
    $feedback = $_POST['feedback'];

    // Ενημέρωση των στηλών grade και feedback στον πίνακα submissions
    $update_sql = "UPDATE submissions SET grade = ?, feedback = ? WHERE submission_id = ?";
    $stmt = $db->prepare($update_sql);
    $stmt->bind_param("dsi", $grade, $feedback, $sub_id);
    
    if ($stmt->execute()) {
        $message = "<div class='alert alert-success shadow-sm'>Η βαθμολογία και τα σχόλια καταχωρήθηκαν επιτυχώς!</div>";
    } else {
        $message = "<div class='alert alert-danger shadow-sm'>Σφάλμα κατά την ενημέρωση: " . $db->error . "</div>";
    }
}

// Λήψη υποβολών για τα μαθήματα που διδάσκει ο συγκεκριμένος καθηγητής
$sql = "SELECT s.*, u.username as student_name, a.title as assignment_title 
        FROM submissions s
        JOIN assignments a ON s.assignment_id = a.assignment_id
        JOIN courses c ON a.course_id = c.course_id
        JOIN users u ON s.student_id = u.user_id
        WHERE c.professor_id = ?
        ORDER BY s.submission_date DESC";

$stmt_list = $db->prepare($sql);
$stmt_list->bind_param("i", $prof_id);
$stmt_list->execute();
$result = $stmt_list->get_result();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Διαχείριση Υποβολών</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow border-0">
            <div class="card-header bg-dark text-white p-3">
                <h4 class="mb-0"><i class="bi bi-person-badge"></i> Διαχείριση Υποβολών & Βαθμολόγηση</h4>
            </div>
            <div class="card-body p-4">
                
                <?php echo $message; ?>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Φοιτητής</th>
                                <th>Εργασία</th>
                                <th>Απάντηση (Comment)</th>
                                <th>Τρέχων Βαθμός</th>
                                <th>Ενέργεια Βαθμολόγησης</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['student_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['assignment_title']); ?></td>
                                <td>
                                    <div class="p-2 bg-light rounded border" style="max-height: 100px; overflow-y: auto;">
                                        <small><?php echo nl2br(htmlspecialchars($row['comment'])); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <?php if($row['grade'] !== null): ?>
                                        <span class="badge bg-info text-dark fs-6"><?php echo $row['grade']; ?> / 10</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Χωρίς Βαθμό</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="POST" class="d-flex gap-2">
                                        <input type="hidden" name="submission_id" value="<?php echo $row['submission_id']; ?>">
                                        
                                        <input type="number" name="grade" step="0.1" min="0" max="10" 
                                               class="form-control form-control-sm" style="width: 80px;" 
                                               placeholder="0-10" 
                                               value="<?php echo $row['grade']; ?>" required>
                                        
                                        <input type="text" name="feedback" 
                                               class="form-control form-control-sm" 
                                               placeholder="Προσθέστε σχόλια..." 
                                               value="<?php echo htmlspecialchars($row['feedback'] ?? ''); ?>">
                                        
                                        <button type="submit" name="grade_submission" class="btn btn-sm btn-primary">OK</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if($result->num_rows == 0): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Δεν υπάρχουν ακόμη υποβολές για τα μαθήματά σας.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    <a href="../dashboard.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Επιστροφή στο Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>