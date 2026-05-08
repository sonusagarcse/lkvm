<?php
session_start();
if (!isset($_SESSION['coord_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../connection.php';

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$slip_id = intval($_GET['id']);

// Fetch slip and employee details
$query = mysqli_query($con, "SELECT s.*, e.name, e.designation, e.base_salary, e.emp_no as employee_number FROM emp_salary_slips s JOIN employees e ON s.emp_no = e.emp_no WHERE s.id=$slip_id");
$slip = mysqli_fetch_assoc($query);

if (!$slip) {
    die("Salary slip not found.");
}

$monthName = date('F', mktime(0, 0, 0, $slip['month'], 10));
$monthYear = $monthName . " " . $slip['year'];

// Fetch Organization Details from Global Settings (defined in connection.php)
$companyName = !empty($SITE_NAME) ? $SITE_NAME : "Lok Kala Vikas Manch";
$companyAddress = !empty($ADDRESS) ? $ADDRESS : "Lok Kala Bhawan, gewalganj Imamganj Gaya ji Bihar - 824206";
$companyEmail = !empty($CONTACT_EMAIL) ? $CONTACT_EMAIL : "info@lkvmbihar.in";
$companyPhone = !empty($CONTACT_MOBILE) ? $CONTACT_MOBILE : "+91 9523012888";

// Fallback to Branch details if global settings are empty (optional, but keep it robust)
$coord_id = $_SESSION['coord_id'];
$branch_query = mysqli_query($con, "SELECT b.* FROM branch b JOIN admin_login a ON b.id = a.bid WHERE a.id = $coord_id");
$branch = mysqli_fetch_assoc($branch_query);

if ($branch && empty($SITE_NAME)) {
    $companyName = $branch['bname'];
    $companyAddress = $branch['baddress'];
    $companyEmail = $branch['bemail'];
    $companyPhone = $branch['bcontact'];
}

// Fetch Salary Settings
$settings_query = mysqli_query($con, "SELECT * FROM emp_settings LIMIT 1");
$emp_settings = mysqli_fetch_assoc($settings_query);
$template_type = $emp_settings['slip_template_type'] ?? 'Default';

$logoPath = "../images/Logo 1.png"; // Default logo

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Slip - <?php echo $slip['name']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --secondary: #64748b;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --success: #10b981;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            line-height: 1.5;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .no-print {
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: white;
            color: var(--text-main);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background-color: #f1f5f9;
        }

        /* Salary Slip Card */
        .salary-slip {
            background: var(--card-bg);
            width: 100%;
            max-width: 800px;
            padding: 50px;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70%;
            opacity: 0.02;
            z-index: 0;
            pointer-events: none;
        }

        .salary-slip::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--primary), #6366f1);
            z-index: 2;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 25px;
        }

        .company-info h1 {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary-dark);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .company-info p {
            font-size: 13px;
            color: var(--text-muted);
        }

        .slip-title {
            text-align: right;
        }

        .slip-title h2 {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 4px;
        }

        .slip-title p {
            font-size: 14px;
            color: var(--primary);
            font-weight: 600;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-bottom: 40px;
            background: rgba(248, 250, 252, 0.8);
            padding: 20px;
            border-radius: 12px;
            backdrop-filter: blur(4px);
        }

        .info-item label {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 4px;
        }

        .info-item span {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-main);
        }

        /* Salary Table */
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        .salary-table th {
            text-align: left;
            padding: 12px 15px;
            background: rgba(241, 245, 249, 0.8);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
        }

        .salary-table td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .salary-table tr:last-child td {
            border-bottom: none;
        }

        .amount-col {
            text-align: right;
            font-weight: 600;
        }

        /* Summary Section */
        .summary-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 20px;
        }

        .attendance-summary {
            flex: 1;
        }

        .attendance-summary h3 {
            font-size: 14px;
            margin-bottom: 10px;
            color: var(--text-muted);
        }

        .attendance-tags {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .tag {
            padding: 4px 10px;
            background: rgba(241, 245, 249, 0.8);
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            color: var(--text-main);
        }

        .final-amount {
            text-align: right;
            background: var(--primary);
            color: white;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .final-amount label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            opacity: 0.9;
            margin-bottom: 4px;
        }

        .final-amount span {
            font-size: 28px;
            font-weight: 800;
        }

        .footer {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            border-top: 1px solid var(--text-main);
            margin-bottom: 8px;
        }

        .signature-box p {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .no-print {
                display: none;
            }

            .salary-slip {
                box-shadow: none;
                border: none;
                padding: 30px;
                max-width: 100%;
            }

            .final-amount {
                background: #f1f5f9 !important;
                color: black !important;
                box-shadow: none;
                border: 1px solid #e2e8f0;
            }

            .watermark {
                opacity: 0.1 !important;
                /* Lighter for print */
            }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>

<body>

    <div class="no-print">
        <a href="emp_salary_slips.php" class="btn btn-secondary">← Back to List</a>
        <button class="btn btn-primary" onclick="window.print()">Print Slip</button>
        <button class="btn btn-primary" style="background: #10b981;" onclick="downloadPDF()">Download PDF</button>
    </div>

    <div class="salary-slip" id="slip-content">
        <?php if ($template_type == 'Custom' && $emp_settings['slip_bg_image']):
            $positions = json_decode($emp_settings['slip_text_positions'], true);
            $fieldData = [
                'EmpNo' => $slip['employee_number'],
                'Name' => $slip['name'],
                'Designation' => $slip['designation'],
                'MonthYear' => $monthYear,
                'TotalDays' => $slip['total_days'],
                'FullDays' => $slip['full_days'],
                'HalfDays' => $slip['half_days'],
                'Absents' => $slip['absents'],
                'FinalSalary' => '₹ ' . number_format($slip['final_salary'], 2)
            ];
            ?>
            <!-- CUSTOM IMAGE OVERLAY TEMPLATE -->
            <div style="position: relative; display: inline-block; width: 100%;">
                <img src="../images/<?php echo $emp_settings['slip_bg_image']; ?>" style="width: 100%; display: block;">
                <?php foreach ($positions as $field => $style):
                    if (isset($fieldData[$field])): ?>
                        <div style="position: absolute; 
                                left: <?php echo $style['x']; ?>%; 
                                top: <?php echo $style['y']; ?>%; 
                                font-size: <?php echo $style['fontSize']; ?>px; 
                                color: <?php echo $style['color']; ?>; 
                                font-weight: bold;
                                transform: translate(0, 0);">
                            <?php echo $fieldData[$field]; ?>
                        </div>
                    <?php endif; endforeach; ?>
            </div>

        <?php else: ?>
            <!-- DEFAULT MODERN CSS TEMPLATE -->
            <!-- Watermark Logo -->
            <?php if (file_exists($logoPath)): ?>
                <img src="<?php echo $logoPath; ?>" alt="Watermark" class="watermark">
            <?php endif; ?>

            <div class="content-wrapper">
                <div class="header">
                    <div class="company-info">
                        <h1><?php echo $companyName; ?></h1>
                        <p><?php echo $companyAddress; ?></p>
                        <p>Email: <?php echo $companyEmail; ?> | Phone: <?php echo $companyPhone; ?></p>
                    </div>
                    <div class="slip-title">
                        <h2>SALARY SLIP</h2>
                        <p><?php echo $monthYear; ?></p>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <label>Employee Name</label>
                        <span><?php echo $slip['name']; ?></span>
                    </div>
                    <div class="info-item">
                        <label>Employee ID</label>
                        <span>#<?php echo $slip['employee_number']; ?></span>
                    </div>
                    <div class="info-item">
                        <label>Designation</label>
                        <span><?php echo $slip['designation']; ?></span>
                    </div>
                </div>

                <table class="salary-table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th class="amount-col">Amount (INR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Basic Monthly Salary</td>
                            <td class="amount-col">₹ <?php echo number_format($slip['base_salary'], 2); ?></td>
                        </tr>
                        <tr>
                            <td>Attendance Based Calculation</td>
                            <td class="amount-col">₹ <?php echo number_format($slip['final_salary'], 2); ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="summary-section">
                    <div class="attendance-summary">
                        <h3>Attendance Summary</h3>
                        <div class="attendance-tags">
                            <div class="tag">Total Days: <?php echo $slip['total_days']; ?></div>
                            <div class="tag">Full Days: <?php echo $slip['full_days']; ?></div>
                            <div class="tag">Half Days: <?php echo $slip['half_days']; ?></div>
                            <div class="tag" style="background: #fee2e2; color: #991b1b;">Absents:
                                <?php echo $slip['absents']; ?>
                            </div>
                        </div>
                    </div>
                    <div class="final-amount">
                        <label>Net Payable Amount</label>
                        <span>₹ <?php echo number_format($slip['final_salary'], 2); ?></span>
                    </div>
                </div>

                <div class="footer">
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <p>Employee Signature</p>
                    </div>
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <p>Authorized Signatory</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function downloadPDF() {
            const element = document.getElementById('slip-content');
            const opt = {
                margin: 0,
                filename: 'Salary_Slip_<?php echo str_replace(' ', '_', $slip['name']); ?>_<?php echo $monthYear; ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>

</body>

</html>