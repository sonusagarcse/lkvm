<?php
// code/manage_member.php
session_start();
require_once '../connection.php';

if (isset($_GET['flag']) && $_GET['flag'] == 'add') {
    // Collect and sanitize POST variables
    $bid = isset($_POST['bid']) ? intval($_POST['bid']) : 0;
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $dob = mysqli_real_escape_string($con, $_POST['dob']);
    $father = mysqli_real_escape_string($con, $_POST['father']);
    $mother = mysqli_real_escape_string($con, $_POST['mother']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $category = mysqli_real_escape_string($con, $_POST['category']);
    $marital_status = mysqli_real_escape_string($con, $_POST['marital_status']);
    $qualification = mysqli_real_escape_string($con, $_POST['qualification']);
    $aadhar = mysqli_real_escape_string($con, $_POST['aadhar']);
    $state = mysqli_real_escape_string($con, $_POST['state']);
    $dis = mysqli_real_escape_string($con, $_POST['dis']);
    $pincode = mysqli_real_escape_string($con, $_POST['pincode']);
    $mob = mysqli_real_escape_string($con, $_POST['mob']);
    $wat_no = mysqli_real_escape_string($con, $_POST['wat_no']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $program = isset($_POST['program']) ? intval($_POST['program']) : 0;
    $course = isset($_POST['course']) ? intval($_POST['course']) : 0;
    $pass = mysqli_real_escape_string($con, $_POST['pass']);
    $c_pass = mysqli_real_escape_string($con, $_POST['c_pass']);
    $address = mysqli_real_escape_string($con, $_POST['address']);

    // Check password match
    if ($pass !== $c_pass) {
        header("Location: ../register?msg=password_not_matched");
        exit();
    }

    // Auto-generate incrementing values
    $query_last = mysqli_query($con, "SELECT username, srno, rollno FROM registration ORDER BY id DESC LIMIT 1");
    $last_username = 1001;
    $last_srno = 1;
    $last_rollno = 1;

    if (mysqli_num_rows($query_last) > 0) {
        $last_row = mysqli_fetch_assoc($query_last);
        $last_username = intval($last_row['username']) + 1;
        $last_srno = intval($last_row['srno']) + 1;
        $last_rollno = intval($last_row['rollno']) + 1;
    }

    $asession = date('Y');
    $doj = date('Y-m-d');
    $reg_date = date('d-m-Y');
    
    // Generate unique registration number
    $regno = "LKVM1003783/" . $last_rollno . "/" . $asession;

    // Default parameters
    $section = 0;
    $mcode = '';
    $caste = '';
    $hsrollno = '';
    $pmob = 0;
    $otp = '';
    $fee = '0';
    $status = 0; // Inactive by default, pending admin approval
    $img = '';
    $signimg = '';
    $thumbimg = '';
    $idproofimg = '';
    $qualificationimg = '';
    $refer = '';
    $bfee = '250';
    $reg_type = 1;

    // Prepare INSERT statement using Prepared Statements for security
    $insert_stmt = $con->prepare("INSERT INTO registration (
        username, regno, srno, rollno, program, course, section, mcode, name, father, asession, mother, dob, 
        category, aadhar, qualification, marital_status, caste, hsrollno, pmob, doj, gender, email, mob, 
        state, dis, wat_no, pass, address, pincode, otp, fee, status, date, img, signimg, thumbimg, 
        idproofimg, qualificationimg, refer, bfee, reg_type, bid
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($insert_stmt) {
        $insert_stmt->bind_param(
            "isiiiisssssssssssssisssssssssisssssssssssii",
            $last_username, $regno, $last_srno, $last_rollno, $program, $course, $section, $mcode, $name, $father, $asession, $mother, $dob,
            $category, $aadhar, $qualification, $marital_status, $caste, $hsrollno, $pmob, $doj, $gender, $email, $mob,
            $state, $dis, $wat_no, $pass, $address, $pincode, $otp, $fee, $status, $reg_date, $img, $signimg, $thumbimg,
            $idproofimg, $qualificationimg, $refer, $bfee, $reg_type, $bid
        );

        if ($insert_stmt->execute()) {
            header("Location: ../register?msg=send");
            exit();
        } else {
            // Echo database error if failed to insert
            echo "Error: " . $insert_stmt->error;
        }
        $insert_stmt->close();
    } else {
        echo "Prepare failed: " . $con->error;
    }
}
?>
