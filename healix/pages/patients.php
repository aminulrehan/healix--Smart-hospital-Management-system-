<?php
// ============================================================
// pages/patients.php — Patient Management
// Register new patients and view all patient records
// ============================================================

$page_title = 'Patients';
require_once '../config/db.php';

// ---- Handle "Add Patient" form submission ----
if (isset($_POST['add_patient'])) {

    // Sanitize all inputs to prevent SQL injection
    $name        = $conn->real_escape_string(trim($_POST['name']));
    $age         = (int) $_POST['age'];
    $gender      = $conn->real_escape_string($_POST['gender']);
    $phone       = $conn->real_escape_string(trim($_POST['phone']));
    $blood_group = $conn->real_escape_string(trim($_POST['blood_group']));
    $address     = $conn->real_escape_string(trim($_POST['address']));
    $email       = $conn->real_escape_string(trim($_POST['email']));

    // Insert the new patient into the database
    $sql = "INSERT INTO patients (name, age, gender, phone, blood_group, address, email)
            VALUES ('$name', $age, '$gender', '$phone', '$blood_group', '$address', '$email')";

    if ($conn->query($sql)) {
        $success = "Patient <strong>$name</strong> registered successfully!";
    } else {
        // Common error: duplicate phone number
        $error = "Error: " . $conn->error;
    }
}

// ---- Fetch all patients (newest first) ----
$patients = $conn->query(
    "SELECT * FROM patients ORDER BY patient_id DESC"
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
    <h1><i class="bi bi-people" style="font-size:1.6rem;vertical-align:middle;margin-right:10px;"></i>Patients</h1>
    <p>Register new patients and manage patient records.</p>
</div>

<div class="row g-4">

    <!-- ============================================================
         LEFT COLUMN: Add Patient Form
    ============================================================ -->
    <div class="col-lg-4">
        <div class="healix-card">
            <div class="card-title"><i class="bi bi-person-plus"></i> Register New Patient</div>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Emma Johnson" required>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Age *</label>
                        <input type="number" name="age" class="form-control" min="0" max="150" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Gender *</label>
                        <select name="gender" class="form-select" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone *</label>
                    <input type="text" name="phone" class="form-control" placeholder="01XXXXXXXXX" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="patient@email.com">
                </div>

                <div class="mb-3">
                    <label class="form-label">Blood Group</label>
                    <select name="blood_group" class="form-select">
                        <option value="">-- Select --</option>
                        <?php foreach (['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg): ?>
                            <option value="<?= $bg ?>"><?= $bg ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="City, Country"></textarea>
                </div>

                <button type="submit" name="add_patient" class="btn-healix-primary">
                    <i class="bi bi-person-check"></i> Register Patient
                </button>
            </form>
        </div>
    </div>

    <!-- ============================================================
         RIGHT COLUMN: Patient List Table
    ============================================================ -->
    <div class="col-lg-8">
        <div class="healix-card">
            <div class="card-title" style="display:flex;justify-content:space-between;align-items:center;">
                <span><i class="bi bi-table"></i> All Patients</span>
                <span style="font-family:var(--font-body);font-size:0.82rem;font-weight:500;background:var(--primary-pale);color:var(--primary);padding:3px 10px;border-radius:20px;">
                    <?= $patients->num_rows ?> total
                </span>
            </div>

            <?php if ($patients->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="healix-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Phone</th>
                            <th>Blood</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $patients->fetch_assoc()): ?>
                        <tr>
                            <td><strong style="color:var(--primary);">#<?= $row['patient_id'] ?></strong></td>
                            <td>
                                <div style="font-weight:600;"><?= htmlspecialchars($row['name']) ?></div>
                                <?php if ($row['email']): ?>
                                <div style="font-size:0.78rem;color:var(--neutral-700);"><?= htmlspecialchars($row['email']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= $row['age'] ?></td>
                            <td>
                                <span class="badge-healix badge-secondary">
                                    <?= $row['gender'] ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['phone']) ?></td>
                            <td>
                                <?php if ($row['blood_group']): ?>
                                <span class="badge-healix badge-danger"><?= $row['blood_group'] ?></span>
                                <?php else: ?>
                                <span style="color:var(--neutral-700);">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-people"></i>
                <p>No patients registered yet.<br>Use the form on the left to add the first one.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div><!-- .row -->

<?php require_once '../includes/footer.php'; ?>
