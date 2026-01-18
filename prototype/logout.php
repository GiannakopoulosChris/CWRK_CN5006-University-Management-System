<?php
/**
 * @desc Τερματίζει την ενεργή PHP session του χρήστη, διαγράφει τα δεδομένα και ανακατευθύνει στην αρχική σελίδα.
 */
session_start();

// Καταστροφή όλων των μεταβλητών session
$_SESSION = array();

// Καταστροφή του session
session_destroy();

// Ανακατεύθυνση στην αρχική σελίδα
header("location: index.php");
exit;
?>