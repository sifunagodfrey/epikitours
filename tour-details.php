<?php
// ------------------------------
// Load core config & helpers
// ------------------------------
require_once 'config/database.php';
require_once 'routes.php';
require_once __DIR__ . '/helpers/currency_converter.php';

// ------------------------------
// Handle currency selection
// ------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['currency'])) {
    // Set selected currency from dropdown
    $_SESSION['currency'] = $_POST['currency'];
}

// Set default currency if not already set in session
if (!isset($_SESSION['currency'])) {
    $_SESSION['currency'] = 'KES';
}

// ------------------------------
// Get product by slug from URL
// ------------------------------
$slug = $_GET['slug'] ?? null;

if (!$slug) {
    // No slug provided - show 404 page
    http_response_code(404);
    $pageTitle = "Product Not Found";
    $pageDescription = "The product you're looking for could not be found.";
    $product = ['name' => 'Not Found', 'short_description' => '', 'thumbnail' => 'images/not-found.jpg'];

    ob_start();
    echo "<div class='container my-5'><h3>Product not found</h3><p>We couldn't locate the product you're looking for.</p></div>";
    $pageContent = ob_get_clean();
    include 'layouts/single-product-template.php';
    exit;
}

try {
    // ------------------------------
    // Fetch product with category & brand info
    // ------------------------------
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.parent_id, b.name AS brand_name
        FROM isk_products p
        LEFT JOIN isk_product_categories c ON p.category_id = c.id
        LEFT JOIN isk_brands b ON p.brand_id = b.id
        WHERE p.slug = ? AND p.is_active = 1
        LIMIT 1
    ");
    $stmt->execute([$slug]);
    $product = $stmt->fetch();

    if (!$product) {
        // Product not found or inactive - show 404
        http_response_code(404);
        $pageTitle = "Product Not Found";
        $pageDescription = "The product you're looking for could not be found.";

        ob_start();
        echo "<div class='container my-5'><h3>Product not found</h3><p>We couldn't locate the product you're looking for.</p></div>";
        $pageContent = ob_get_clean();
        include 'layouts/single-product-template.php';
        exit;
    }

    // ------------------------------
    // Fetch bulk pricing tiers (if enabled)
    // ------------------------------
    $bulkTiers = [];
    if ($product['is_bulk_enabled']) {
        $stmt = $pdo->prepare("
            SELECT min_quantity, max_quantity, price_per_unit 
            FROM isk_product_bulk_pricing 
            WHERE product_id = ? 
            ORDER BY min_quantity ASC
        ");
        $stmt->execute([$product['id']]);
        $rawTiers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $bulkTiers = [];

        foreach ($rawTiers as $tier) {
            // Convert from KES to session currency
            $converted = convertCurrencyRaw($tier['price_per_unit'], $_SESSION['currency']); // Assumes conversion is FROM KES
            $tier['price_per_unit_converted'] = $converted;
            $bulkTiers[] = $tier;
        }

    }

    // ------------------------------
    // Set metadata for SEO
    // ------------------------------
    $pageTitle = $product['name'];
    $pageDescription = $product['short_description'] ?: substr(strip_tags($product['detailed_description']), 0, 160);

    // Use placeholder if product thumbnail is missing
    $product['thumbnail'] = $product['thumbnail'] ?: 'product-placeholder.jpg';

    // ------------------------------
    // Fetch parent category for breadcrumb
    // ------------------------------
    $parentCategory = null;
    if ($product['parent_id']) {
        $parentStmt = $pdo->prepare("SELECT name, slug FROM isk_product_categories WHERE id = ?");
        $parentStmt->execute([$product['parent_id']]);
        $parentCategory = $parentStmt->fetch();
    }

    // ------------------------------
    // Fetch average rating and review count
    // ------------------------------
    $ratingStmt = $pdo->prepare("
        SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews 
        FROM isk_product_reviews 
        WHERE product_id = ? AND status = 'approved'
    ");
    $ratingStmt->execute([$product['id']]);
    $ratingData = $ratingStmt->fetch();

    // ------------------------------
    // Fetch product attributes
    // ------------------------------
    $attrStmt = $pdo->prepare("
        SELECT a.id AS attribute_id, a.name AS attribute_name
        FROM isk_product_attributes a
        WHERE a.product_id = ?
    ");
    $attrStmt->execute([$product['id']]);
    $attributes = $attrStmt->fetchAll(PDO::FETCH_ASSOC);

    // ------------------------------
    // For each attribute, fetch its values
    // ------------------------------
    $attributeOptions = [];
    foreach ($attributes as $attribute) {
        $valueStmt = $pdo->prepare("
            SELECT v.id AS value_id, v.value
            FROM isk_product_attribute_values v
            WHERE v.product_id = ? AND v.attribute_id = ?
        ");
        $valueStmt->execute([$product['id'], $attribute['attribute_id']]);
        $attributeOptions[$attribute['attribute_name']] = $valueStmt->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    // If any DB error occurs, show user-friendly error
    http_response_code(500);
    $pageTitle = "Error";
    $pageDescription = "An error occurred while loading the product.";

    ob_start();
    echo "<div class='container my-5'><h3>Error loading product</h3><p>" . htmlspecialchars($e->getMessage()) . "</p></div>";
    $pageContent = ob_get_clean();
    include 'layouts/single-product-template.php';
    exit;
}

// Start buffering HTML content for this product's page
ob_start();
?>
<nav aria-label="breadcrumb" class="my-4">
    <ol class="breadcrumb mb-0 breadcrumb-ellipsis">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-primary">Home</a></li>
        <?php if ($parentCategory): ?>
            <li class="breadcrumb-item text-primary">
                <a href="<?= BASE_URL . 'category/' . $parentCategory['slug'] ?>">
                    <?= $parentCategory['name'] ?>
                </a>
            </li>
        <?php endif; ?>
        <li class="breadcrumb-item">
            <a class="text-primary" href="<?= BASE_URL . 'category/' . $product['category_slug'] ?>">
                <?= $product['category_name'] ?>
            </a>
        </li>
        <!-- Truncate only product name -->
        <li class="breadcrumb-item active breadcrumb-truncate" aria-current="page"><?= $product['name'] ?></li>
    </ol>
</nav>


<!-- ==================== ROW 1 ==================== -->
<div class="row g-4">
    <!-- ==================== COLUMN 1: Product Image ==================== -->
    <div class="col-md-4">
        <div class="card shadow-sm h-100 position-relative">
            <?php if (!empty($product['is_bulk_enabled'])): ?>
                <div
                    class="badge-wholesale position-absolute top-0 start-0 bg-primary text-white px-2 py-1 fw-bold rounded-end">
                    Wholesale
                </div>
            <?php endif; ?>

            <!-- Main product image -->
            <img id="mainImage-<?= $product['id'] ?>"
                src="uploads/<?= htmlspecialchars($product['thumbnail'] ?: 'product-placeholder.jpg') ?>"
                class="card-img-top main-image" alt="<?= htmlspecialchars($product['name']) ?>">

            <div class="card-body">
                <?php
                // Fetch product gallery images
                $stmt = $pdo->prepare("SELECT file_path FROM isk_media WHERE product_id = ? AND type = 'image'");
                $stmt->execute([$product['id']]);
                $galleryImages = $stmt->fetchAll(PDO::FETCH_COLUMN);
                ?>

                <!-- Product Gallery Thumbnails -->
                <?php if (!empty($galleryImages)): ?>
                    <div class="gallery-scroll d-flex overflow-auto gap-2 mb-3">
                        <?php foreach ($galleryImages as $img): ?>
                            <img src="uploads/<?= htmlspecialchars(basename($img)) ?>" alt="Gallery Image"
                                class="rounded border gallery-thumb"
                                style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                                onclick="updateMainImage('mainImage-<?= $product['id'] ?>', 'uploads/<?= htmlspecialchars(basename($img)) ?>')">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Share and Wishlist Buttons -->
                <div class="d-flex gap-2">
                    <button id="shareProductBtn" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-share-alt"></i> Share
                    </button>

                    <button id="wishlistBtn" class="btn btn-outline-primary btn-sm w-100"
                        data-product-id="<?= $product['id'] ?>">
                        <i class="fas fa-plus"></i> Wishlist
                    </button>
                </div>

                <!-- Hidden input for clipboard functionality -->
                <input type="text" id="productShareUrl" value="<?= BASE_URL . htmlspecialchars($product['slug']) ?>"
                    hidden>
            </div>
        </div>
    </div>

    <!-- JS: Switch main image on thumbnail click -->
    <script>
        function updateMainImage(imageId, newSrc) {
            const mainImage = document.getElementById(imageId);
            if (mainImage) mainImage.src = newSrc;
        }
    </script>


    <!-- ==================== COLUMN 2: Product Details ==================== -->
    <div class="col-md-8">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <!-- Product title -->
                <h4 class="text-dark fw-normal fs-4"><?= htmlspecialchars($product['name']) ?></h4>
                <hr>
                <p><?= htmlspecialchars($product['short_description']) ?></p>

                <!-- Basic Product Meta -->
                <ul class="list-unstyled d-flex flex-wrap gap-3 mb-2">
                    <li><strong>In:</strong> <?= htmlspecialchars($product['category_name']) ?></li>
                    <?php if (!empty($product['brand_name'])): ?>
                        <li><strong>Brand:</strong> <?= htmlspecialchars($product['brand_name']) ?></li>
                    <?php endif; ?>
                    <li><strong>In Stock</strong></li>
                </ul>

                <!-- Bulk Pricing Section -->
                <?php if (!empty($bulkTiers)): ?>
                    <div id="bulkAlert" class="card-body alert alert-info border-info p-2 mb-3 w-100">
                        <strong>Bulk Pricing Available!</strong><br>
                        <ul class="mb-0 ps-3">
                            <?php foreach ($bulkTiers as $tier): ?>
                                <li>
                                    Buy
                                    <?= $tier['min_quantity'] ?>
                                    <?= $tier['max_quantity'] ? ' - ' . $tier['max_quantity'] : '+' ?>
                                    @ <?= formatCurrency($tier['price_per_unit'], $_SESSION['currency']) ?> each
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Attribute Options (e.g. Size, Color) -->
                <?php if (!empty($attributeOptions)): ?>
                    <div class="mb-3">
                        <strong class='text-primary'>Select Options:</strong>
                        <?php foreach ($attributeOptions as $attrName => $values): ?>
                            <div class="mt-2">
                                <label class="form-label"><?= htmlspecialchars($attrName) ?></label>
                                <select class="form-select variant-selector"
                                    data-attribute="<?= htmlspecialchars($attrName) ?>">
                                    <option value="">-- Choose <?= htmlspecialchars($attrName) ?> --</option>
                                    <?php foreach ($values as $val): ?>
                                        <option value="<?= $val['value_id'] ?>"><?= htmlspecialchars($val['value']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Display price if not a bulk item -->
                <?php if (!$product['is_variant'] && !$product['is_bulk_enabled']): ?>
                    <p class="text-primary mb-2 fw-bold fs-4" id="variantPriceDisplay">
                        <?= formatCurrency($product['price'], $_SESSION['currency']) ?>
                    </p>
                <?php endif; ?>

                <!-- Stock Display (JS updated) -->
                <p class="text-muted mb-3" id="variantStockDisplay"></p>
                <input type="hidden" id="selectedVariantId" name="variant_id" value="">

                <?php
                // Setup cart quantity
                $cart = $_SESSION['cart'] ?? [];
                $productId = $product['id'];
                $inCart = isset($cart[$productId]);
                $cartQty = $inCart ? $cart[$productId]['quantity'] : 1;

                // Enforce minimum quantity if bulk
                $minQty = !empty($bulkTiers) ? $bulkTiers[0]['min_quantity'] : 1;
                ?>

                <?php if (!empty($bulkTiers)): ?>
                    <small class="text-muted">
                        Minimum order: <?= $bulkTiers[0]['min_quantity'] ?> units
                    </small>
                <?php endif; ?>

                <!-- Quantity Controls -->
                <div class="input-group w-100 mb-3">
                    <button class="btn btn-outline-primary quantity-minus" type="button">-</button>
                    <input type="number" class="form-control text-center bg-light" id="quantityInput"
                        value="<?= max($cartQty, $minQty) ?>" min="<?= $minQty ?>" step="1"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <button class="btn btn-outline-primary quantity-plus" type="button">+</button>
                </div>

                <!-- Add to Cart / Checkout Toast Message -->
                <div class="toast-container mt-3" style="position: relative;">
                    <div id="cartToast" class="toast w-100 text-white bg-secondary border-0" role="alert"
                        aria-live="assertive" aria-atomic="true">
                        <div class="d-flex align-items-center justify-content-between px-3 py-2 w-100">
                            <div class="d-flex justify-content-between align-items-center w-100 gap-3">
                                <span id="cartToastMessage" class="flex-grow-1">Product added to cart.</span>
                                <a href="shopping-cart" class="btn btn-sm btn-light text-success">View Cart</a>
                            </div>

                            <button type="button" class="btn-close btn-close-white ms-2 mb-1" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>

                <!-- Add to Cart or Checkout -->
                <?php if (!$inCart): ?>
                    <button id="addToCartBtn" class="btn btn-lg btn-primary w-100" data-product-id="<?= $productId ?>"
                        data-product-name="<?= htmlspecialchars($product['name']) ?>"
                        data-product-price="<?= $product['price'] ?>" data-product-thumbnail="<?= $product['thumbnail'] ?>">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                <?php else: ?>
                    <a href="shopping-cart" class="btn btn-lg btn-primary w-100">
                        <i class="fas fa-check-circle"></i> Proceed to Checkout
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ==================== ROW 2: Description + Reviews ==================== -->
<div class="row mt-5 g-4">
    <!-- Product Description -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Product Description</h4>
                <hr>
                <div class="card-text"><?= $product['detailed_description'] ?></div>
            </div>
        </div>
    </div>
    <!-- Seller Information & Reviews Column -->
    <div class="col-md-4">
        <?php
        // Fetch vendor details and check plan permissions
        $vendorStmt = $pdo->prepare("
    SELECT v.id, v.business_name, slug, v.logo_path, p.vendor_store_view
    FROM isk_vendors v
    JOIN isk_vendor_plans p ON v.plan_id = p.id
    WHERE v.id = ? 
    LIMIT 1
");
        $vendorStmt->execute([$product['vendor_id']]);
        $vendor = $vendorStmt->fetch(PDO::FETCH_ASSOC);

        // Reviews count (safe default)
        $reviewsCount = isset($ratingData['total_reviews']) ? (int) $ratingData['total_reviews'] : 0;
        ?>

        <?php if ($vendor && $vendor['vendor_store_view']): ?>
            <!-- Seller Profile Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="card-title mb-3 text-primary">Seller Information</h6>
                    <hr>
                    <div class="d-flex align-items-center mb-3">
                        <?php if (!empty($vendor['logo_path'])): ?>
                            <!-- Vendor logo -->
                            <img src="uploads/<?= htmlspecialchars($vendor['logo_path']) ?>"
                                alt="<?= htmlspecialchars($vendor['business_name']) ?>" class="rounded-circle me-3"
                                style="width:56px;height:56px;object-fit:cover;">
                        <?php else: ?>
                            <!-- Placeholder avatar -->
                            <div class="bg-light rounded-circle me-3 d-flex align-items-center justify-content-center"
                                style="width:56px;height:56px;">
                                <i class="fas fa-store text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h6 class="mb-0"><?= htmlspecialchars($vendor['business_name']) ?></h6>
                            <small class="text-muted">Trusted seller</small>
                        </div>
                    </div>
                    <p class="text-muted small mb-3">Top rated for service & delivery.</p>
                    <a href="<?= BASE_URL ?>vendor/<?= urlencode($vendor['slug']) ?>" class="btn btn-outline-primary w-100">
                        View Seller Store
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Ratings & Reviews Card (always shown) -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="card-title mb-3 text-primary">Ratings & Reviews</h6>
                <p class="mb-1"><strong><?= $reviewsCount ?></strong> review(s)</p>
            </div>
        </div>
    </div>
</div>
<?php include "includes/related-products.php"; ?>

<?php
$pageContent = ob_get_clean();
include 'layouts/single-product-template.php';
?>
<script>
    // Set global JS values from PHP session
    const currencySymbol = "<?= getCurrencySymbol($_SESSION['currency']) ?>";
    const currencyDecimals = <?= $_SESSION['currency'] === 'KES' ? 0 : 2 ?>;
    document.addEventListener('DOMContentLoaded', function () {
        const qtyInput = document.getElementById('quantityInput');
        const btnMinus = document.querySelector('.quantity-minus');
        const btnPlus = document.querySelector('.quantity-plus');
        const bulkTiers = <?= json_encode($bulkTiers) ?>;

        // Function to display the correct bulk pricing notice based on current quantity
        function updateBulkNotice() {
            const qty = Math.max(1, parseInt(qtyInput.value) || 1);
            const bulkAlert = document.getElementById('bulkAlert');
            let matched = null;

            // Loop through tiers to find applicable one
            for (let tier of bulkTiers) {
                const min = parseInt(tier.min_quantity);
                const max = tier.max_quantity ? parseInt(tier.max_quantity) : Infinity;
                if (qty >= min && qty <= max) {
                    matched = tier;
                    break;
                }
            }

            // Show discount or pricing table
            if (matched) {
                bulkAlert.classList.remove('alert-info');
                bulkAlert.classList.add('alert-success');
                bulkAlert.innerHTML = `
    <strong>Bulk Discount Applied:</strong><br>
    You’ll pay <strong> ${currencySymbol} ${parseFloat(matched.price_per_unit_converted)
                        .toLocaleString(undefined, { minimumFractionDigits: currencyDecimals, maximumFractionDigits: currencyDecimals })}</strong> per item for ${qty} units.
`;

            } else {
                bulkAlert.classList.remove('alert-success');
                bulkAlert.classList.add('alert-info');
                bulkAlert.innerHTML = `
                    <strong>Bulk Pricing Available:</strong><br>
                    <ul class="mb-0 ps-3">
                        ${bulkTiers.map(t => {
                    const max = t.max_quantity ? ' - ' + t.max_quantity : '+';
                    return `<li>Buy ${t.min_quantity}${max} @ ${currencySymbol} ${parseFloat(t.price_per_unit).toLocaleString(undefined, { minimumFractionDigits: currencyDecimals, maximumFractionDigits: currencyDecimals })} each</li>`;
                }).join('')}
                    </ul>
                `;
            }
        }

        // Quantity adjustment buttons
        btnMinus?.addEventListener('click', () => {
            let val = parseInt(qtyInput.value) || 1;
            if (val > 1) {
                qtyInput.value = val - 1;
                updateBulkNotice();
            }
        });

        btnPlus?.addEventListener('click', () => {
            let val = parseInt(qtyInput.value) || 1;
            qtyInput.value = val + 1;
            updateBulkNotice();
        });

        // Manual input triggers update
        qtyInput?.addEventListener('input', updateBulkNotice);

        // Initial load
        updateBulkNotice();
    });

    // Handle add to cart logic
    document.addEventListener('DOMContentLoaded', function () {
        const addToCartBtn = document.getElementById('addToCartBtn');
        const qtyInput = document.getElementById('quantityInput');
        const bulkTiers = <?= json_encode($bulkTiers) ?>;

        addToCartBtn?.addEventListener('click', function () {
            const minQty = bulkTiers.length ? parseInt(bulkTiers[0].min_quantity) : 1;
            const qty = parseInt(qtyInput?.value) || 1;

            // Reject if quantity is below minimum
            if (qty < minQty) {
                alert(`This product requires a minimum of ${minQty} items to purchase.`);
                return;
            }

            let pricePerUnit = parseFloat(this.dataset.productPrice);

            // Apply bulk price if matched
            for (let tier of bulkTiers) {
                const min = parseInt(tier.min_quantity);
                const max = tier.max_quantity ? parseInt(tier.max_quantity) : Infinity;
                if (qty >= min && qty <= max) {
                    pricePerUnit = parseFloat(tier.price_per_unit);
                    break;
                }
            }

            const data = {
                product_id: this.dataset.productId,
                name: this.dataset.productName,
                price: pricePerUnit,
                thumbnail: this.dataset.productThumbnail,
                quantity: qty,
                min_quantity: minQty,
                variant_id: this.dataset.variantId || null  // passes variant to server
            };

            // AJAX to add product to cart
            fetch('ajax/add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
                .then(res => res.json())
                .then(res => {
                    const toastEl = document.getElementById('cartToast');
                    const toastBody = document.getElementById('cartToastMessage');

                    if (res.status === 'success') {
                        toastBody.textContent = 'Product added to cart.';
                        new bootstrap.Toast(toastEl).show();
                    } else {
                        toastBody.textContent = res.message || 'Product added to cart.';
                        new bootstrap.Toast(toastEl).show();
                    }
                })
                .catch(err => {
                    alert('Something went wrong.');
                    console.error(err);
                });
        });

    });

    // Share product functionality
    document.getElementById('shareProductBtn')?.addEventListener('click', function () {
        const url = document.getElementById('productShareUrl').value;
        const productName = "<?= addslashes($product['name']) ?>";

        if (navigator.share) {
            navigator.share({
                title: productName,
                text: `Check out this product: ${productName}`,
                url: url
            }).catch((error) => {
                console.warn('Share failed:', error.message);
            });
        } else {
            navigator.clipboard.writeText(url).then(() => {
                alert("Link copied to clipboard!");
            }).catch(() => {
                prompt("Copy this product link:", url);
            });
        }
    });

    // Add product to wishlist
    document.getElementById('wishlistBtn')?.addEventListener('click', function () {
        const productId = this.dataset.productId;

        fetch('ajax/add_to_wishlist.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId })
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Added to wishlist!');
                } else if (data.status === 'login') {
                    window.location.href = 'login?next=' + encodeURIComponent(window.location.href);
                } else {
                    alert(data.message || 'Something went wrong.');
                }
            })
            .catch(err => {
                console.error('Wishlist error:', err);
                alert('Could not add to wishlist.');
            });
    });
</script>
<script>
    // Set global JS values from PHP session
    const currencySymbol = "<?= getCurrencySymbol($_SESSION['currency']) ?>";
    const currencyDecimals = <?= $_SESSION['currency'] === 'KES' ? 0 : 2 ?>;
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectors = document.querySelectorAll('.variant-selector');
        const priceDisplay = document.getElementById('variantPriceDisplay');
        const stockDisplay = document.getElementById('variantStockDisplay');

        // Load all product variants from DB
        const allVariants = <?= json_encode($pdo->query("SELECT pv.id, pv.price, pv.stock, GROUP_CONCAT(pva.attribute_value_id ORDER BY pva.attribute_id) AS value_combo FROM isk_product_variants pv JOIN isk_product_variant_attributes pva ON pv.id = pva.variant_id WHERE pv.product_id = {$product['id']} GROUP BY pv.id")->fetchAll(PDO::FETCH_ASSOC)); ?>;

        // Build comma-separated combo from selected dropdowns
        function getSelectedCombo() {
            const selectedValues = Array.from(selectors).map(sel => sel.value).filter(v => v !== "");
            return selectedValues.sort().join(',');
        }

        // Update displayed variant info based on selection
        function updateVariantInfo() {
            const combo = getSelectedCombo();
            const matched = allVariants.find(v => {
                const variantCombo = v.value_combo.split(',').sort().join(',');
                return variantCombo === combo;
            });

            if (matched) {
                priceDisplay.innerText = currencySymbol + " " + Number(matched.price).toLocaleString();
                stockDisplay.innerText = matched.stock + " in stock";

                const addBtn = document.getElementById('addToCartBtn');
                if (addBtn) {
                    addBtn.setAttribute('data-product-price', matched.price);
                    addBtn.setAttribute('data-variant-id', matched.id); // 
                }
                document.getElementById('selectedVariantId').value = matched.id;
            } else {
                priceDisplay.innerText = "Select variant";
                stockDisplay.innerText = "";
            }
        }

        if (matched) {
            priceDisplay.innerText = currencySymbol + " " + Number(matched.price).toLocaleString();
            stockDisplay.innerText = matched.stock + " in stock";

            const addBtn = document.getElementById('addToCartBtn');
            if (addBtn) {
                addBtn.setAttribute('data-product-price', matched.price);
                addBtn.setAttribute('data-variant-id', matched.id); // Optional: pass variant ID too
            }
        }

    }

        selectors.forEach(sel => {
        sel.addEventListener('change', updateVariantInfo);
    });

    // Initial load
    updateVariantInfo();

    );
</script>