<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once '../includes/db.php';

// 1. Έλεγχος RBAC για Φοιτητή (Role 1)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    die("Forbidden Action");
}

$student_id = $_SESSION['user_id'];

// 2. Λήψη όλων των εργασιών και των μαθημάτων τους
$sql = "SELECT a.*, c.course_name 
        FROM assignments a
        JOIN courses c ON a.course_id = c.course_id
        ORDER BY a.deadline ASC";
$result = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Διαθέσιμες Εργασίες</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow border-0">
            <div class="card-header bg-info text-white p-3">
                <h4 class="mb-0"><i class="bi bi-journal-text"></i> Λίστα Εργασιών προς Υποβολή</h4>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Μάθημα</th>
                                <th>Εργασία</th>
                                <th>Προθεσμία</th>
                                <th class="text-center">Ενέργεια</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): 
                                    // Έλεγχος αν έχει ήδη υποβληθεί η εργασία από τον φοιτητή
                                    $check_sql = "SELECT submission_id FROM submissions WHERE student_id = ? AND assignment_id = ?";
                                    $stmt_check = $db->prepare($check_sql);
                                    $stmt_check->bind_param("ii", $student_id, $row['assignment_id']);
                                    $stmt_check->execute();
                                    $has_submitted = $stmt_check->get_result()->num_rows > 0;
                                ?>
                                <tr>
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($row['course_name']); ?></span></td>
                                    <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                                    <td><i class="bi bi-clock"></i> <?php echo date('d/m/Y H:i', strtotime($row['deadline'])); ?></td>
                                    <td class="text-center">
                                        <?php if ($has_submitted): ?>
                                            <span class="badge bg-success p-2 w-100">
                                                <i class="bi bi-check-circle-fill"></i> Υποβλήθηκε
                                            </span>
                                        <?php else: ?>
                                            <a href="submit_assignment.php?id=<?php echo $row['assignment_id']; ?>" 
                                               class="btn btn-sm btn-outline-success w-100 shadow-sm">
                                                Υποβολή Εργασίας
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted p-4">Δεν υπάρχουν διαθέσιμες εργασίες.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <a href="../dashboard.php" class="btn btn-secondary shadow-sm">
                        <i class="bi bi-arrow-left"></i> Πίσω στο Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>