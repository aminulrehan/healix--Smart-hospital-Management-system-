<?php
// ============================================================
// pages/doctors.php — Doctor Management
// Add new doctors and view all doctor profiles
// ============================================================

$page_title = 'Doctors';
require_once '../config/db.php';

// ---- Handle "Add Doctor" form submission ----
if (isset($_POST['add_doctor'])) {

    $name          = $conn->real_escape_string(trim($_POST['name']));
    $specialty     = $conn->real_escape_string(trim($_POST['specialty']));
    $phone         = $conn->real_escape_string(trim($_POST['phone']));
    $email         = $conn->real_escape_string(trim($_POST['email']));
    $available_days = $conn->real_escape_string(trim($_POST['available_days']));

    $sql = "INSERT INTO doctors (name, specialty, phone, email, available_days)
            VALUES ('$name', '$specialty', '$phone', '$email', '$available_days')";

    if ($conn->query($sql)) {
        $success = "Dr. <strong>$name</strong> has been registered successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// ---- Fetch all doctors ----
$doctors = $conn->query(
    "SELECT * FROM doctors ORDER BY doctor_id DESC"
);

require_once '../includes/header.php';
?>

<!-- ---- Alert Messages ---- -->
<?php if (!empty($success)): ?>
    <div class="healix-alert healix-alert-success">
        <i class="bi bi-check-circle-fill"></i> <?= $success ?>
    </div>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <div class="healix-alert healix-alert-danger">
        <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<!-- ---- Page Header ---- -->
<div class="page-header">
    <h1><i class="bi bi-person-badge" style="font-size:1.6rem;vertical-align:middle;margin-right:10px;"></i>Doctors</h1>
    <p>Manage doctor profiles and specializations.</p>
</div>

<div class="row g-4">

    <!-- ============================================================
         LEFT COLUMN: Add Doctor Form
    ============================================================ -->
    <div class="col-lg-4">
        <div class="healix-card">
            <div class="card-title"><i class="bi bi-person-plus"></i> Add New Doctor</div>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Doctor's Full Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Hasan Ali" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Specialty *</label>
                    <input type="text" name="specialty" class="form-control" placeholder="e.g. Cardiologist" list="specialties" required>
                    <datalist id="specialties">
                        <?php
                        $specs = ['Cardiology','Dermatology','Gynecology','Orthopedics','Neurology',
                                  'Pediatrics','Psychiatry','Oncology','Radiology','General Surgery'];
                        foreach ($specs as $s) echo "<option value='$s'>";
                        ?>
                    </datalist>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone *</label>
                    <input type="text" name="phone" class="form-control" placeholder="01XXXXXXXXX" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="doctor@healix.com">
                </div>

                <div class="mb-4">
                    <label class="form-label">Available Days</label>
                    <select name="available_days" class="form-select">
                        <option value="Mon-Fri">Monday – Friday</option>
                        <option value="Mon-Thu">Monday – Thursday</option>
                        <option value="Sun-Wed">Sunday – Wednesday</option>
                        <option value="Tue-Sat">Tuesday – Saturday</option>
                        <option value="Mon-Sat">Monday – Saturday</option>
                        <option value="Weekends">Weekends Only</option>
                    </select>
                </div>

                <button type="submit" name="add_doctor" class="btn-healix-primary">
                    <i class="bi bi-person-check"></i> Register Doctor
                </button>
            </form>
        </div>
    </div>

    <!-- ============================================================
         RIGHT COLUMN: Doctor Cards Grid
    ============================================================ -->
    <div class="col-lg-8">
        <div class="healix-card">
            <div class="card-title" style="display:flex;justify-content:space-between;align-items:center;">
                <span><i class="bi bi-grid"></i> All Doctors</span>
                <span style="font-family:var(--font-body);font-size:0.82rem;font-weight:500;background:var(--primary-pale);color:var(--primary);padding:3px 10px;border-radius:20px;">
                    <?= $doctors->num_rows ?> registered
                </span>
            </div>

            <?php if ($doctors->num_rows > 0): ?>
            <div class="row g-3">
                <?php while ($row = $doctors->fetch_assoc()): ?>
                <div class="col-sm-6">
                    <!-- Doctor Profile Card -->
                    <div style="border:1.5px solid var(--neutral-200);border-radius:10px;padding:18px;transition:box-shadow 0.2s;background:#fff;" onmouseover="this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.boxShadow='none'">
                        <!-- Avatar + Name -->
                        <div style="display:flex;align-items:center;gap:14px;margin-bottom:12px;">
                            <div style="width:46px;height:46px;border-radius:50%;background:var(--primary-pale);color:var(--primary);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.1rem;flex-shrink:0;">
                                <?= strtoupper(substr($row['name'], 0, 1)) ?>
                            </div>
                            <div>
                                <div style="font-weight:600;color:var(--neutral-900);">Dr. <?= htmlspecialchars($row['name']) ?></div>
                                <div style="font-size:0.8rem;">
                                    <span class="badge-healix badge-primary"><?= htmlspecialchars($row['specialty']) ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Details -->
                        <div style="font-size:0.82rem;color:var(--neutral-700);display:flex;flex-direction:column;gap:4px;">
                            <span><i class="bi bi-telephone" style="width:16px;"></i> <?= htmlspecialchars($row['phone']) ?></span>
                            <?php if ($row['email']): ?>
                            <span><i class="bi bi-envelope" style="width:16px;"></i> <?= htmlspecialchars($row['email']) ?></span>
                            <?php endif; ?>
                            <span><i class="bi bi-calendar3" style="width:16px;"></i> <?= htmlspecialchars($row['available_days']) ?></span>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-person-badge"></i>
                <p>No doctors registered yet.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>
