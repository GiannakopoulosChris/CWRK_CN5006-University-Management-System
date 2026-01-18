<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once '../includes/db.php';

// Έλεγχος RBAC
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    die("Forbidden Action.");
}

$prof_id = $_SESSION['user_id'];

// Λήψη των μαθημάτων του συγκεκριμένου καθηγητή
$sql = "SELECT * FROM courses WHERE professor_id = ? ORDER BY course_name ASC";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $prof_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Τα Μαθήματά μου</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-book"></i> Τα Μαθήματά μου</h4>
                <a href="add_course.php" class="btn btn-light btn-sm">+ Νέο Μάθημα</a>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Όνομα Μαθήματος</th>
                                <th>Περιγραφή</th>
                                <th class="text-center">Ενέργειες</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['course_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td class="text-center">
                                    <a href="add_assignment.php?course_id=<?php echo $row['course_id']; ?>" 
                                       class="btn btn-sm btn-outline-success">
                                       <i class="bi bi-plus-circle"></i> Νέα Εργασία
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if($result->num_rows == 0): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Δεν έχετε δημιουργήσει ακόμη μαθήματα.</td>
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