<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Add New Tour";
$metaDescription = "Add a new tour to EpikiTours admin panel.";

$errors = [];
$success = "";

// -------------------
// Helper: Generate Slug
// -------------------
function generateSlug($pdo, $title)
{
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
    $baseSlug = $slug;
    $counter = 1;
    while (true) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM epi_tours WHERE slug = ?");
        $stmt->execute([$slug]);
        if ($stmt->fetchColumn() == 0) {
            break;
        }
        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }
    return $slug;
}

// -------------------
// Handle Form Submit
// -------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $start_date = $_POST['start_date'] ?? '';
    $start_time = $_POST['start_time'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $youtube_link = trim($_POST['youtube_link'] ?? '');
    $marzipano_path = trim($_POST['marzipano_path'] ?? '');
    $jitsi_link = trim($_POST['jitsi_link'] ?? '');
    $status = $_POST['status'] ?? 'upcoming';

    $preview_thumbnail = null;

    // -------------------
    // Handle Thumbnail Upload
    // -------------------
    if (isset($_FILES['preview_thumbnail']) && $_FILES['preview_thumbnail']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($_FILES['preview_thumbnail']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('thumb_') . '.' . strtolower($ext);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['preview_thumbnail']['tmp_name'], $filePath)) {
            $preview_thumbnail = 'uploads/' . $fileName;
        } else {
            $errors[] = "Failed to upload thumbnail.";
        }
    }

    // -------------------
    // Validation
    // -------------------
    if ($title === '')
        $errors[] = "Title is required.";
    if ($start_date === '')
        $errors[] = "Start date is required.";
    if ($start_time === '')
        $errors[] = "Start time is required.";
    if ($end_date === '')
        $errors[] = "End date is required.";
    if ($end_time === '')
        $errors[] = "End time is required.";

    // -------------------
    // Insert Tour
    // -------------------
    if (empty($errors)) {
        try {
            $slug = generateSlug($pdo, $title);

            $stmt = $pdo->prepare("
                INSERT INTO epi_tours 
                    (title, slug, description, start_date, start_time, end_date, end_time, location, youtube_link, marzipano_path, jitsi_link, preview_thumbnail, status, created_by) 
                VALUES 
                    (:title, :slug, :description, :start_date, :start_time, :end_date, :end_time, :location, :youtube_link, :marzipano_path, :jitsi_link, :preview_thumbnail, :status, :created_by)
            ");

            $stmt->execute([
                ':title' => $title,
                ':slug' => $slug,
                ':description' => $description,
                ':start_date' => $start_date,
                ':start_time' => $start_time,
                ':end_date' => $end_date,
                ':end_time' => $end_time,
                ':location' => $location,
                ':youtube_link' => $youtube_link,
                ':marzipano_path' => $marzipano_path,
                ':jitsi_link' => $jitsi_link,
                ':preview_thumbnail' => $preview_thumbnail,
                ':status' => $status,
                ':created_by' => 1 // TODO: replace with logged-in admin user ID
            ]);

            $success = "Tour added successfully!";
        } catch (PDOException $e) {
            $errors[] = "Error saving tour: " . $e->getMessage();
        }
    }
}

ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-2 rounded mb-3">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Admin</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>tours">Tours</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Tour</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Add New Tour</h4>
</div>

<!-- Alerts -->
<?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<!-- Add Tour Form -->
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="post" enctype="multipart/form-data">
            <!-- Title -->
            <div class="mb-3">
                <label for="title" class="form-label">Tour Title *</label>
                <input type="text" name="title" id="title" class="form-control" required>
                <small class="form-text text-muted">Enter a clear and descriptive tour title (used to generate the
                    slug).</small>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4"></textarea>
                <small class="form-text text-muted">Provide a detailed description of the tour (optional).</small>
            </div>

            <!-- Location -->
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" id="location" class="form-control">
                <small class="form-text text-muted">Specify the destination or meeting point of the tour.</small>
            </div>

            <!-- Dates & Times -->
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="start_date" class="form-label">Start Date *</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" required>
                    <small class="form-text text-muted">Select the date when the tour begins.</small>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="start_time" class="form-label">Start Time *</label>
                    <input type="time" name="start_time" id="start_time" class="form-control" required>
                    <small class="form-text text-muted">Specify the time when the tour begins.</small>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="end_date" class="form-label">End Date *</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" required>
                    <small class="form-text text-muted">Select the date when the tour ends.</small>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="end_time" class="form-label">End Time *</label>
                    <input type="time" name="end_time" id="end_time" class="form-control" required>
                    <small class="form-text text-muted">Specify the time when the tour ends.</small>
                </div>
            </div>

            <!-- Thumbnail -->
            <div class="mb-3">
                <label for="preview_thumbnail" class="form-label">Preview Thumbnail</label>
                <input type="file" name="preview_thumbnail" id="preview_thumbnail" class="form-control"
                    accept="image/*">
                <small class="form-text text-muted">Upload a thumbnail image to represent the tour (JPEG, PNG,
                    etc.).</small>
            </div>

            <!-- Extra Links -->
            <div class="mb-3">
                <label for="youtube_link" class="form-label">YouTube Link</label>
                <input type="url" name="youtube_link" id="youtube_link" class="form-control">
                <small class="form-text text-muted">Optional: Add a YouTube link showcasing the tour.</small>
            </div>
            <div class="mb-3">
                <label for="marzipano_path" class="form-label">Marzipano Path</label>
                <input type="text" name="marzipano_path" id="marzipano_path" class="form-control">
                <small class="form-text text-muted">Optional: Path to a Marzipano virtual tour (360° view).</small>
            </div>
            <div class="mb-3">
                <label for="jitsi_link" class="form-label">Jitsi Link</label>
                <input type="url" name="jitsi_link" id="jitsi_link" class="form-control">
                <small class="form-text text-muted">Optional: Jitsi meeting link for live interaction.</small>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="upcoming">Upcoming</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="completed">Completed</option>
                    <option value="canceled">Canceled</option>
                </select>
                <small class="form-text text-muted">Set the current status of the tour.</small>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Save Tour
            </button>
            <a href="<?= BASE_URL ?>tours" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
include BASE_PATH . 'layouts/admin-layout.php';
