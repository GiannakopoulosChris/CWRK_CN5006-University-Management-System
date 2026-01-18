<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once '../includes/db.php'; 

if (!isset($_SESSION['user_id'])) { die("Πρέπει να συνδεθείτε."); }
$user_id = $_SESSION['user_id'];

// SQL: Παίρνουμε εργασίες ΚΑΙ την υποβολή του συγκεκριμένου φοιτητή (αν υπάρχει)
$sql = "SELECT a.*, c.course_name, s.grade, s.feedback, s.submission_id 
        FROM assignments a
        JOIN courses c ON a.course_id = c.course_id
        LEFT JOIN submissions s ON a.assignment_id = s.assignment_id AND s.student_id = ?
        ORDER BY a.deadline ASC";

$stmt = $db->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Οι Εργασίες μου</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow border-0">
            <div class="card-header bg-dark text-white p-3">
                <h4 class="mb-0"><i class="bi bi-journal-check"></i> Κατάσταση Εργασιών</h4>
            </div>
            <div class="card-body p-4">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Μάθημα</th>
                            <th>Εργασία</th>
                            <th>Βαθμολογία & Σχόλια</th>
                            <th>Ενέργεια</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><span class="badge bg-primary"><?php echo $row['course_name']; ?></span></td>
                            <td>
                                <strong><?php echo $row['title']; ?></strong><br>
                                <small class="text-muted">Deadline: <?php echo date('d/m/Y', strtotime($row['deadline'])); ?></small>
                            </td>
                            <td>
                                <?php if ($row['grade'] !== null): ?>
                                    <div class="alert alert-info mb-0 p-2">
                                        <strong>Βαθμός: <?php echo $row['grade']; ?> / 10</strong><br>
                                        <small><i class="bi bi-chat-dots"></i> Σχόλια: <?php echo $row['feedback'] ?: 'Κανένα σχόλιο.'; ?></small>
                                    </div>
                                <?php elseif ($row['submission_id']): ?>
                                    <span class="badge bg-warning text-dark">Εκκρεμεί Βαθμολόγηση</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Δεν υποβλήθηκε</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$row['submission_id']): ?>
                                    <a href="submit_assignment.php?id=<?php echo $row['assignment_id']; ?>" class="btn btn-sm btn-success">
                                        <i class="bi bi-upload"></i> Υποβολή
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline-secondary" disabled>Υποβλήθηκε</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <a href="../dashboard.php" class="btn btn-outline-secondary mt-3">Πίσω στο Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>