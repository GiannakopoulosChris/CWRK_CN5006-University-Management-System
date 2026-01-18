<?php
session_start();
require_once '../includes/db.php'; // Σύνδεση με τη βάση

// 1. ΕΛΕΓΧΟΣ RBAC (Role-Based Access Control) - Μόνο Καθηγητές (Role 2)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    die("Forbidden Action: Δεν έχετε δικαίωμα πρόσβασης σε αυτή τη σελίδα.");
}

$message = "";

// 2. ΛΟΓΙΚΗ ΑΠΟΘΗΚΕΥΣΗΣ ΜΑΘΗΜΑΤΟΣ
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $c_name = $_POST['course_name'];
    $c_desc = $_POST['description'];
    $prof_id = $_SESSION['user_id'];

    $sql = "INSERT INTO courses (course_name, description, professor_id) VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql); // Χρήση του $db από το db.php σου
    
    // Σύνδεση των παραμέτρων (s = string, i = integer)
    $stmt->bind_param("ssi", $c_name, $c_desc, $prof_id);
    
    if ($stmt->execute()) {
        $message = "<div class='alert alert-success shadow-sm'>Το μάθημα δημιουργήθηκε με επιτυχία!</div>";
    } else {
        $message = "<div class='alert alert-danger shadow-sm'>Σφάλμα: " . $db->error . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Δημιουργία Μαθήματος</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3>Προσθήκη Νέου Μαθήματος</h3>
            </div>
            <div class="card-body">
                <?php echo $message; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Όνομα Μαθήματος</label>
                        <input type="text" name="course_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Περιγραφή</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Δημιουργία</button>
                    <a href="../dashboard.php" class="btn btn-secondary">Επιστροφή</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>