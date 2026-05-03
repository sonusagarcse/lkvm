<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../connection.php';
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_type'] != 1) {
    header("Location: login.php");
    exit();
}

// Filtering
$month_filter = isset($_GET['month']) ? intval($_GET['month']) : '';
$year_filter = isset($_GET['year']) ? intval($_GET['year']) : '';
$emp_id_filter = isset($_GET['emp_id']) ? mysqli_real_escape_string($con, $_GET['emp_id']) : '';

$where = " WHERE 1=1 ";
if ($month_filter != '') $where .= " AND s.month = $month_filter";
if ($year_filter != '') $where .= " AND s.year = $year_filter";
if ($emp_id_filter != '') $where .= " AND s.emp_no = '$emp_id_filter'";

$all_employees = mysqli_query($con, "SELECT emp_no, name FROM employees ORDER BY name ASC");
$slips_query = mysqli_query($con, "SELECT s.*, e.name, e.designation, e.base_salary FROM emp_salary_slips s JOIN employees e ON s.emp_no = e.emp_no $where ORDER BY s.year DESC, s.month DESC, e.name ASC");

include 'includes/header.php';
?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-history me-2"></i>Salary Slip History</h2>
        <a href="emp_salary_slips.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Generate New Slips
        </a>
    </div>

    <!-- Filters -->
    <div class="glass-panel p-4 mb-4">
        <form method="GET" class="row align-items-end">
            <div class="col-md-3 mb-2">
                <label>Employee</label>
                <select name="emp_id" class="form-control">
                    <option value="">All Employees</option>
                    <?php 
                    mysqli_data_seek($all_employees, 0);
                    while($e = mysqli_fetch_assoc($all_employees)): 
                    ?>
                        <option value="<?php echo $e['emp_no']; ?>" <?php echo $emp_id_filter == $e['emp_no'] ? 'selected' : ''; ?>>
                            <?php echo $e['name'] . ' (' . $e['emp_no'] . ')'; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label>Month</label>
                <select name="month" class="form-control">
                    <option value="">All Months</option>
                    <?php for ($m=1; $m<=12; $m++): ?>
                        <option value="<?php echo $m; ?>" <?php echo $month_filter == $m ? 'selected' : ''; ?>>
                            <?php echo date('F', mktime(0, 0, 0, $m, 10)); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label>Year</label>
                <select name="year" class="form-control">
                    <option value="">All Years</option>
                    <?php for ($y=date('Y')-2; $y<=date('Y')+1; $y++): ?>
                        <option value="<?php echo $y; ?>" <?php echo $year_filter == $y ? 'selected' : ''; ?>>
                            <?php echo $y; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <button type="submit" class="btn btn-secondary w-100">Filter</button>
            </div>
            <div class="col-md-2 mb-2">
                <a href="emp_slip_history.php" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </form>
    </div>

    <div class="glass-panel p-4">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Emp ID</th>
                        <th>Name</th>
                        <th>Month/Year</th>
                        <th>Working Details</th>
                        <th>Salary</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($slips_query)): ?>
                        <tr>
                            <td><?php echo $row['emp_no']; ?></td>
                            <td><?php echo $row['name']; ?><br><small class="text-muted"><?php echo $row['designation']; ?></small></td>
                            <td><?php echo date('F Y', mktime(0, 0, 0, $row['month'], 10, $row['year'])); ?></td>
                            <td>
                                <small>
                                    Total: <?php echo $row['total_days']; ?> | 
                                    Present: <?php echo $row['full_days']; ?>F / <?php echo $row['half_days']; ?>H | 
                                    Abs: <?php echo $row['absents']; ?>
                                </small>
                            </td>
                            <td><strong>₹<?php echo number_format($row['final_salary'], 2); ?></strong></td>
                            <td>
                                <div class="btn-group">
                                    <a href="emp_view_slip.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info" title="View" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="emp_view_slip.php?id=<?php echo $row['id']; ?>&download=1" class="btn btn-sm btn-success" title="Download PDF" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if (mysqli_num_rows($slips_query) == 0): ?>
                        <tr>
                            <td colspan="6" class="text-center">No history found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
