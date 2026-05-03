<?php
// admin/enquiries.php
require_once '../connection.php';
include 'includes/header.php';

$cid = 10; // Not used here but kept for consistency if needed

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $query = "DELETE FROM contact WHERE id = $id";
    if (mysqli_query($con, $query)) {
        echo "<script>window.location.href='enquiries.php?msg=deleted';</script>";
    }
}
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="h3 mb-0 text-gray-800">Contact Enquiries</h2>
    </div>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted')
    echo '<div class="alert alert-success">Enquiry deleted successfully!</div>'; ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th width="15%">Name</th>
                        <th width="15%">Contact</th>
                        <th width="15%">Location/Gender</th>
                        <th width="20%">Subject</th>
                        <th width="20%">Message</th>
                        <th width="10%">Date</th>
                        <th width="5%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM contact ORDER BY id DESC";
                    $result = mysqli_query($con, $query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $msg = (strlen($row['msg']) > 50) ? substr(strip_tags($row['msg']), 0, 50) . '...' : $row['msg'];
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td><strong>" . htmlspecialchars($row['name']) . "</strong></td>
                                    <td>
                                        <i class='fas fa-envelope text-muted'></i> " . htmlspecialchars($row['email']) . "<br>
                                        <i class='fas fa-phone text-muted'></i> " . htmlspecialchars($row['mob']) . "
                                    </td>
                                    <td>
                                        " . htmlspecialchars($row['loc']) . "<br>
                                        <small class='text-muted'>" . htmlspecialchars($row['gender']) . "</small>
                                    </td>
                                    <td>" . htmlspecialchars($row['sub']) . "</td>
                                    <td title='" . htmlspecialchars($row['msg']) . "'>{$msg}</td>
                                    <td>" . htmlspecialchars($row['date']) . "</td>
                                    <td>
                                        <a href='enquiries.php?delete={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this enquiry?\")'><i class='fas fa-trash'></i></a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>No enquiries found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>