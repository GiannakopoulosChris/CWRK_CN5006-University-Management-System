<?php
// Έναρξη session για να ελέγξουμε αν ο χρήστης είναι συνδεδεμένος
session_start();
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Αρχική Σελίδα - Μητροπολιτικό Κολλέγιο</title>
    <link rel="stylesheet" href="css/style.css">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
</head>
<body>

    <header class="main-header">
        <div class="container">
            <a href="index.php" class="logo-link">
                <img src="pictures/logo.png" alt="Λογότυπο Μητροπολιτικό Κολλέγιο" class="logo">
            </a>
            <nav class="main-nav">
                <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                    <span>Καλώς ήρθες, <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>!</span>
                    <a href="dashboard.php" class="btn">Πίνακας Ελέγχου</a>
                    <a href="logout.php" class="btn btn-secondary">Αποσύνδεση</a>
                <?php else: ?>
                    <a href="login.php" class="btn">Σύνδεση</a>
                    <a href="register.php" class="btn btn-secondary">Εγγραφή</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container">
                <h1>Καλωσήρθατε στο Μητροπολιτικό Κολλέγιο</h1>
                <p>Το μεγαλύτερο Κολλέγιο Πανεπιστημιακών Σπουδών στην Ελλάδα.</p>
            </div>
        </section>

        <section class="container content-section">
            <h2>Το Campus μας</h2>
            <p>Με υπερσύγχρονα Campuses σε 8 σημεία της χώρας, προσφέρουμε ένα ιδανικό εκπαιδευτικό περιβάλλον. Οι εγκαταστάσεις μας, όπως το campus στο Μαρούσι (Σωρού 74), περιλαμβάνουν σύγχρονα εργαστήρια, βιβλιοθήκες και χώρους αναψυχής, σχεδιασμένα να υποστηρίζουν τη γνώση και την έρευνα.</p>
            
            <img src="pictures/campus.jpg" alt="Εικόνα Campus Αμαρουσίου" class="responsive-img">

            <h2>Βρείτε μας στο Χάρτη</h2>
            <p>Επισκεφθείτε το κεντρικό μας campus στο Μαρούσι, Σωρού 74.</p>
            
            <div id="map"></div>
        </section>
    </main>

    <footer class="main-footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Μητροπολιτικό Κολλέγιο. Όλα τα δικαιώματα κατοχυρωμένα.</p>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>