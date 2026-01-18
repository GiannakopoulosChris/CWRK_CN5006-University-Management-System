<?php
// Διόρθωση για να μην βγαίνει το Notice στην κορυφή
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Σύνδεση με τη βάση (χρησιμοποιεί το $db από το db.php σου)
require_once '../includes/db.php'; 

if (!isset($_SESSION['user_id'])) {
    die("Πρέπει να συνδεθείτε για να δείτε αυτή τη σελίδα.");
}

// SQL Query διορθωμένο: Χρησιμοποιούμε το 'username' αντί για 'fullname'
$sql = "SELECT courses.course_name, courses.description, users.username 
        FROM courses 
        JOIN users ON courses.professor_id = users.user_id";

$result = $db->query($sql);

if (!$result) {
    die("Σφάλμα στη βάση: " . $db->error);
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Διαθέσιμα Μαθήματα</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow border-0">
            <div class="card-header bg-success text-white p-3">
                <h4 class="mb-0">Λίστα Μαθημάτων Πανεπιστημίου</h4>
            </div>
            <div class="card-body p-4">
                <p class="text-muted">Εδώ εμφανίζονται τα μαθήματα που έχουν δημιουργηθεί από τους καθηγητές.</p>
                
                <table class="table table-striped table-hover align-middle mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th>Τίτλος Μαθήματος</th>
                            <th>Περιγραφή</th>
                            <th>Διδάσκων (Username)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="fw-bold text-primary"><?php echo $row['course_name']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><span class="badge bg-info text-dark"><?php echo $row['username']; ?></span></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center">Δεν έχουν δημιουργηθεί μαθήματα ακόμα.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <div class="mt-4">
                    <a href="../dashboard.php" class="btn btn-outline-secondary">Επιστροφή στο Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>