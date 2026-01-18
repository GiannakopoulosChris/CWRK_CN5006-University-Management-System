<?php
// 1. Διόρθωση για το session notice
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 2. Σύνδεση με τη βάση ($db από το db.php σου)
require_once '../includes/db.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    die("Πρόσβαση μόνο για καθηγητές.");
}

$prof_id = $_SESSION['user_id'];
$message = "";

// 3. Λήψη μαθημάτων για το dropdown
$courses_sql = "SELECT course_id, course_name FROM courses WHERE professor_id = ?";
$stmt_c = $db->prepare($courses_sql);
$stmt_c->bind_param("i", $prof_id);
$stmt_c->execute();
$courses_result = $stmt_c->get_result();

// 4. ΛΟΓΙΚΗ ΑΠΟΘΗΚΕΥΣΗΣ
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $c_id = $_POST['course_id'];
    $title = $_POST['title'];
    $instr = $_POST['instructions']; // Αντιστοιχεί στη στήλη instructions
    $deadline = $_POST['deadline'];

    // SQL INSERT με τη σωστή στήλη 'instructions'
    $sql = "INSERT INTO assignments (course_id, title, instructions, deadline) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("isss", $c_id, $title, $instr, $deadline);
    
    if ($stmt->execute()) {
        $message = "<div class='alert alert-success shadow-sm'>Η εργασία δημιουργήθηκε επιτυχώς!</div>";
    } else {
        $message = "<div class='alert alert-danger shadow-sm'>Σφάλμα: " . $db->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Νέα Εργασία</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white p-3">
                <h4 class="mb-0">Ανάθεση Νέας Εργασίας</h4>
            </div>
            <div class="card-body p-4">
                <?php echo $message; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Επιλογή Μαθήματος</label>
                        <select name="course_id" class="form-select" required>
                            <?php while($row = $courses_result->fetch_assoc()): ?>
                                <option value="<?php echo $row['course_id']; ?>"><?php echo $row['course_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Τίτλος Εργασίας</label>
                        <input type="text" name="title" class="form-control" placeholder="π.χ. Άσκηση 1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Περιγραφή / Οδηγίες</label>
                        <textarea name="instructions" class="form-control" rows="4" placeholder="Γράψτε εδώ τις οδηγίες της εργασίας..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ημερομηνία Παράδοσης (Deadline)</label>
                        <input type="datetime-local" name="deadline" class="form-control" required>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Δημιουργία Εργασίας</button>
                        <a href="../dashboard.php" class="btn btn-link text-muted">Επιστροφή στο Dashboard</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>