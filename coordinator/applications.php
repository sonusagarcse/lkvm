<?php
// admin/applications.php
require_once '../connection.php';
include 'includes/header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $query = "DELETE FROM career WHERE id = $id";
    if (mysqli_query($con, $query)) {
        echo "<script>window.location.href='applications.php?msg=deleted';</script>";
    }
}
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="h3 mb-0 text-gray-800">Job Applications</h2>
    </div>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted')
    echo '<div class="alert alert-success">Application deleted successfully!</div>'; ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th width="20%">Name</th>
                        <th width="20%">Contact</th>
                        <th width="35%">Resume / Details</th>
                        <th width="10%">Date</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM career ORDER BY id DESC";
                    $result = mysqli_query($con, $query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Resume might be a file path or text. Check if it looks like a file.
                            $resume = htmlspecialchars($row['resume']);
                            $resumeDisplay = $resume;
                            if (strpos($resume, 'uploads/') !== false || strpos($resume, '.pdf') !== false || strpos($resume, '.doc') !== false) {
                                // Assuming uploads are in a standard directory, might need adjustment
                                $resumeDisplay = "<a href='../$resume' target='_blank' class='btn btn-sm btn-outline-primary'><i class='fas fa-download'></i> Download Resume</a>";
                            }

                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td><strong>" . htmlspecialchars($row['name']) . "</strong></td>
                                    <td>
                                        <i class='fas fa-envelope text-muted'></i> " . htmlspecialchars($row['email']) . "<br>
                                        <i class='fas fa-phone text-muted'></i> " . htmlspecialchars($row['mob']) . "
                                    </td>
                                    <td>{$resumeDisplay}</td>
                                    <td>" . htmlspecialchars($row['date']) . "</td>
                                    <td>
                                        <a href='applications.php?delete={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this application?\")'><i class='fas fa-trash'></i></a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No applications found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>