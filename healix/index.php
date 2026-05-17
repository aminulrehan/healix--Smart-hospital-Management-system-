<?php
// ============================================================
// index.php — Dashboard
// Shows summary statistics for the hospital system
// ============================================================

$page_title = 'Dashboard';
require_once 'config/db.php';
require_once 'includes/header.php';

// ---- Fetch summary counts ----
$total_patients     = $conn->query("SELECT COUNT(*) AS c FROM patients")->fetch_assoc()['c'];
$total_doctors      = $conn->query("SELECT COUNT(*) AS c FROM doctors")->fetch_assoc()['c'];
$total_appointments = $conn->query("SELECT COUNT(*) AS c FROM appointments")->fetch_assoc()['c'];
$total_unpaid       = $conn->query("SELECT COUNT(*) AS c FROM bills WHERE payment_status='Unpaid'")->fetch_assoc()['c'];

// ---- Fetch today's appointments ----
$today = date('Y-m-d');
$today_apt = $conn->query(
    "SELECT a.appointment_id, p.name AS patient_name, d.name AS doctor_name,
            a.app_time, a.status, a.reason
     FROM appointments a
     JOIN patients p ON a.patient_id = p.patient_id
     JOIN doctors  d ON a.doctor_id  = d.doctor_id
     WHERE a.app_date = '$today'
     ORDER BY a.app_time ASC"
);
?>

<!-- ---- Page Header ---- -->
<div class="page-header">
    <h1><i class="bi bi-speedometer2" style="font-size:1.6rem;vertical-align:middle;margin-right:10px;"></i>Dashboard</h1>
    <p>Welcome to Healix — here's a snapshot of today's hospital activity.</p>
</div>

<!-- ---- Stats Row ---- -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <a href="pages/patients.php" class="text-decoration-none">
            <div class="stat-card">
                <div class="stat-icon stat-icon-teal"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="stat-value"><?= $total_patients ?></div>
                    <div class="stat-label">Patients</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="pages/doctors.php" class="text-decoration-none">
            <div class="stat-card">
                <div class="stat-icon stat-icon-green"><i class="bi bi-person-badge-fill"></i></div>
                <div>
                    <div class="stat-value"><?= $total_doctors ?></div>
                    <div class="stat-label">Doctors</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="pages/appointments.php" class="text-decoration-none">
            <div class="stat-card">
                <div class="stat-icon stat-icon-amber"><i class="bi bi-calendar-check-fill"></i></div>
                <div>
                    <div class="stat-value"><?= $total_appointments ?></div>
                    <div class="stat-label">Appointments</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="pages/bills.php" class="text-decoration-none">
            <div class="stat-card">
                <div class="stat-icon stat-icon-red"><i class="bi bi-receipt-cutoff"></i></div>
                <div>
                    <div class="stat-value"><?= $total_unpaid ?></div>
                    <div class="stat-label">Unpaid Bills</div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- ---- Today's Appointments ---- -->
<div class="healix-card">
    <div class="card-title">
        <i class="bi bi-calendar2-day"></i>
        Today's Appointments
        <small style="font-family:var(--font-body);font-size:0.8rem;color:var(--neutral-700);margin-left:8px;"><?= date('D, d M Y') ?></small>
    </div>

    <?php if ($today_apt && $today_apt->num_rows > 0): ?>
    <div class="table-responsive">
        <table class="healix-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Time</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $today_apt->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['appointment_id'] ?></td>
                    <td><?= htmlspecialchars($row['patient_name']) ?></td>
                    <td>Dr. <?= htmlspecialchars($row['doctor_name']) ?></td>
                    <td><?= date('h:i A', strtotime($row['app_time'])) ?></td>
                    <td><?= htmlspecialchars($row['reason'] ?? '—') ?></td>
                    <td>
                        <?php
                        $badge = match($row['status']) {
                            'Completed' => 'badge-success',
                            'Cancelled' => 'badge-danger',
                            default     => 'badge-primary',
                        };
                        ?>
                        <span class="badge-healix <?= $badge ?>">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="bi bi-calendar-x"></i>
        <p>No appointments scheduled for today.</p>
        <a href="pages/appointments.php" class="btn-healix-primary" style="width:auto;display:inline-flex;padding:10px 20px;">
            <i class="bi bi-plus-lg"></i> Book Appointment
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- ---- Quick Links ---- -->
<div class="row g-3 mt-2">
    <?php
    $quick = [
        ['pages/patients.php',     'bi-person-plus',      'Add Patient',     'Register a new patient'],
        ['pages/doctors.php',      'bi-person-badge',     'Add Doctor',      'Add a new doctor profile'],
        ['pages/appointments.php', 'bi-calendar-plus',    'Book Appointment','Schedule a new visit'],
        ['pages/bills.php',        'bi-cash-stack',       'Create Bill',     'Generate a patient bill'],
    ];
    foreach ($quick as [$link, $icon, $title, $desc]):
    ?>
    <div class="col-6 col-md-3">
        <a href="<?= $link ?>" class="text-decoration-none">
            <div class="stat-card" style="flex-direction:column;align-items:flex-start;gap:10px;padding:20px;">
                <i class="bi <?= $icon ?>" style="font-size:1.4rem;color:var(--primary-light);"></i>
                <div>
                    <div style="font-weight:600;font-size:0.92rem;color:var(--primary);"><?= $title ?></div>
                    <div style="font-size:0.8rem;color:var(--neutral-700);"><?= $desc ?></div>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
