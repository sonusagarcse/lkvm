<?php
// admin/donations.php
require_once '../connection.php';
include 'includes/header.php';

// Stats queries
$totalAmountQ = mysqli_query($con, "SELECT SUM(amount) as total FROM donations WHERE status='paid'");
$totalAmount = mysqli_fetch_assoc($totalAmountQ)['total'] ?? 0;

$totalCountQ = mysqli_query($con, "SELECT COUNT(id) as cnt FROM donations WHERE status='paid'");
$totalCount = mysqli_fetch_assoc($totalCountQ)['cnt'] ?? 0;

$todayStatsQ = mysqli_query($con, "SELECT SUM(amount) as total, COUNT(id) as cnt FROM donations WHERE status='paid' AND DATE(created_at) = CURDATE()");
$todayStats = mysqli_fetch_assoc($todayStatsQ);
$todayAmount = $todayStats['total'] ?? 0;
$todayCount = $todayStats['cnt'] ?? 0;

// Fetch donations
$donationsQ = mysqli_query($con, "SELECT * FROM donations ORDER BY created_at DESC");
?>

<div class="row mb-4 align-items-center">
    <div class="col">
        <h2 class="h3 mb-0 text-gray-800">Donations Management</h2>
        <p class="text-muted small mb-0">Track and manage all contributions received through the website.</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 border-start border-primary border-4 shadow-sm py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Collections</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₹<?php echo number_format($totalAmount, 2); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 border-start border-success border-4 shadow-sm py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Today's Collections</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₹<?php echo number_format($todayAmount, 2); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 border-start border-info border-4 shadow-sm py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Donors</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalCount; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 border-start border-warning border-4 shadow-sm py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Today's Donors</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $todayCount; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white py-3">
        <h5 class="m-0 fw-bold text-primary">Donation Records</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered" id="donationTable">
                <thead class="bg-light">
                    <tr>
                        <th>Date</th>
                        <th>Donor Name</th>
                        <th>Amount</th>
                        <th>Email/Mobile</th>
                        <th>PAN/City</th>
                        <th>Payment ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($donationsQ) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($donationsQ)): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?php echo date('d M Y', strtotime($row['created_at'])); ?></div>
                                <small class="text-muted"><?php echo date('h:i A', strtotime($row['created_at'])); ?></small>
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($row['name']); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($row['country']); ?></small>
                            </td>
                            <td>
                                <span class="badge bg-success shadow-sm p-2">₹<?php echo number_format($row['amount'], 2); ?></span>
                            </td>
                            <td>
                                <div class="small"><i class="fas fa-envelope text-muted"></i> <?php echo htmlspecialchars($row['email']); ?></div>
                                <div class="small"><i class="fas fa-phone text-muted"></i> <?php echo htmlspecialchars($row['mobile']); ?></div>
                            </td>
                            <td>
                                <div class="small">PAN: <?php echo htmlspecialchars($row['pan']); ?></div>
                                <div class="small">City: <?php echo htmlspecialchars($row['city']); ?></div>
                            </td>
                            <td>
                                <code><?php echo htmlspecialchars($row['payment_id']); ?></code>
                                <div class="small text-muted"><?php echo htmlspecialchars($row['order_id']); ?></div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">No donation records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
