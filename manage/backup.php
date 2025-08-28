<?php
require_once __DIR__ . '/routes.php';
require_once __DIR__ . '/../config/database.php';

$pageTitle = "System Backup";
$metaDescription = "Create, manage, and download backups of the Isokoni Marketplace platform.";
// Handle DELETE request
if (isset($_GET['delete'])) {
    $deleteFile = basename($_GET['delete']); // sanitize filename
    $backupPath = __DIR__ . '/backups/' . $deleteFile;

    if (file_exists($backupPath)) {
        unlink($backupPath);

        // Also remove from DB if you're using isk_backups table
        require_once __DIR__ . '/../config/database.php';
        $pdo->prepare("DELETE FROM isk_backups WHERE filename = ?")->execute([$deleteFile]);

        header("Location: backup.php?deleted=1");
        exit;
    } else {
        header("Location: backup.php?error=notfound");
        exit;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $timestamp = date('Ymd-His');
    $backupDir = __DIR__ . DIRECTORY_SEPARATOR . 'backups';

    if (!file_exists($backupDir)) {
        mkdir($backupDir, 0755, true);
    }

    if (!is_writable($backupDir)) {
        die("<div class='alert alert-danger'>❌ The backups directory is not writable. Please check permissions.</div>");
    }

    $backupName = "full-backup-$timestamp";
    $sqlFile = $backupDir . DIRECTORY_SEPARATOR . "$backupName.sql";
    $zipFile = $backupDir . DIRECTORY_SEPARATOR . "$backupName.zip";

    // Load DB credentials
    require_once __DIR__ . '/../config/database.php';
    $dbName = $dbname;
    $dbUser = $user;
    $dbPass = $pass;
    $dbHost = $host;

    // Escape password safely
    $escapedPass = escapeshellarg($dbPass);

    // Dump database
    $dumpCommand = "mysqldump -h$dbHost -u$dbUser -p$escapedPass $dbName > " . escapeshellarg($sqlFile);
    system($dumpCommand, $result);

    if (!file_exists($sqlFile)) {
        die("<div class='alert alert-danger'>❌ Failed to create database dump.</div>");
    }

    // Zip process
    $zip = new ZipArchive();
    if ($zip->open($zipFile, ZipArchive::CREATE) !== true) {
        unlink($sqlFile);
        die("<div class='alert alert-danger'>❌ Failed to create ZIP file. Please check file permissions.</div>");
    }

    // Add SQL dump
    $zip->addFile($sqlFile, basename($sqlFile));

    // Add all project files except backup directory
    $rootPath = realpath(__DIR__ . '/..');
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $file) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        if (
            strpos($filePath, DIRECTORY_SEPARATOR . 'backups') !== false ||
            $filePath === realpath(__FILE__)
        ) {
            continue;
        }

        if (is_file($filePath)) {
            $zip->addFile($filePath, $relativePath);
        }
    }

    if (!$zip->close()) {
        unlink($sqlFile);
        die("<div class='alert alert-danger'>❌ Failed to finalize ZIP archive.</div>");
    }

    unlink($sqlFile);

    // Only now calculate size and insert to DB
    if (file_exists($zipFile)) {
        $backupCreated = basename($zipFile);
        $sizeMB = round(filesize($zipFile) / (1024 * 1024), 2);

        // Record in DB
        $pdo->prepare("INSERT INTO isk_backups (filename, type, size_mb, path) VALUES (?, ?, ?, ?)")
            ->execute([$backupCreated, 'Full', $sizeMB, $zipFile]);
    } else {
        die("<div class='alert alert-danger'>❌ Backup ZIP not found after creation.</div>");
    }
}

ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-2 rounded mb-3">
        <li class="breadcrumb-item"><a class="text-primary" href="<?= BASE_URL ?>dashboard">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">System Backup</li>
    </ol>
</nav>

<!-- Page heading -->
<h4 class="mb-4">Backup Manager</h4>

<!-- Backup action panel -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <p class="mb-3">Create or download backups of your site and database. Always backup before updates or major
            changes.</p>

        <?php if (!empty($backupCreated)): ?>
            <div class="alert alert-success">Backup created:
                <a href="<?= BASE_URL ?>backups/<?= $backupCreated ?>">Download <?= $backupCreated ?></a>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <button type="submit" class="btn btn-primary me-2">Create New Backup</button>
        </form>
    </div>
</div>

<!-- Backup history -->
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h6 class="mb-3">Previous Backups</h6>

        <div class="table-responsive">
            <table class="table table-bordered align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Backup File</th>
                        <th>Date Created</th>
                        <th>Size</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM isk_backups ORDER BY created_at DESC");
                    $backups = $stmt->fetchAll();
                    $i = 1;
                    foreach ($backups as $backup):
                        ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($backup['filename']) ?></td>
                            <td><?= date('Y-m-d H:i', strtotime($backup['created_at'])) ?></td>
                            <td><?= $backup['size_mb'] ?> MB</td>
                            <td><?= $backup['type'] ?></td>
                            <td>
                                <a href="backups/<?= urlencode($backup['filename']) ?>"
                                    class="btn btn-sm btn-outline-secondary" download>
                                    Download
                                </a>
                                <a href="?delete=<?= urlencode($backup['filename']) ?>"
                                    class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this backup?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
include BASE_PATH . 'layouts/admin-layout.php';
?>