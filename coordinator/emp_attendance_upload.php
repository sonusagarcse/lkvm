<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../connection.php';

// Fetch settings
$settings_query = mysqli_query($con, "SELECT * FROM emp_settings LIMIT 1");
$settings = mysqli_fetch_assoc($settings_query);
$full_day_hours = $settings['full_day_hours'] ?? 8.00;
$half_day_hours = $settings['half_day_hours'] ?? 4.00;

$message = '';
$messageType = '';
$logs_inserted = 0;
$days_processed = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['attendance_file'])) {
    $file = $_FILES['attendance_file']['tmp_name'];
    
    if ($file) {
        $content = file_get_contents($file);
        // Convert UTF-16LE to UTF-8
        $content = mb_convert_encoding($content, 'UTF-8', 'UTF-16LE');
        
        $lines = explode("\n", $content);
        $header_skipped = false;
        
        $dates_to_process = []; // To keep track of which emp/dates need calculation
        
        mysqli_begin_transaction($con);
        try {
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;
                
                $columns = explode("\t", $line);
                
                // Skip header
                if (!$header_skipped) {
                    if (isset($columns[0]) && $columns[0] == 'No') {
                        $header_skipped = true;
                        continue;
                    }
                }
                
                // Expecting at least 10 columns based on ALOG_011.txt
                if (count($columns) >= 10) {
                    $emp_no = mysqli_real_escape_string($con, trim($columns[2]));
                    $in_out = intval(trim($columns[6]));
                    $datetime = mysqli_real_escape_string($con, trim($columns[9]));
                    
                    // Basic validation
                    if ($emp_no !== '' && strtotime($datetime)) {
                        $log_year = date('Y', strtotime($datetime));
                        $current_year = date('Y');
                        
                        // Only process records for the current year
                        if ($log_year == $current_year) {
                            $date_only = date('Y-m-d', strtotime($datetime));
                            
                            // Insert raw log (IGNORE to prevent duplicate logs)
                            $sql = "INSERT IGNORE INTO emp_attendance_logs (emp_no, log_time, in_out) VALUES ('$emp_no', '$datetime', $in_out)";
                            mysqli_query($con, $sql);
                            
                            if (mysqli_affected_rows($con) > 0) {
                                $logs_inserted++;
                            }
                            
                            // Mark this emp and date for processing
                            $dates_to_process[$emp_no][$date_only] = true;
                        }
                    }
                }
            }
            
            // Now calculate daily attendance for the affected dates
            foreach ($dates_to_process as $emp_no => $dates) {
                foreach ($dates as $date => $val) {
                    // Get all logs for this employee on this date
                    // Get earliest and latest logs for this employee on this date
                    $logs_query = mysqli_query($con, "SELECT MIN(log_time) as first_log, MAX(log_time) as last_log, COUNT(*) as log_count FROM emp_attendance_logs WHERE emp_no='$emp_no' AND DATE(log_time) = '$date'");
                    $log_data = mysqli_fetch_assoc($logs_query);
                    
                    $first_time = $log_data['first_log'] ? strtotime($log_data['first_log']) : null;
                    $last_time = $log_data['last_log'] ? strtotime($log_data['last_log']) : null;
                    $num_logs = intval($log_data['log_count']);
                    
                    $total_hours = 0.00;
                    if ($num_logs > 1 && $last_time > $first_time) {
                        $diff_seconds = $last_time - $first_time;
                        $total_hours = round($diff_seconds / 3600, 2);
                    }
                    
                    // Determine status
                    $num_logs = mysqli_num_rows($logs_query);
                    $status = 'Absent';
                    if ($total_hours >= $full_day_hours) {
                        $status = 'Full Day';
                    } elseif ($total_hours >= $half_day_hours) {
                        $status = 'Half Day';
                    } elseif ($num_logs == 1) {
                        // Single punch rule: Automatically mark as Half Day
                        $status = 'Half Day';
                        if ($total_hours == 0) $total_hours = $half_day_hours; 
                    } else {
                        $status = 'Absent';
                    }
                    
                    // Insert or Update daily attendance
                    $upsert = "INSERT INTO emp_daily_attendance (emp_no, log_date, total_hours, status) 
                               VALUES ('$emp_no', '$date', '$total_hours', '$status') 
                               ON DUPLICATE KEY UPDATE total_hours=VALUES(total_hours), status=VALUES(status)";
                    mysqli_query($con, $upsert);
                    $days_processed++;
                }
            }
            
            mysqli_commit($con);
            
            $message = "File processed successfully! $logs_inserted new punches recorded. $days_processed daily records updated.";
            $messageType = "success";
            
        } catch (Exception $e) {
            mysqli_rollback($con);
            $message = "Error processing file: " . $e->getMessage();
            $messageType = "danger";
        }
    } else {
        $message = "Failed to upload file.";
        $messageType = "danger";
    }
}

include 'includes/header.php';
?>
        <h2 class="mb-4"><i class="fas fa-file-upload me-2"></i>Upload Attendance</h2>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="glass-panel p-4 h-100">
                    <h4>Upload Machine Log (ALOG_011.txt)</h4>
                    <hr>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="attendance_file" class="form-label">Select Text File</label>
                            <input class="form-control" type="file" id="attendance_file" name="attendance_file" accept=".txt" required>
                        </div>
                        <button type="submit" class="btn btn-primary glass-btn"><i class="fas fa-upload me-2"></i>Process Attendance</button>
                    </form>
                    
                    <div class="mt-4">
                        <h6>Current Rules:</h6>
                        <ul>
                            <li><strong>Full Day:</strong> &ge; <?php echo $full_day_hours; ?> hours</li>
                            <li><strong>Half Day:</strong> &ge; <?php echo $half_day_hours; ?> hours</li>
                        </ul>
                        <small class="text-muted">Salary calculation rules are managed by the Super Admin.</small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="glass-panel p-4 h-100">
                    <h4>Instructions</h4>
                    <hr>
                    <ol>
                        <li>Export the attendance log from your biometric machine. It should be a tab-separated text file (e.g., <code>ALOG_011.txt</code>).</li>
                        <li>Ensure the file contains the headers: <code>No, TMNo, EnNo, Name, GMNo, Mode, In/Out, Antipass, ProxyWork, DateTime</code>.</li>
                        <li>Make sure employees are added in <strong>Employee Manage</strong> with the matching <code>EnNo</code> before generating salary slips.</li>
                        <li>The system calculates total hours by subtracting the first 'In' punch from the last 'Out' punch of the day.</li>
                        <li>You can upload the same file multiple times; the system will safely ignore duplicate records.</li>
                    </ol>
                </div>
            </div>
        </div>

<?php include 'includes/footer.php'; ?>
