<?php
// -------------------
// Page Metadata
// -------------------
$pageTitle = "Event Calendar";
$pageDescription = "View upcoming EpikiTours events and free virtual tours.";
$pageSlug = "tour-calendar";
$bannerImage = "images/epiki-tours-car-in-mountain.jpg";

// -------------------
// Sample Events (this could later come from a database)
// -------------------
$events = [
    "2025-09-05" => ["Virtual Safari Tour - Kenya", "Live Guide Q&A"],
    "2025-09-12" => ["Historical Landmarks Tour"],
    "2025-09-18" => ["Mountain Adventure Virtual Trek"],
    "2025-09-25" => ["Beach Escape Virtual Experience"]
];

// -------------------
// Calendar Logic
// -------------------
$month = $_GET['month'] ?? date("m");
$year = $_GET['year'] ?? date("Y");

// First day of the month
$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
$daysInMonth = date("t", $firstDayOfMonth);
$monthName = date("F", $firstDayOfMonth);
$dayOfWeek = date("N", $firstDayOfMonth); // 1 (Mon) - 7 (Sun)

// Previous & Next month
$prevMonth = $month - 1;
$prevYear = $year;
if ($prevMonth == 0) {
    $prevMonth = 12;
    $prevYear--;
}

$nextMonth = $month + 1;
$nextYear = $year;
if ($nextMonth == 13) {
    $nextMonth = 1;
    $nextYear++;
}

// -------------------
// Start output buffering
// -------------------
ob_start();
?>

<div class="container my-5">
    <h2 class="text-primary mb-4 text-center"><?= $monthName . " " . $year ?> Event Calendar</h2>

    <!-- Calendar Navigation -->
    <div class="d-flex justify-content-between mb-3">
        <a href="?month=<?= $prevMonth ?>&year=<?= $prevYear ?>" class="btn btn-outline-primary">&laquo; Previous</a>
        <a href="?month=<?= $nextMonth ?>&year=<?= $nextYear ?>" class="btn btn-outline-primary">Next &raquo;</a>
    </div>

    <!-- Calendar Table -->
    <table class="table table-bordered text-center">
        <thead class="table-light">
            <tr>
                <th>Mon</th>
                <th>Tue</th>
                <th>Wed</th>
                <th>Thu</th>
                <th>Fri</th>
                <th>Sat</th>
                <th>Sun</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php
                // Empty cells before first day
                for ($i = 1; $i < $dayOfWeek; $i++) {
                    echo "<td></td>";
                }

                // Fill days
                for ($day = 1; $day <= $daysInMonth; $day++, $dayOfWeek++) {
                    $date = "$year-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($day, 2, "0", STR_PAD_LEFT);

                    echo "<td class='p-2 align-top'>";
                    echo "<strong>$day</strong>";

                    // Show events if any
                    if (isset($events[$date])) {
                        echo "<ul class='list-unstyled mt-2'>";
                        foreach ($events[$date] as $event) {
                            echo "<li class='badge bg-primary d-block mb-1'>$event</li>";
                        }
                        echo "</ul>";
                    }

                    echo "</td>";

                    // Start new row after Sunday
                    if ($dayOfWeek % 7 == 0) {
                        echo "</tr><tr>";
                    }
                }

                // Fill remaining cells
                while ($dayOfWeek % 7 != 1) {
                    echo "<td></td>";
                    $dayOfWeek++;
                }
                ?>
            </tr>
        </tbody>
    </table>
</div>

<?php
// -------------------
// Capture page content into $pageContent and load template
// -------------------
$pageContent = ob_get_clean();
include 'layouts/page-template.php';
?>