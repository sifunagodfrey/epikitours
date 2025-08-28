<?php
// -------------------
// Page variables
// -------------------
$pageTitle = "My Calendar";
$pageDescription = "View your booked tours and events on EpikiTours.";
$pageSlug = "visitors/my-calendar";

// -------------------
// Start session
// -------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// -------------------
// Protect page (must be logged in)
// -------------------
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

// -------------------
// Database connection
// -------------------
require_once __DIR__ . '/../config/database.php';

// -------------------
// Fetch user bookings for calendar
// -------------------
$userId = $_SESSION['user_id'];
$bookings = [];

try {
    $stmt = $pdo->prepare("SELECT id, tour_name, start_date, end_date FROM epi_bookings WHERE user_id = :uid");
    $stmt->execute(['uid' => $userId]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Calendar Fetch Error: " . $e->getMessage());
}

// Convert bookings to FullCalendar JSON format
$calendarEvents = [];
foreach ($bookings as $b) {
    $calendarEvents[] = [
        'title' => $b['tour_name'],
        'start' => $b['start_date'],
        'end' => $b['end_date']
    ];
}

// -------------------
// Start output buffering
// -------------------
ob_start();
?>

<div class="text-center mb-5">
    <h4 class="mb-3">My Calendar</h4>
    <p class="text-muted">View your upcoming and past tours at a glance.</p>
</div>

<div id="calendar"></div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: <?= json_encode($calendarEvents); ?>,
            eventColor: '#0d6efd',
            height: 650
        });
        calendar.render();
    });
</script>

<?php
$pageContent = ob_get_clean();
include 'layouts/visitors-dashboard-layout.php';
?>