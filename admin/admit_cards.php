<?php
// admin/admit_cards.php
require_once '../connection.php';
include 'includes/header.php';

$message = '';
$error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $query = "DELETE FROM admit_card WHERE id = $id";
    if (mysqli_query($con, $query)) {
        echo "<script>window.location.href='admit_cards.php?msg=deleted';</script>";
    }
}

// Handle Add (Generate Admit Card)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generate_admit'])) {
    $mid = intval($_POST['mid']); // Student ID (registration table id)

    // Fetch Student data to get course and branch
    $stuQ = mysqli_query($con, "SELECT bid, course FROM registration WHERE id = $mid");
    $stu = mysqli_fetch_assoc($stuQ);

    if ($stu) {
        $bid = $stu['bid'];
        $cid = $stu['course'];
        // pid seems to be parent ID or some other relation, taking logic from dump (e.g. 8)
        // For now, let's assume 0 or derived if not in form. In dump `pid` is 8.
        $pid = 0;

        $rollno = mysqli_real_escape_string($con, $_POST['rollno']);
        $exam_session = mysqli_real_escape_string($con, $_POST['exam_session']);
        $exam = mysqli_real_escape_string($con, $_POST['exam']); // Exam Name e.g. '4' (Semester?)
        $exam_date = mysqli_real_escape_string($con, $_POST['exam_date']);
        $exam_time = mysqli_real_escape_string($con, $_POST['exam_time']);
        $status = 1;
        $issue = date('Y-m-d');
        $date = date('d-m-Y');

        $query = "INSERT INTO admit_card (mid, rollno, pid, cid, exam_session, exam, exam_date, exam_time, issue, date, status, bid) 
                  VALUES ('$mid', '$rollno', '$pid', '$cid', '$exam_session', '$exam', '$exam_date', '$exam_time', '$issue', '$date', '$status', '$bid')";

        if (mysqli_query($con, $query)) {
            echo "<script>window.location.href='admit_cards.php?msg=generated';</script>";
        } else {
            $error = "Database Error: " . mysqli_error($con);
        }
    } else {
        $error = "Student not found!";
    }
}
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="h3 mb-0 text-gray-800">Admit Cards</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#admitModal">
            <i class="fas fa-plus"></i> Generate Admit Card
        </button>
    </div>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'generated')
    echo '<div class="alert alert-success">Admit Card generated successfully!</div>'; ?>
<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted')
    echo '<div class="alert alert-success">Admit Card deleted successfully!</div>'; ?>
<?php if ($error)
    echo '<div class="alert alert-danger">' . $error . '</div>'; ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Roll No</th>
                        <th>Student Name</th>
                        <th>Course</th>
                        <th>Exam Date/Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT a.*, r.name as student_name, c.name as course_name 
                              FROM admit_card a 
                              LEFT JOIN registration r ON a.mid = r.id 
                              LEFT JOIN courses c ON a.cid = c.id 
                              ORDER BY a.id DESC";
                    $result = mysqli_query($con, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>#{$row['id']}</td>
                                    <td>{$row['rollno']}</td>
                                    <td>
                                        <div class='fw-bold'>{$row['student_name']}</div>
                                    </td>
                                    <td><span class='badge bg-info text-dark'>{$row['course_name']}</span></td>
                                    <td>
                                        <div>{$row['exam_date']}</div>
                                        <small class='text-muted'>{$row['exam_time']}</small>
                                    </td>
                                    <td>
                                        <!-- Could add Print Link here -->
                                        <a href='admit_cards.php?delete={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'><i class='fas fa-trash'></i></a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center py-4'>No admit cards generated yet.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Generate Modal -->
<div class="modal fade" id="admitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title">Generate Admit Card</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Select Student (Search by RegNo or Name)</label>
                            <!-- Simple Select for MVP, ideal would be search input with AJAX -->
                            <select name="mid" class="form-select" required>
                                <option value="">Select Student...</option>
                                <?php
                                // Limit to active students to prevent huge list
                                $stuQ = mysqli_query($con, "SELECT id, regno, name FROM registration WHERE status=1 ORDER BY id DESC LIMIT 200");
                                while ($s = mysqli_fetch_assoc($stuQ)) {
                                    echo "<option value='{$s['id']}'>{$s['regno']} - {$s['name']}</option>";
                                }
                                ?>
                            </select>
                            <small class="text-muted">Showing last 200 active registrations.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Roll No</label>
                            <input type="text" name="rollno" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Session (Year)</label>
                            <input type="text" name="exam_session" class="form-control" value="<?php echo date('Y'); ?>"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Exam Name/Semester</label>
                            <input type="text" name="exam" class="form-control" placeholder="e.g. Final Semester"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Exam Date</label>
                            <input type="date" name="exam_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Exam Time</label>
                            <input type="text" name="exam_time" class="form-control"
                                placeholder="e.g. 10:00 AM - 01:00 PM" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="generate_admit" class="btn btn-primary">Generate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>