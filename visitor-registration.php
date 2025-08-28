<?php
session_start();
require_once 'config/database.php';
// Load Brevo (Sendinblue) SDK
require_once 'manage/vendor/autoload.php';
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
$pageTitle = "Vendor Registration";
$pageDescription = "Become a vendor on Isokoni and access thousands of online buyers. Fast registration and easy management tools.";
$pageSlug = "vendor-registration";
$bannerImage = "images/isokoni-online-shopping-card.jpg";

// Load top-level categories
$categories = [];
$stmt = $pdo->prepare("SELECT id, name FROM isk_product_categories WHERE parent_id IS NULL ORDER BY name ASC");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $firstName = htmlspecialchars(trim($_POST['first_name'] ?? ''));
    $lastName = htmlspecialchars(trim($_POST['last_name'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $nationalId = htmlspecialchars(trim($_POST['national_id'] ?? ''));
    $businessName = htmlspecialchars(trim($_POST['business_name'] ?? ''));
    $location = htmlspecialchars(trim($_POST['location'] ?? ''));
    $categoryId = (int) ($_POST['category'] ?? 0);
    $password = $_POST['password'] ?? '';
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $agreedTerms = isset($_POST['vendor_terms']);

    $errors = [];

    // Validation
    if (!$firstName)
        $errors['first_name'] = "First name is required.";
    if (!$lastName)
        $errors['last_name'] = "Last name is required.";
    if (!$email)
        $errors['email'] = "A valid email address is required.";
    if (!preg_match('/^(07|01)[0-9]{8}$/', $phone))
        $errors['phone'] = "Phone must start with 07 or 01 and be 10 digits.";
    if (!preg_match('/^\d{8,12}$/', $nationalId))
        $errors['national_id'] = "National ID must be between 8 and 12 digits.";
    if (!$businessName)
        $errors['business_name'] = "Business name is required.";
    if (!$location)
        $errors['location'] = "Business location is required.";
    if (!$categoryId)
        $errors['category'] = "Please select a business category.";
    if (strlen($password) < 6)
        $errors['password'] = "Password must be at least 6 characters.";
    if (!$agreedTerms)
        $errors['vendor_terms'] = "You must agree to the terms.";

    // File Validation
    $maxFileSize = 2 * 1024 * 1024; // 2MB
    $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];

    // National ID Upload
    if ($_FILES['id_upload']['error'] === UPLOAD_ERR_OK) {
        if ($_FILES['id_upload']['size'] > $maxFileSize || !in_array($_FILES['id_upload']['type'], $allowedTypes)) {
            $errors['id_upload'] = "National ID must be JPG, PNG, or PDF under 2MB.";
        }
    } else {
        $errors['id_upload'] = "National ID file is required.";
    }

    // Business Certificate Upload
    if ($_FILES['certificate_upload']['error'] === UPLOAD_ERR_OK) {
        if ($_FILES['certificate_upload']['size'] > $maxFileSize || !in_array($_FILES['certificate_upload']['type'], $allowedTypes)) {
            $errors['certificate_upload'] = "Certificate must be JPG, PNG, or PDF under 2MB.";
        }
    } else {
        $errors['certificate_upload'] = "Business certificate file is required.";
    }

    // Proceed only if no errors
    if (empty($errors)) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir))
            mkdir($uploadDir, 0777, true);

        $idFilename = uniqid('id_') . '_' . basename($_FILES['id_upload']['name']);
        $certFilename = uniqid('cert_') . '_' . basename($_FILES['certificate_upload']['name']);

        move_uploaded_file($_FILES['id_upload']['tmp_name'], $uploadDir . $idFilename);
        move_uploaded_file($_FILES['certificate_upload']['tmp_name'], $uploadDir . $certFilename);

        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/', '-', $businessName), '-')) . '-' . uniqid();

        try {
            $pdo->beginTransaction();

            // Check if user exists
            $existingUserStmt = $pdo->prepare("SELECT id FROM isk_users WHERE email = :email");
            $existingUserStmt->execute([':email' => $email]);
            $existingUser = $existingUserStmt->fetch(PDO::FETCH_ASSOC);

            // Check if already a vendor
            $existingVendorStmt = $pdo->prepare("SELECT id FROM isk_vendors WHERE email = :email");
            $existingVendorStmt->execute([':email' => $email]);
            $existingVendor = $existingVendorStmt->fetch(PDO::FETCH_ASSOC);

            if ($existingUser && $existingVendor) {
                $pdo->rollBack();
                $errors['form'] = "You are already registered as a vendor. Please <a href='login'>log in</a>.";
            } else {
                // If user exists, reuse ID; else create new
                if ($existingUser) {
                    $userId = $existingUser['id'];
                } else {
                    $userStmt = $pdo->prepare("
                        INSERT INTO isk_users (email, password, role_id, created_at)
                        VALUES (:email, :password, 3, NOW())
                    ");
                    $userStmt->execute([
                        ':email' => $email,
                        ':password' => $passwordHash
                    ]);
                    $userId = $pdo->lastInsertId();
                }

                // Insert vendor
                $vendorStmt = $pdo->prepare("
                    INSERT INTO isk_vendors 
                        (user_id, first_name, last_name, email, phone, national_id, business_name, slug, location, category_id, 
                        store_description, national_id_path, certificate_path, plan_id, is_active, email_verified_at)
                    VALUES 
                        (:user_id, :first_name, :last_name, :email, :phone, :national_id, :business_name, :slug, :location, :category_id, 
                        '', :national_id_path, :certificate_path, 1, 0, NOW())
                ");
                $vendorStmt->execute([
                    ':user_id' => $userId,
                    ':first_name' => $firstName,
                    ':last_name' => $lastName,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':national_id' => $nationalId,
                    ':business_name' => $businessName,
                    ':slug' => $slug,
                    ':location' => $location,
                    ':category_id' => $categoryId,
                    ':national_id_path' => $idFilename,
                    ':certificate_path' => $certFilename
                ]);
                // Fetch Brevo config
                $stmt = $pdo->query("SELECT * FROM isk_brevo_settings ORDER BY id DESC LIMIT 1");
                $brevo = $stmt->fetch();

                if ($brevo) {
                    $config = Configuration::getDefaultConfiguration()
                        ->setApiKey('api-key', $brevo['api_key']);
                    $apiInstance = new TransactionalEmailsApi(new GuzzleHttp\Client(), $config);

                    // Prepare data
                    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
                    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

                    // 1. Log system event
                    $logStmt = $pdo->prepare("
        INSERT INTO isk_system_logs (user_id, description, ip_address, user_agent)
        VALUES (:user_id, :description, :ip_address, :user_agent)
    ");
                    $logStmt->execute([
                        'user_id' => $userId,
                        'description' => 'New vendor registration: ' . $businessName,
                        'ip_address' => $ipAddress,
                        'user_agent' => $userAgent
                    ]);

                    // Notify all admins via internal system message
                    $adminStmt = $pdo->query("SELECT id FROM isk_users WHERE role_id = 1");
                    $admins = $adminStmt->fetchAll(PDO::FETCH_ASSOC);

                    $subject = "New Vendor Registration";
                    $messageText = "A new vendor <strong>{$businessName}</strong> has registered and is pending review.";
                    $link = 'manage/vendors'; // Adjust path if needed

                    $insertMessageStmt = $pdo->prepare("
    INSERT INTO isk_system_messages (sender_id, recipient_id, subject, message, related_url, is_read, created_at)
    VALUES (:sender_id, :recipient_id, :subject, :message, :related_url, 0, NOW())
");

                    foreach ($admins as $admin) {
                        $insertMessageStmt->execute([
                            'sender_id' => $userId, // or NULL if you want it system-generated
                            'recipient_id' => $admin['id'],
                            'subject' => $subject,
                            'message' => $messageText,
                            'related_url' => $link
                        ]);
                    }

                    // 2. Send email to admin
                    $adminEmail = new SendSmtpEmail([
                        'subject' => 'New Vendor Registration',
                        'sender' => ['name' => $brevo['sender_name'], 'email' => $brevo['sender_email']],
                        'to' => [['email' => 'admin@isokoni.com', 'name' => 'Admin']],
                        'htmlContent' => "
            <p>Hi Admin,</p>
            <p>A new vendor has registered on Isokoni:</p>
            <ul>
                <li><strong>Name:</strong> {$firstName} {$lastName}</li>
                <li><strong>Email:</strong> {$email}</li>
                <li><strong>Business:</strong> {$businessName}</li>
                <li><strong>Phone:</strong> {$phone}</li>
                <li><strong>Location:</strong> {$location}</li>
                <li><strong>IP:</strong> {$ipAddress}</li>
            </ul>
        "
                    ]);
                    $apiInstance->sendTransacEmail($adminEmail);

                    // 3. Send welcome email to vendor
                    $welcomeEmail = new SendSmtpEmail([
                        'subject' => 'Welcome to Isokoni!',
                        'sender' => ['name' => $brevo['sender_name'], 'email' => $brevo['sender_email']],
                        'to' => [['email' => $email, 'name' => $firstName]],
                        'htmlContent' => "
            <p>Hello {$firstName},</p>
            <p>Thank you for registering as a vendor on <strong>Isokoni</strong>.</p>
            <p>Your account is under review. You will be notified once activated.</p>
            <p>Best regards,<br>Isokoni Team</p>
        "
                    ]);
                    $apiInstance->sendTransacEmail($welcomeEmail);
                }

                $pdo->commit();
                $_SESSION['success'] = "Vendor registration successful. Awaiting account activation.";
                header("Location: login");
                exit;
            }

        } catch (Exception $e) {
            $pdo->rollBack();
            $errors['form'] = "Registration failed: " . $e->getMessage();
        }
    }
}

ob_start();
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h3 class="mb-4 text-center text-primary">Register as a Vendor on Isokoni</h3>
            <?php if (isset($errors['form'])): ?>
                <div class="alert alert-danger"><?= $errors['form'] ?></div>
            <?php endif; ?>
            <form action="" method="POST" enctype="multipart/form-data" class="shadow p-4 bg-light rounded" novalidate>

                <!-- Row 1: First Name & Last Name -->
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="first_name" class="form-label">First Name *</label>
                        <input type="text" name="first_name" id="first_name"
                            class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>" required maxlength="30"
                            pattern="^[A-Za-z\s\-']{2,30}$"
                            title="Only letters, spaces, hyphens or apostrophes allowed (2–30 characters)">
                        <?php if (isset($errors['first_name'])): ?>
                            <div class="invalid-feedback"><?= $errors['first_name'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="last_name" class="form-label">Last Name *</label>
                        <input type="text" name="last_name" id="last_name"
                            class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>" required maxlength="30"
                            pattern="^[A-Za-z\s\-']{2,30}$"
                            title="Only letters, spaces, hyphens or apostrophes allowed (2–30 characters)">
                        <?php if (isset($errors['last_name'])): ?>
                            <div class="invalid-feedback"><?= $errors['last_name'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Row 2: Email & Phone -->
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" name="email" id="email"
                            class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required maxlength="50">
                        <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback"><?= $errors['email'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="phone" class="form-label">Phone Number *</label>
                        <input type="tel" name="phone" id="phone"
                            class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" pattern="^(07|01)[0-9]{8}$"
                            maxlength="10" title="Enter a valid 10-digit phone number starting with 07 or 01" required>
                        <?php if (isset($errors['phone'])): ?>
                            <div class="invalid-feedback"><?= $errors['phone'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Row 3: National ID & Business Name -->
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="national_id" class="form-label">National ID No. *</label>
                        <input type="text" name="national_id" id="national_id"
                            class="form-control <?= isset($errors['national_id']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($_POST['national_id'] ?? '') ?>" pattern="^\d{8,12}$"
                            minlength="8" maxlength="12" title="Enter a National ID between 8 and 12 digits" required>
                        <?php if (isset($errors['national_id'])): ?>
                            <div class="invalid-feedback"><?= $errors['national_id'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="business_name" class="form-label">Business Name *</label>
                        <input type="text" name="business_name" id="business_name"
                            class="form-control <?= isset($errors['business_name']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($_POST['business_name'] ?? '') ?>" maxlength="50"
                            title="Business name should be 3 to 50 characters" required>
                        <?php if (isset($errors['business_name'])): ?>
                            <div class="invalid-feedback"><?= $errors['business_name'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Row 4: Business Location & Category -->
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="location" class="form-label">Business Location *</label>
                        <input type="text" name="location" id="location"
                            class="form-control <?= isset($errors['location']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($_POST['location'] ?? '') ?>" maxlength="50"
                            placeholder="E.g. Biashara Street, Nairobi" required>
                        <?php if (isset($errors['location'])): ?>
                            <div class="invalid-feedback"><?= $errors['location'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="category" class="form-label">Main Category *</label>
                        <select name="category" id="category"
                            class="form-select <?= isset($errors['category']) ? 'is-invalid' : '' ?>" required>
                            <option value="">-- Select Category --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat['id']) ?>" <?= (isset($_POST['category']) && $_POST['category'] == $cat['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['category'])): ?>
                            <div class="invalid-feedback"><?= $errors['category'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Row 5: ID & Certificate Uploads -->
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="id_upload" class="form-label">Upload National ID *</label>
                        <input type="file" name="id_upload" id="id_upload"
                            class="form-control <?= isset($errors['id_upload']) ? 'is-invalid' : '' ?>"
                            accept=".jpg,.jpeg,.png,.pdf" required>
                        <?php if (isset($errors['id_upload'])): ?>
                            <div class="invalid-feedback"><?= $errors['id_upload'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="certificate_upload" class="form-label">Business Incorporation Certificate *</label>
                        <input type="file" name="certificate_upload" id="certificate_upload"
                            class="form-control <?= isset($errors['certificate_upload']) ? 'is-invalid' : '' ?>"
                            accept=".pdf,.jpg,.jpeg,.png" required>
                        <?php if (isset($errors['certificate_upload'])): ?>
                            <div class="invalid-feedback"><?= $errors['certificate_upload'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Create Password *</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password"
                            class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" maxlength="30"
                            minlength="6" title="Password should be 6–30 characters" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">Show</button>
                    </div>
                    <?php if (isset($errors['password'])): ?>
                        <div class="invalid-feedback d-block"><?= $errors['password'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Terms Agreement -->
                <div class="mb-3 form-check">
                    <input type="checkbox"
                        class="form-check-input <?= isset($errors['vendor_terms']) ? 'is-invalid' : '' ?>"
                        id="vendor_terms" name="vendor_terms" <?= isset($_POST['vendor_terms']) ? 'checked' : '' ?>
                        required>
                    <label class="form-check-label" for="vendor_terms">
                        I agree to the <a href="merchant-policy">Merchant Policy</a> and
                        <a href="terms-and-conditions">Terms & Conditions</a>
                    </label>
                    <?php if (isset($errors['vendor_terms'])): ?>
                        <div class="invalid-feedback d-block"><?= $errors['vendor_terms'] ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary w-100">Register Vendor Account</button>
            </form>

        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById("password");
        const toggleButton = event.currentTarget;
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleButton.textContent = "Hide";
        } else {
            passwordInput.type = "password";
            toggleButton.textContent = "Show";
        }
    }
</script>


<?php
$pageContent = ob_get_clean();
include 'layouts/page-template.php';
?>