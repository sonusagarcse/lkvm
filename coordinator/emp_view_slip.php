<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../connection.php';

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$slip_id = intval($_GET['id']);

// Fetch slip and employee details
$query = mysqli_query($con, "SELECT s.*, e.name, e.designation, e.base_salary FROM emp_salary_slips s JOIN employees e ON s.emp_no = e.emp_no WHERE s.id=$slip_id");
$slip = mysqli_fetch_assoc($query);

if (!$slip) {
    die("Salary slip not found.");
}

// Fetch settings
$settings_query = mysqli_query($con, "SELECT * FROM emp_settings LIMIT 1");
$settings = mysqli_fetch_assoc($settings_query);

$bg_image = $settings['slip_bg_image'] ?? '';
$positions = $settings['slip_text_positions'] ? json_decode($settings['slip_text_positions'], true) : [];
$defaultFields = ['EmpNo', 'Name', 'Designation', 'MonthYear', 'TotalDays', 'FullDays', 'HalfDays', 'Absents', 'FinalSalary'];

// Ensure all default fields exist in positions (in case they haven't saved them yet)
foreach ($defaultFields as $field) {
    if (!isset($positions[$field])) {
        $positions[$field] = ['x' => 10, 'y' => 10, 'fontSize' => 16, 'color' => '#000000'];
    } else {
        // Fix legacy pixel coordinates that push elements off-screen
        if (isset($positions[$field]['x']) && $positions[$field]['x'] > 100) $positions[$field]['x'] = 10;
        if (isset($positions[$field]['y']) && $positions[$field]['y'] > 100) $positions[$field]['y'] = 10;
    }
}

if (!$bg_image) {
    die("No background template configured. Please setup in Employee Settings.");
}

$monthName = date('F', mktime(0, 0, 0, $slip['month'], 10));
$monthYear = $monthName . " " . $slip['year'];

// Map the dynamic data to the fields
$dataMap = [
    'EmpNo' => $slip['emp_no'],
    'Name' => $slip['name'],
    'Designation' => $slip['designation'],
    'MonthYear' => $monthYear,
    'TotalDays' => $slip['total_days'],
    'FullDays' => $slip['full_days'],
    'HalfDays' => $slip['half_days'],
    'Absents' => $slip['absents'],
    'FinalSalary' => "Rs. " . number_format($slip['final_salary'], 2)
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Slip - <?php echo $slip['name']; ?></title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            font-family: Arial, sans-serif;
        }
        .slip-container {
            position: relative;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background: white;
            display: inline-block;
        }
        .slip-bg {
            display: block;
            max-width: 100%;
        }
        .slip-text {
            position: absolute;
            white-space: nowrap;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .slip-container {
                box-shadow: none;
            }
            .no-print {
                display: none;
            }
        }
        .controls {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            border-radius: 5px;
            z-index: 1000;
        }
        .btn {
            padding: 10px 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background: #0056b3;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body>

    <div class="controls no-print">
        <button class="btn" onclick="window.print()" style="margin-bottom: 10px; width: 100%;">Print Slip</button>
        <a href="emp_salary_slips.php" class="btn" style="background: #6c757d; display: block; text-align: center;">Back</a>
    </div>

    <script>
        function downloadPDF() {
            var element = document.querySelector('.slip-container');
            var opt = {
                margin:       0,
                filename:     'Salary_Slip_<?php echo str_replace(' ', '_', $slip['name']); ?>.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'px', format: [element.offsetWidth, element.offsetHeight], orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save().then(function() {
                <?php if (isset($_GET['download']) && $_GET['download'] == 1): ?>
                    window.close();
                <?php endif; ?>
            });
        }

        window.onload = function() {
            <?php if (isset($_GET['download']) && $_GET['download'] == 1): ?>
                setTimeout(downloadPDF, 1500); // 1.5s for rendering
            <?php endif; ?>
        }
    </script>

    <div class="slip-container">
        <img src="../images/<?php echo $bg_image; ?>" class="slip-bg" alt="Salary Slip Template">
        
        <?php foreach ($positions as $field => $style): ?>
            <?php if (isset($dataMap[$field])): ?>
                <div class="slip-text" style="
                    left: <?php echo $style['x']; ?>%; 
                    top: <?php echo $style['y']; ?>%; 
                    font-size: <?php echo isset($style['fontSize']) ? $style['fontSize'] : 16; ?>px; 
                    color: <?php echo isset($style['color']) ? $style['color'] : '#000000'; ?>;
                    ">
                    <?php echo $dataMap[$field]; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

</body>
</html>
