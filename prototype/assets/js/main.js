/**
 * @file main.js
 * @description Κύριο αρχείο JavaScript που διαχειρίζεται τη φόρτωση του χάρτη Leaflet.
 */

// Έλεγχος για το αν βρισκόμαστε στη σελίδα που περιέχει το div 'map'
document.addEventListener("DOMContentLoaded", function() {
    
    const mapDiv = document.getElementById('map');
    
    // Φόρτωση χάρτη μόνο αν υπάρχει το div 'map'
    if (mapDiv) {
        
        // Συντεταγμένες για Μητροπολιτικό Κολλέγιο (Campus Αμαρουσίου, Σωρού 74)
        // (Προσέγγιση από Google Maps)
        const lat = 38.0423;
        const lon = 23.8050;
        const zoomLevel = 16;

        // 1. Αρχικοποίηση χάρτη
        // 'L' είναι το global αντικείμενο από τη βιβλιοθήκη Leaflet
        const map = L.map('map').setView([lat, lon], zoomLevel);

        // 2. Φόρτωση των 'tiles' (τα γραφικά του χάρτη)
        // Χρησιμοποιούμε το OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // 3. Προσθήκη Marker (πινέζα)
        const marker = L.marker([lat, lon]).addTo(map);
        
        // 4. Προσθήκη Popup στο marker
        marker.bindPopup("<b>Μητροπολιτικό Κολλέγιο</b><br>Campus Αμαρουσίου<br>Σωρού 74").openPopup();
    }

});