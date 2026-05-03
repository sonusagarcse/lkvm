<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../connection.php';

$message = '';
$messageType = '';

$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$emp_id_filter = isset($_GET['emp_id']) ? mysqli_real_escape_string($con, $_GET['emp_id']) : '';

if (isset($_POST['generate_slips'])) {
    $sel_month = intval($_POST['month']);
    $sel_year = intval($_POST['year']);
    $sel_emp_id = mysqli_real_escape_string($con, $_POST['emp_id']);
    
    $days_in_month = cal_days_in_month(CAL_GREGORIAN, $sel_month, $sel_year);
    
    // Fetch divisor from settings
    $set_q = mysqli_query($con, "SELECT salary_divisor FROM emp_settings LIMIT 1");
    $set_r = mysqli_fetch_assoc($set_q);
    $divisor = ($set_r && $set_r['salary_divisor'] > 0) ? intval($set_r['salary_divisor']) : 30;
    
    // Auto-calculate Sundays and Official Holidays for this generation
    $sel_holidays = 0;
    for ($d = 1; $d <= $days_in_month; $d++) {
        if (date('N', mktime(0, 0, 0, $sel_month, $d, $sel_year)) == 7) $sel_holidays++;
    }
    $h_start = "$sel_year-" . str_pad($sel_month, 2, '0', STR_PAD_LEFT) . "-01";
    $h_end = "$sel_year-" . str_pad($sel_month, 2, '0', STR_PAD_LEFT) . "-$days_in_month";
    $h_q = mysqli_query($con, "SELECT COUNT(*) as count FROM emp_holidays WHERE holiday_date BETWEEN '$h_start' AND '$h_end'");
    $h_r = mysqli_fetch_assoc($h_q);
    $sel_holidays += $h_r['count'];
    
    $emp_query_str = "SELECT * FROM employees";
    if ($sel_emp_id != '') {
        $emp_query_str .= " WHERE emp_no = '$sel_emp_id'";
    }
    $employees = mysqli_query($con, $emp_query_str);
    $generated = 0;
    
    while ($emp = mysqli_fetch_assoc($employees)) {
        $emp_no = $emp['emp_no'];
        $base_salary = $emp['base_salary'];
        
        $start_date = "$sel_year-" . str_pad($sel_month, 2, '0', STR_PAD_LEFT) . "-01";
        $end_date = "$sel_year-" . str_pad($sel_month, 2, '0', STR_PAD_LEFT) . "-$days_in_month";
        
        $att_query = mysqli_query($con, "SELECT status, COUNT(*) as count FROM emp_daily_attendance WHERE emp_no='$emp_no' AND log_date BETWEEN '$start_date' AND '$end_date' GROUP BY status");
        
        $full_days = 0;
        $half_days = 0;
        $absents = 0;
        $present_days_logged = 0;
        
        while ($att = mysqli_fetch_assoc($att_query)) {
            if ($att['status'] == 'Full Day') $full_days = $att['count'];
            if ($att['status'] == 'Half Day') $half_days = $att['count'];
            if ($att['status'] == 'Absent') $absents = $att['count'];
            $present_days_logged += $att['count'];
        }
        
        // Calculate paid days (Present + Holidays)
        $paid_days = $full_days + ($half_days * 0.5) + $sel_holidays;
        if ($paid_days > $days_in_month) {
            $paid_days = $days_in_month;
        }
        
        // Absents are the days not paid for
        $absents = $days_in_month - $paid_days;
        if ($absents < 0) {
            $absents = 0;
        }
        
        $per_day_salary = $base_salary / $divisor;
        $final_salary = round($paid_days * $per_day_salary, 2);
        
        // Check if slip already exists
        $check = mysqli_query($con, "SELECT id FROM emp_salary_slips WHERE emp_no='$emp_no' AND month=$sel_month AND year=$sel_year");
        if (mysqli_num_rows($check) > 0) {
            $slip_id = mysqli_fetch_assoc($check)['id'];
            mysqli_query($con, "UPDATE emp_salary_slips SET total_days=$days_in_month, full_days=$full_days, half_days=$half_days, absents=$absents, final_salary=$final_salary WHERE id=$slip_id");
        } else {
            mysqli_query($con, "INSERT INTO emp_salary_slips (emp_no, month, year, total_days, full_days, half_days, absents, final_salary) VALUES ('$emp_no', $sel_month, $sel_year, $days_in_month, $full_days, $half_days, $absents, $final_salary)");
        }
        $generated++;
    }
    
    $message = "Salary slips generated/updated for $generated employees for " . date('F', mktime(0, 0, 0, $sel_month, 10)) . " $sel_year.";
    header("Location: emp_salary_slips.php?month=$sel_month&year=$sel_year&emp_id=$sel_emp_id&msg=" . urlencode($message));
    exit();
}

if (isset($_GET['msg'])) {
    $message = $_GET['msg'];
    $messageType = "success";
}

// Fetch slips for selected month
$slips_query_str = "SELECT s.*, e.name, e.designation, e.base_salary FROM emp_salary_slips s JOIN employees e ON s.emp_no = e.emp_no WHERE month=$month AND year=$year";
if ($emp_id_filter != '') {
    $slips_query_str .= " AND s.emp_no='$emp_id_filter'";
}
$slips_query = mysqli_query($con, $slips_query_str);

// Fetch all employees for dropdown
$all_employees = mysqli_query($con, "SELECT emp_no, name FROM employees ORDER BY name ASC");

// Auto-calculate Sundays and Holidays for the selected month
$auto_holidays = 0;
$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// 1. Count Sundays
for ($d = 1; $d <= $days_in_month; $d++) {
    $time = mktime(0, 0, 0, $month, $d, $year);
    if (date('N', $time) == 7) { // 7 = Sunday
        $auto_holidays++;
    }
}

// 2. Count Official Holidays from DB
$start_date = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
$end_date = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-$days_in_month";
$h_query = mysqli_query($con, "SELECT COUNT(*) as count FROM emp_holidays WHERE holiday_date BETWEEN '$start_date' AND '$end_date'");
$h_data = mysqli_fetch_assoc($h_query);
$auto_holidays += $h_data['count'];

include 'includes/header.php';
?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-file-invoice-dollar me-2"></i>Monthly Salary Slips</h2>
            <a href="emp_slip_history.php" class="btn btn-outline-primary">
                <i class="fas fa-history me-2"></i>Slip History
            </a>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="glass-panel p-4 mb-4">
            <div class="row align-items-end">
                <!-- SINGLE FORM FOR BOTH VIEW AND GENERATE -->
                <div class="col-md-12">
                    <form method="GET" id="salaryForm" class="row align-items-end">
                        <div class="col-md-3 mb-2">
                            <label>Employee</label>
                            <select name="emp_id" id="emp_id_select" class="form-control">
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
                            <select name="month" id="month_select" class="form-control">
                                <?php for ($m=1; $m<=12; $m++): ?>
                                    <option value="<?php echo $m; ?>" <?php echo $month == $m ? 'selected' : ''; ?>>
                                        <?php echo date('F', mktime(0, 0, 0, $m, 10)); ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label>Year</label>
                            <select name="year" id="year_select" class="form-control">
                                <?php for ($y=date('Y')-2; $y<=date('Y')+1; $y++): ?>
                                    <option value="<?php echo $y; ?>" <?php echo $year == $y ? 'selected' : ''; ?>>
                                        <?php echo $y; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <button type="submit" class="btn btn-secondary w-100">View Slips</button>
                        </div>
                        
                        <!-- GENERATE TRIGGER -->
                        <div class="col-md-3 mb-2">
                            <button type="button" onclick="triggerGenerate()" class="btn btn-primary glass-btn w-100">
                                <i class="fas fa-cogs me-2"></i>Generate Slips
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Hidden Generation Form -->
        <form id="genForm" method="POST" style="display:none;">
            <input type="hidden" name="generate_slips" value="1">
            <input type="hidden" name="emp_id" id="gen_emp_id">
            <input type="hidden" name="month" id="gen_month">
            <input type="hidden" name="year" id="gen_year">
        </form>

        <script>
        function triggerGenerate() {
            document.getElementById('gen_emp_id').value = document.getElementById('emp_id_select').value;
            document.getElementById('gen_month').value = document.getElementById('month_select').value;
            document.getElementById('gen_year').value = document.getElementById('year_select').value;
            
            const empName = document.getElementById('emp_id_select').options[document.getElementById('emp_id_select').selectedIndex].text;
            const monthName = document.getElementById('month_select').options[document.getElementById('month_select').selectedIndex].text;
            
            if(confirm('Generate/Update salary slip for ' + empName + ' for ' + monthName + '?')) {
                document.getElementById('genForm').submit();
            }
        }
        </script>
        
        <div class="glass-panel p-4">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Emp ID</th>
                            <th>Name</th>
                            <th>Month</th>
                            <th>Total Days</th>
                            <th>Present (Full/Half)</th>
                            <th>Absent</th>
                            <th>Base Salary</th>
                            <th>Final Salary</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($slips_query)): ?>
                            <tr>
                                <td><?php echo $row['emp_no']; ?></td>
                                <td><?php echo $row['name']; ?><br><small class="text-muted"><?php echo $row['designation']; ?></small></td>
                                <td><?php echo date('M Y', mktime(0, 0, 0, $row['month'], 10, $row['year'])); ?></td>
                                <td><?php echo $row['total_days']; ?></td>
                                <td><span class="text-success"><?php echo $row['full_days']; ?></span> / <span class="text-warning"><?php echo $row['half_days']; ?></span></td>
                                <td><span class="text-danger"><?php echo $row['absents']; ?></span></td>
                                <td>₹<?php echo number_format($row['base_salary'], 2); ?></td>
                                <td><strong>₹<?php echo number_format($row['final_salary'], 2); ?></strong></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="emp_view_slip.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="emp_view_slip.php?id=<?php echo $row['id']; ?>&download=1" class="btn btn-sm btn-success" target="_blank">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if (mysqli_num_rows($slips_query) == 0): ?>
                            <tr>
                                <td colspan="8" class="text-center">No salary slips found for this month. Click "Generate Slips".</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

<?php include 'includes/footer.php'; ?>
