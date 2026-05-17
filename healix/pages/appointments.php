<?php
// ============================================================
// pages/appointments.php — Appointment Management
// Book new appointments and manage existing ones
// ============================================================

$page_title = 'Appointments';
require_once '../config/db.php';

// ---- Handle "Book Appointment" form submission ----
if (isset($_POST['add_appointment'])) {

    $patient_id = (int) $_POST['patient_id'];
    $doctor_id  = (int) $_POST['doctor_id'];
    $app_date   = $conn->real_escape_string($_POST['app_date']);
    $app_time   = $conn->real_escape_string($_POST['app_time']);
    $status     = $conn->real_escape_string($_POST['status']);
    $reason     = $conn->real_escape_string(trim($_POST['reason']));

    $sql = "INSERT INTO appointments (patient_id, doctor_id, app_date, app_time, status, reason)
            VALUES ($patient_id, $doctor_id, '$app_date', '$app_time', '$status', '$reason')";

    if ($conn->query($sql)) {
        $success = "Appointment booked successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// ---- Handle status update (e.g. mark as Completed) ----
if (isset($_GET['update_status']) && isset($_GET['id'])) {
    $apt_id    = (int) $_GET['id'];
    $new_status = $conn->real_escape_string($_GET['update_status']);
    $conn->query("UPDATE appointments SET status='$new_status' WHERE appointment_id=$apt_id");
    header("Location: appointments.php?msg=updated");
    exit;
}

// ---- Fetch patients and doctors for dropdowns ----
$patients = $conn->query("SELECT patient_id, name FROM patients ORDER BY name");
$doctors  = $conn->query("SELECT doctor_id, name, specialty FROM doctors ORDER BY name");

// ---- Fetch all appointments with JOIN ----
$appointments = $conn->query(
    "SELECT a.appointment_id, p.name AS patient_name, d.name AS doctor_name,
            d.specialty, a.app_date, a.app_time, a.status, a.reason
     FROM appointments a
     JOIN patients p ON a.patient_id = p.patient_id
     JOIN doctors  d ON a.doctor_id  = d.doctor_id
     ORDER BY a.app_date DESC, a.app_time DESC"
);

require_once '../includes/header.php';
?>

<!-- ---- Alert Messages ---- -->
<?php if (!empty($success) || isset($_GET['msg'])): ?>
    <div class="healix-alert healix-alert-success">
        <i class="bi bi-check-circle-fill"></i>
        <?= !empty($success) ? $success : 'Appointment status updated.' ?>
    </div>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <div class="healix-alert healix-alert-danger">
        <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<!-- ---- Page Header ---- -->
<div class="page-header">
    <h1><i class="bi bi-calendar-check" style="font-size:1.6rem;vertical-align:middle;margin-right:10px;"></i>Appointments</h1>
    <p>Schedule and track patient visits.</p>
</div>

<div class="row g-4">

    <!-- ============================================================
         LEFT COLUMN: Book Appointment Form
    ============================================================ -->
    <div class="col-lg-4">
        <div class="healix-card">
            <div class="card-title"><i class="bi bi-calendar-plus"></i> Book Appointment</div>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Patient *</label>
                    <select name="patient_id" class="form-select" required>
                        <option value="">-- Select Patient --</option>
                        <?php
                        // Reset pointer in case it was used above
                        $patients->data_seek(0);
                        while ($p = $patients->fetch_assoc()):
                        ?>
                        <option value="<?= $p['patient_id'] ?>"><?= htmlspecialchars($p['name']) ?> (#<?= $p['patient_id'] ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Doctor *</label>
                    <select name="doctor_id" class="form-select" required>
                        <option value="">-- Select Doctor --</option>
                        <?php
                        $doctors->data_seek(0);
                        while ($d = $doctors->fetch_assoc()):
                        ?>
                        <option value="<?= $d['doctor_id'] ?>">Dr. <?= htmlspecialchars($d['name']) ?> — <?= htmlspecialchars($d['specialty']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-7">
                        <label class="form-label">Date *</label>
                        <input type="date" name="app_date" class="form-control" min="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-5">
                        <label class="form-label">Time</label>
                        <input type="time" name="app_time" class="form-control" value="10:00">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Reason for Visit</label>
                    <input type="text" name="reason" class="form-control" placeholder="e.g. Chest pain, Routine checkup">
                </div>

                <div class="mb-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="Scheduled">Scheduled</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>

                <button type="submit" name="add_appointment" class="btn-healix-primary btn-healix-accent">
                    <i class="bi bi-calendar-check"></i> Book Appointment
                </button>
            </form>
        </div>
    </div>

    <!-- ============================================================
         RIGHT COLUMN: Appointments Table
    ============================================================ -->
    <div class="col-lg-8">
        <div class="healix-card">
            <div class="card-title" style="display:flex;justify-content:space-between;align-items:center;">
                <span><i class="bi bi-list-check"></i> All Appointments</span>
                <span style="font-family:var(--font-body);font-size:0.82rem;font-weight:500;background:var(--primary-pale);color:var(--primary);padding:3px 10px;border-radius:20px;">
                    <?= $appointments->num_rows ?> total
                </span>
            </div>

            <?php if ($appointments->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="healix-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $appointments->fetch_assoc()):
                            $badge = match($row['status']) {
                                'Completed' => 'badge-success',
                                'Cancelled' => 'badge-danger',
                                default     => 'badge-primary',
                            };
                        ?>
                        <tr>
                            <td><strong style="color:var(--primary);">#<?= $row['appointment_id'] ?></strong></td>
                            <td><?= htmlspecialchars($row['patient_name']) ?></td>
                            <td>
                                <div>Dr. <?= htmlspecialchars($row['doctor_name']) ?></div>
                                <div style="font-size:0.75rem;color:var(--neutral-700);"><?= htmlspecialchars($row['specialty']) ?></div>
                            </td>
                            <td>
                                <div><?= date('d M Y', strtotime($row['app_date'])) ?></div>
                                <div style="font-size:0.78rem;color:var(--neutral-700);"><?= date('h:i A', strtotime($row['app_time'])) ?></div>
                            </td>
                            <td><span class="badge-healix <?= $badge ?>"><?= $row['status'] ?></span></td>
                            <td>
                                <?php if ($row['status'] === 'Scheduled'): ?>
                                <a href="?update_status=Completed&id=<?= $row['appointment_id'] ?>"
                                   style="font-size:0.78rem;color:var(--success);text-decoration:none;font-weight:600;"
                                   onclick="return confirm('Mark as Completed?')">
                                    <i class="bi bi-check2"></i> Done
                                </a>
                                &nbsp;|&nbsp;
                                <a href="?update_status=Cancelled&id=<?= $row['appointment_id'] ?>"
                                   style="font-size:0.78rem;color:var(--danger);text-decoration:none;font-weight:600;"
                                   onclick="return confirm('Cancel this appointment?')">
                                    <i class="bi bi-x"></i> Cancel
                                </a>
                                <?php else: ?>
                                <span style="color:var(--neutral-700);font-size:0.78rem;">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-calendar-x"></i>
                <p>No appointments found.<br>Book one using the form.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>
