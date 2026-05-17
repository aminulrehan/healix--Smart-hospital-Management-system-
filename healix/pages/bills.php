<?php
// ============================================================
// pages/bills.php — Bill Management
// Generate patient bills and track payment status
// ============================================================

$page_title = 'Bills';
require_once '../config/db.php';

// ---- Handle "Create Bill" form submission ----
if (isset($_POST['add_bill'])) {

    $patient_id     = (int) $_POST['patient_id'];
    $amount         = (float) $_POST['amount'];
    $payment_status = $conn->real_escape_string($_POST['payment_status']);
    $description    = $conn->real_escape_string(trim($_POST['description']));
    $bill_date      = $conn->real_escape_string($_POST['bill_date']);

    if ($amount <= 0) {
        $error = "Amount must be greater than 0.";
    } else {
        $sql = "INSERT INTO bills (patient_id, amount, payment_status, description, bill_date)
                VALUES ($patient_id, $amount, '$payment_status', '$description', '$bill_date')";

        if ($conn->query($sql)) {
            $success = "Bill of <strong>TK. " . number_format($amount, 2) . "</strong> generated successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}

// ---- Handle payment status update ----
if (isset($_GET['pay']) && isset($_GET['id'])) {
    $bill_id = (int) $_GET['id'];
    $conn->query("UPDATE bills SET payment_status='Paid' WHERE bill_id=$bill_id");
    header("Location: bills.php?msg=paid");
    exit;
}

// ---- Fetch patients for dropdown ----
$patients = $conn->query("SELECT patient_id, name FROM patients ORDER BY name");

// ---- Fetch all bills with patient name ----
$bills = $conn->query(
    "SELECT b.bill_id, p.name AS patient_name, b.amount,
            b.payment_status, b.description, b.bill_date
     FROM bills b
     JOIN patients p ON b.patient_id = p.patient_id
     ORDER BY b.bill_id DESC"
);

// ---- Summary stats ----
$total_amount   = $conn->query("SELECT SUM(amount) AS s FROM bills")->fetch_assoc()['s'] ?? 0;
$paid_amount    = $conn->query("SELECT SUM(amount) AS s FROM bills WHERE payment_status='Paid'")->fetch_assoc()['s'] ?? 0;
$unpaid_amount  = $conn->query("SELECT SUM(amount) AS s FROM bills WHERE payment_status='Unpaid'")->fetch_assoc()['s'] ?? 0;

require_once '../includes/header.php';
?>

<!-- ---- Alert Messages ---- -->
<?php if (!empty($success) || isset($_GET['msg'])): ?>
    <div class="healix-alert healix-alert-success">
        <i class="bi bi-check-circle-fill"></i>
        <?= !empty($success) ? $success : 'Payment status updated to Paid.' ?>
    </div>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <div class="healix-alert healix-alert-danger">
        <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<!-- ---- Page Header ---- -->
<div class="page-header">
    <h1><i class="bi bi-receipt" style="font-size:1.6rem;vertical-align:middle;margin-right:10px;"></i>Bills</h1>
    <p>Generate and manage patient billing records.</p>
</div>

<!-- ---- Financial Summary ---- -->
<div class="row g-3 mb-4">
    <div class="col-4">
        <div class="stat-card">
            <div class="stat-icon stat-icon-teal"><i class="bi bi-cash-stack"></i></div>
            <div>
                <div class="stat-value" style="font-size:1.5rem;">TK. <?= number_format($total_amount, 0) ?></div>
                <div class="stat-label">Total Billed</div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="stat-card">
            <div class="stat-icon stat-icon-green"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="stat-value" style="font-size:1.5rem;color:var(--success);">TK. <?= number_format($paid_amount, 0) ?></div>
                <div class="stat-label">Collected</div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="stat-card">
            <div class="stat-icon stat-icon-red"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="stat-value" style="font-size:1.5rem;color:var(--danger);">TK. <?= number_format($unpaid_amount, 0) ?></div>
                <div class="stat-label">Outstanding</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    <!-- ============================================================
         LEFT COLUMN: Create Bill Form
    ============================================================ -->
    <div class="col-lg-4">
        <div class="healix-card">
            <div class="card-title"><i class="bi bi-receipt-cutoff"></i> Create New Bill</div>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Patient *</label>
                    <select name="patient_id" class="form-select" required>
                        <option value="">-- Select Patient --</option>
                        <?php
                        $patients->data_seek(0);
                        while ($p = $patients->fetch_assoc()):
                        ?>
                        <option value="<?= $p['patient_id'] ?>"><?= htmlspecialchars($p['name']) ?> (#<?= $p['patient_id'] ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Amount (BDT) *</label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--neutral-700);font-weight:600;">TK.</span>
                        <input type="number" name="amount" class="form-control" step="0.01" min="1"
                               placeholder="0.00" style="padding-left:44px;" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" class="form-control"
                           placeholder="e.g. Cardiology Consultation + ECG">
                </div>

                <div class="mb-3">
                    <label class="form-label">Bill Date</label>
                    <input type="date" name="bill_date" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>

                <div class="mb-4">
                    <label class="form-label">Payment Status</label>
                    <select name="payment_status" class="form-select">
                        <option value="Unpaid">Unpaid</option>
                        <option value="Paid">Paid</option>
                        <option value="Partial">Partial</option>
                    </select>
                </div>

                <button type="submit" name="add_bill" class="btn-healix-primary">
                    <i class="bi bi-file-earmark-text"></i> Generate Bill
                </button>
            </form>
        </div>
    </div>

    <!-- ============================================================
         RIGHT COLUMN: Bills Table
    ============================================================ -->
    <div class="col-lg-8">
        <div class="healix-card">
            <div class="card-title" style="display:flex;justify-content:space-between;align-items:center;">
                <span><i class="bi bi-table"></i> Bill Records</span>
                <span style="font-family:var(--font-body);font-size:0.82rem;font-weight:500;background:var(--primary-pale);color:var(--primary);padding:3px 10px;border-radius:20px;">
                    <?= $bills->num_rows ?> total
                </span>
            </div>

            <?php if ($bills->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="healix-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patient</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $bills->fetch_assoc()):
                            $badge = match($row['payment_status']) {
                                'Paid'    => 'badge-success',
                                'Partial' => 'badge-warning',
                                default   => 'badge-danger',
                            };
                        ?>
                        <tr>
                            <td><strong style="color:var(--primary);">#<?= $row['bill_id'] ?></strong></td>
                            <td><?= htmlspecialchars($row['patient_name']) ?></td>
                            <td style="font-size:0.83rem;max-width:160px;"><?= htmlspecialchars($row['description'] ?: '—') ?></td>
                            <td><?= date('d M Y', strtotime($row['bill_date'])) ?></td>
                            <td><strong>TK. <?= number_format($row['amount'], 2) ?></strong></td>
                            <td><span class="badge-healix <?= $badge ?>"><?= $row['payment_status'] ?></span></td>
                            <td>
                                <?php if ($row['payment_status'] !== 'Paid'): ?>
                                <a href="?pay=1&id=<?= $row['bill_id'] ?>"
                                   style="font-size:0.78rem;color:var(--success);font-weight:600;text-decoration:none;"
                                   onclick="return confirm('Mark this bill as Paid?')">
                                    <i class="bi bi-check2-circle"></i> Mark Paid
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
                <i class="bi bi-receipt"></i>
                <p>No bills generated yet.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>
