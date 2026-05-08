<?php
// admin/add_student.php
require_once '../connection.php';
include 'includes/header.php';

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $status = isset($_POST['status']) ? intval($_POST['status']) : 1;

    // Handle Image Upload
    $img_name = '';
    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['img']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $img_name = time() . '_' . rand(1000, 9999) . '.' . $ext;
            $upload_path = '../images/' . $img_name;
            if (!move_uploaded_file($_FILES['img']['tmp_name'], $upload_path)) {
                $img_name = '';
            }
        }
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
            $state, $dis, $wat_no, $pass, $address, $pincode, $otp, $fee, $status, $reg_date, $img_name, $signimg, $thumbimg,
            $idproofimg, $qualificationimg, $refer, $bfee, $reg_type, $bid
        );

        if ($insert_stmt->execute()) {
            $success = "Student Registered Successfully! Registration Number: <strong class='text-primary'>$regno</strong>. Username/Login roll: <strong class='text-primary'>$last_username</strong>";
        } else {
            $error = "Error: " . $insert_stmt->error;
        }
        $insert_stmt->close();
    } else {
        $error = "Prepare failed: " . $con->error;
    }
}
?>

<div class="row">
    <div class="col-12 col-xl-10 mx-auto">
        <!-- Breadcrumb / Header -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="students.php">Students</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Student</li>
                    </ol>
                </nav>
                <h3 class="h3 m-0 fw-bold text-dark"><i class="fas fa-user-plus me-2 text-primary"></i>Register New Student</h3>
            </div>
            <a href="students.php" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm fw-semibold">
                <i class="fas fa-arrow-left me-2"></i>Back to Student List
            </a>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success border-0 shadow-sm py-3 px-4 rounded-4 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-2x me-3 text-success"></i>
                    <div>
                        <h5 class="alert-heading mb-1 fw-bold">Success!</h5>
                        <p class="m-0"><?php echo $success; ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger border-0 shadow-sm py-3 px-4 rounded-4 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-times-circle fa-2x me-3 text-danger"></i>
                    <div>
                        <h5 class="alert-heading mb-1 fw-bold">Error Occurred</h5>
                        <p class="m-0"><?php echo $error; ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Registration Form Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-primary text-white py-3 px-4 d-flex align-items-center">
                <i class="fas fa-address-card fa-lg me-2"></i>
                <h5 class="card-title m-0 fw-bold">Student Registration Details</h5>
            </div>
            <div class="card-body p-4 p-md-5">
                <form method="POST" action="" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <!-- Section: Academic Details -->
                    <h5 class="fw-bold text-primary mb-3 border-bottom pb-2">
                        <i class="fas fa-graduation-cap me-2"></i>Academic Details
                    </h5>
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Branch <span class="text-danger">*</span></label>
                            <select name="bid" class="form-select py-2.5 rounded-3 border-secondary-subtle" required>
                                <option value="">--Select Branch--</option>
                                <?php
                                $sel_b = mysqli_query($con, "SELECT * FROM branch ORDER BY id DESC");
                                while ($bres = mysqli_fetch_assoc($sel_b)) {
                                    echo "<option value='{$bres['id']}'>({$bres['bcode']}) {$bres['bname']} - {$bres['dis']}</option>";
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">Please select a branch.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Program Category <span class="text-danger">*</span></label>
                            <select name="program" id="program" class="form-select py-2.5 rounded-3 border-secondary-subtle" onChange="FetchCourse(this.value)" required>
                                <option value="">--Select Program--</option>
                                <?php
                                $sel_p = mysqli_query($con, "SELECT * FROM course_category ORDER BY id DESC");
                                while ($pres = mysqli_fetch_assoc($sel_p)) {
                                    echo "<option value='{$pres['id']}'>{$pres['name']}</option>";
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">Please select a program category.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Course <span class="text-danger">*</span></label>
                            <select name="course" id="course" class="form-select py-2.5 rounded-3 border-secondary-subtle" required>
                                <option value="">--Select Course--</option>
                            </select>
                            <div class="invalid-feedback">Please select a course.</div>
                        </div>
                    </div>

                    <!-- Section: Personal Details -->
                    <h5 class="fw-bold text-primary mb-3 border-bottom pb-2 mt-5">
                        <i class="fas fa-user me-2"></i>Personal Information
                    </h5>
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control py-2.5 rounded-3 border-secondary-subtle" placeholder="Student's Name" name="name" required>
                            <div class="invalid-feedback">Please enter the student's name.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" class="form-control py-2.5 rounded-3 border-secondary-subtle" name="dob" required>
                            <div class="invalid-feedback">Please enter Date of Birth.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select py-2.5 rounded-3 border-secondary-subtle" required>
                                <option value="">--Select Gender--</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="invalid-feedback">Please select gender.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Father's Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control py-2.5 rounded-3 border-secondary-subtle" placeholder="Father's Name" name="father" required>
                            <div class="invalid-feedback">Please enter father's name.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Mother's Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control py-2.5 rounded-3 border-secondary-subtle" placeholder="Mother's Name" name="mother" required>
                            <div class="invalid-feedback">Please enter mother's name.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select py-2.5 rounded-3 border-secondary-subtle" required>
                                <option value="">--Select Category--</option>
                                <option value="GEN">GEN</option>
                                <option value="OBC">OBC</option>
                                <option value="SC">SC</option>
                                <option value="ST">ST</option>
                            </select>
                            <div class="invalid-feedback">Please select a category.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Marital Status <span class="text-danger">*</span></label>
                            <select name="marital_status" class="form-select py-2.5 rounded-3 border-secondary-subtle" required>
                                <option value="">--Select Marital Status--</option>
                                <option value="Married">Married</option>
                                <option value="Unmarried">Unmarried</option>
                            </select>
                            <div class="invalid-feedback">Please select marital status.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Last Qualification <span class="text-danger">*</span></label>
                            <select name="qualification" class="form-select py-2.5 rounded-3 border-secondary-subtle" required>
                                <option value="">--Select Qualification--</option>
                                <option value="Below 8th">Below 8th</option>
                                <option value="8th">8th</option>
                                <option value="9th">9th</option>
                                <option value="10th">10th</option>
                                <option value="11th">11th</option>
                                <option value="12th">12th</option>
                                <option value="Graduation">Graduation</option>
                                <option value="Post Graduation">Post Graduation</option>
                                <option value="Diploma">Diploma</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="invalid-feedback">Please select qualification.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Aadhar Number (12 digits)</label>
                            <input type="text" class="form-control py-2.5 rounded-3 border-secondary-subtle" placeholder="12-digit Aadhar Number" name="aadhar" maxlength="12" pattern="\d{12}">
                            <div class="invalid-feedback">Please enter a valid 12-digit Aadhar number.</div>
                        </div>
                    </div>

                    <!-- Section: Contact Details -->
                    <h5 class="fw-bold text-primary mb-3 border-bottom pb-2 mt-5">
                        <i class="fas fa-phone me-2"></i>Contact Information
                    </h5>
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control py-2.5 rounded-3 border-secondary-subtle" placeholder="Mobile Number" name="mob" required>
                            <div class="invalid-feedback">Please enter a valid mobile number.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">WhatsApp Number</label>
                            <input type="text" class="form-control py-2.5 rounded-3 border-secondary-subtle" placeholder="WhatsApp Number" name="wat_no">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" class="form-control py-2.5 rounded-3 border-secondary-subtle" placeholder="student@example.com" name="email">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">State <span class="text-danger">*</span></label>
                            <select name="state" class="form-select py-2.5 rounded-3 border-secondary-subtle" required>
                                <option value="">--Select State--</option>
                                <option value="Andhra Pradesh">Andhra Pradesh</option>
                                <option value="Assam">Assam</option>
                                <option value="Bihar" selected>Bihar</option>
                                <option value="Chhattisgarh">Chhattisgarh</option>
                                <option value="Delhi">Delhi</option>
                                <option value="Gujarat">Gujarat</option>
                                <option value="Haryana">Haryana</option>
                                <option value="Jharkhand">Jharkhand</option>
                                <option value="Madhya Pradesh">Madhya Pradesh</option>
                                <option value="Maharashtra">Maharashtra</option>
                                <option value="Rajasthan">Rajasthan</option>
                                <option value="Uttar Pradesh">Uttar Pradesh</option>
                                <option value="West Bengal">West Bengal</option>
                            </select>
                            <div class="invalid-feedback">Please select state.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">District <span class="text-danger">*</span></label>
                            <input type="text" class="form-control py-2.5 rounded-3 border-secondary-subtle" placeholder="District" name="dis" value="GAYA" required>
                            <div class="invalid-feedback">Please enter district.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Pincode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control py-2.5 rounded-3 border-secondary-subtle" placeholder="Pincode" name="pincode" required>
                            <div class="invalid-feedback">Please enter pincode.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Complete Address <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control rounded-3 border-secondary-subtle" rows="3" placeholder="Enter Full Postal Address" required></textarea>
                            <div class="invalid-feedback">Please enter address.</div>
                        </div>
                    </div>

                    <!-- Section: Security & System Settings -->
                    <h5 class="fw-bold text-primary mb-3 border-bottom pb-2 mt-5">
                        <i class="fas fa-cog me-2"></i>System & Uploads
                    </h5>
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Default Password <span class="text-danger">*</span></label>
                            <input type="text" class="form-control py-2.5 rounded-3 border-secondary-subtle" name="pass" value="12345" required>
                            <div class="invalid-feedback">Please set a password.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Profile Picture (Student Avatar)</label>
                            <input type="file" name="img" class="form-control py-2 rounded-3 border-secondary-subtle" accept="image/*">
                            <small class="text-muted">Used for the "Recent Joined Students" home section carousel/scroller.</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select py-2.5 rounded-3 border-secondary-subtle" required>
                                <option value="1" selected>Active / Approved</option>
                                <option value="0">Inactive / Pending</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 shadow fw-bold text-uppercase letter-spacing-1">
                            <i class="fas fa-check-circle me-2"></i>Register Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Course Loading script -->
<script>
function FetchCourse(id) {
    $('#course').html('<option value="">Loading courses...</option>');
    $.ajax({
        type: 'post',
        url: '../ajaxdata.php',
        data: { program_id: id },
        success: function(data) {
            $('#course').html('<option value="">--Select Course--</option>' + data);
        }
    });
}

// Bootstrap Form Validation Starter Script
(function () {
  'use strict'
  var forms = document.querySelectorAll('.needs-validation')
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
})()
</script>

<?php include 'includes/footer.php'; ?>
