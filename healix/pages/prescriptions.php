<?php
// ============================================================
// pages/prescriptions.php — Prescription Management
// Issue prescriptions linked to completed appointments
// ============================================================

$page_title = 'Prescriptions';
require_once '../config/db.php';

// ---- Handle "Add Prescription" form submission ----
if (isset($_POST['add_prescription'])) {

    $appointment_id = (int) $_POST['appointment_id'];
    $medicines      = $conn->real_escape_string(trim($_POST['medicines']));
    $notes          = $conn->real_escape_string(trim($_POST['notes']));

    // Prevent duplicate prescriptions for the same appointment (UNIQUE key in DB)
    $sql = "INSERT INTO prescriptions (appointment_id, medicines, notes)
            VALUES ($appointment_id, '$medicines', '$notes')";

    if ($conn->query($sql)) {
        $success = "Prescription saved successfully!";
    } else {
        $error = "Error: A prescription may already exist for this appointment. " . $conn->error;
    }
}

// ---- Fetch completed appointments for the dropdown ----
// Only show Completed appointments that don't have a prescription yet
$appointments = $conn->query(
    "SELECT a.appointment_id, p.name AS patient_name, d.name AS doctor_name, a.app_date
     FROM appointments a
     JOIN patients p ON a.patient_id = p.patient_id
     JOIN doctors  d ON a.doctor_id  = d.doctor_id
     WHERE a.status = 'Completed'
       AND a.appointment_id NOT IN (SELECT appointment_id FROM prescriptions)
     ORDER BY a.app_date DESC"
);

// ---- Fetch all prescriptions ----
$prescriptions = $conn->query(
    "SELECT pr.prescription_id, pr.appointment_id, p.name AS patient_name,
            d.name AS doctor_name, pr.medicines, pr.notes, pr.created_at
     FROM prescriptions pr
     JOIN appointments a ON pr.appointment_id = a.appointment_id
     JOIN patients p ON a.patient_id = p.patient_id
     JOIN doctors  d ON a.doctor_id  = d.doctor_id
     ORDER BY pr.prescription_id DESC"
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
    <h1><i class="bi bi-file-earmark-medical" style="font-size:1.6rem;vertical-align:middle;margin-right:10px;"></i>Prescriptions</h1>
    <p>Issue and view medical prescriptions for completed appointments.</p>
</div>

<div class="row g-4">

    <!-- ============================================================
         LEFT COLUMN: Add Prescription Form
    ============================================================ -->
    <div class="col-lg-4">
        <div class="healix-card">
            <div class="card-title"><i class="bi bi-file-earmark-plus"></i> New Prescription</div>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Completed Appointment *</label>
                    <select name="appointment_id" class="form-select" required>
                        <option value="">-- Select Appointment --</option>
                        <?php if ($appointments && $appointments->num_rows > 0): ?>
                            <?php while ($a = $appointments->fetch_assoc()): ?>
                            <option value="<?= $a['appointment_id'] ?>">
                                #<?= $a['appointment_id'] ?> — <?= htmlspecialchars($a['patient_name']) ?>
                                (Dr. <?= htmlspecialchars($a['doctor_name']) ?>)
                                — <?= date('d M Y', strtotime($a['app_date'])) ?>
                            </option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option disabled>No pending appointments</option>
                        <?php endif; ?>
                    </select>
                    <small style="color:var(--neutral-700);font-size:0.78rem;">Only Completed appointments without a prescription are shown.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Medicines *</label>
                    <textarea name="medicines" class="form-control" rows="4"
                        placeholder="Napa 500mg - 1+0+1 (7 days)&#10;Seclo 20mg - 1+0+0 (14 days)"
                        required></textarea>
                    <small style="color:var(--neutral-700);font-size:0.78rem;">Enter each medicine on a new line.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label">Doctor's Notes / Advice</label>
                    <textarea name="notes" class="form-control" rows="3"
                        placeholder="Take rest. Drink plenty of water. Avoid spicy food."></textarea>
                </div>

                <button type="submit" name="add_prescription" class="btn-healix-primary">
                    <i class="bi bi-file-earmark-check"></i> Save Prescription
                </button>
            </form>
        </div>
    </div>

    <!-- ============================================================
         RIGHT COLUMN: Prescription List
    ============================================================ -->
    <div class="col-lg-8">
        <div class="healix-card">
            <div class="card-title" style="display:flex;justify-content:space-between;align-items:center;">
                <span><i class="bi bi-journal-medical"></i> All Prescriptions</span>
                <span style="font-family:var(--font-body);font-size:0.82rem;font-weight:500;background:var(--primary-pale);color:var(--primary);padding:3px 10px;border-radius:20px;">
                    <?= $prescriptions->num_rows ?> total
                </span>
            </div>

            <?php if ($prescriptions->num_rows > 0): ?>
            <div style="display:flex;flex-direction:column;gap:14px;">
                <?php while ($row = $prescriptions->fetch_assoc()): ?>

                <!-- Prescription Card -->
                <div style="border:1.5px solid var(--neutral-200);border-radius:10px;overflow:hidden;">

                    <!-- Header -->
                    <div style="background:var(--primary-pale);padding:12px 16px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
                        <div>
                            <strong style="color:var(--primary);"><?= htmlspecialchars($row['patient_name']) ?></strong>
                            <span style="color:var(--neutral-700);font-size:0.83rem;margin-left:8px;">Apt. #<?= $row['appointment_id'] ?></span>
                        </div>
                        <div style="font-size:0.8rem;color:var(--neutral-700);">
                            <i class="bi bi-person-badge"></i> Dr. <?= htmlspecialchars($row['doctor_name']) ?>
                            &nbsp;|&nbsp;
                            <i class="bi bi-clock"></i> <?= date('d M Y', strtotime($row['created_at'])) ?>
                        </div>
                    </div>

                    <!-- Body -->
                    <div style="padding:14px 16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div>
                            <div style="font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:var(--neutral-700);margin-bottom:6px;">
                                <i class="bi bi-capsule"></i> Medicines
                            </div>
                            <div style="font-size:0.88rem;line-height:1.6;white-space:pre-line;">
                                <?= nl2br(htmlspecialchars($row['medicines'])) ?>
                            </div>
                        </div>
                        <?php if ($row['notes']): ?>
                        <div>
                            <div style="font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:var(--neutral-700);margin-bottom:6px;">
                                <i class="bi bi-chat-square-text"></i> Notes
                            </div>
                            <div style="font-size:0.88rem;line-height:1.6;color:var(--neutral-700);">
                                <?= nl2br(htmlspecialchars($row['notes'])) ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php endwhile; ?>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-file-earmark-medical"></i>
                <p>No prescriptions issued yet.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>
