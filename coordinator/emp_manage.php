<?php
session_start();
if (!isset($_SESSION['coord_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../connection.php';

// Handle Add/Edit/Delete
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $emp_no = mysqli_real_escape_string($con, $_POST['emp_no']);
        $name = mysqli_real_escape_string($con, $_POST['name']);
        $designation = mysqli_real_escape_string($con, $_POST['designation']);
        $base_salary = floatval($_POST['base_salary']);

        $check = mysqli_query($con, "SELECT * FROM employees WHERE emp_no = '$emp_no'");
        if (mysqli_num_rows($check) > 0) {
            $message = "Employee No already exists!";
            $messageType = "danger";
        } else {
            $insert = mysqli_query($con, "INSERT INTO employees (emp_no, name, designation, base_salary) VALUES ('$emp_no', '$name', '$designation', '$base_salary')");
            if ($insert) {
                $message = "Employee added successfully.";
                $messageType = "success";
            } else {
                $message = "Error: " . mysqli_error($con);
                $messageType = "danger";
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'edit') {
        $id = intval($_POST['id']);
        $name = mysqli_real_escape_string($con, $_POST['name']);
        $designation = mysqli_real_escape_string($con, $_POST['designation']);
        $base_salary = floatval($_POST['base_salary']);

        $update = mysqli_query($con, "UPDATE employees SET name='$name', designation='$designation', base_salary='$base_salary' WHERE id=$id");
        if ($update) {
            $message = "Employee updated successfully.";
            $messageType = "success";
        } else {
            $message = "Error: " . mysqli_error($con);
            $messageType = "danger";
        }
    }
}



$employees = mysqli_query($con, "SELECT * FROM employees ORDER BY id DESC");

include 'includes/header.php';
?>
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-users-cog me-2"></i>Employee Management</h2>
                <button class="btn btn-primary glass-btn" data-bs-toggle="modal" data-bs-target="#addEmpModal">
                    <i class="fas fa-plus me-2"></i> Add Employee
                </button>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="glass-panel p-4">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Emp No (ID)</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Base Salary</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($employees)): ?>
                            <tr>
                                <td><?php echo $row['emp_no']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['designation']; ?></td>
                                <td>₹<?php echo number_format($row['base_salary'], 2); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick='editEmp(<?php echo json_encode($row); ?>)'><i class="fas fa-edit"></i> Edit</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if (mysqli_num_rows($employees) == 0): ?>
                            <tr>
                                <td colspan="5" class="text-center">No employees found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

<!-- Add Modal -->
<div class="modal fade" id="addEmpModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content glass-panel border-0">
            <div class="modal-header">
                <h5 class="modal-title">Add Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label>Employee No (Must match attendance machine ID)</label>
                        <input type="text" name="emp_no" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Designation</label>
                        <input type="text" name="designation" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Monthly Base Salary (₹)</label>
                        <input type="number" step="0.01" name="base_salary" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editEmpModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content glass-panel border-0">
            <div class="modal-header">
                <h5 class="modal-title">Edit Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label>Employee No (Cannot be changed)</label>
                        <input type="text" id="edit_emp_no" class="form-control" readonly disabled>
                    </div>
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Designation</label>
                        <input type="text" name="designation" id="edit_designation" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Monthly Base Salary (₹)</label>
                        <input type="number" step="0.01" name="base_salary" id="edit_base_salary" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editEmp(data) {
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_emp_no').value = data.emp_no;
        document.getElementById('edit_name').value = data.name;
        document.getElementById('edit_designation').value = data.designation;
        document.getElementById('edit_base_salary').value = data.base_salary;
        
        var editModal = new bootstrap.Modal(document.getElementById('editEmpModal'));
        editModal.show();
    }
</script>

<?php include 'includes/footer.php'; ?>
