<?php
// admin/enquiries.php
require_once '../connection.php';
include 'includes/header.php';

$cid = 10; // Not used here but kept for consistency if needed

// Handle Delete
if (isset($_GET['delete'])) {
    $provided_token = $_GET['csrf_token'] ?? '';
    if (!verify_csrf_token($provided_token)) {
        die("CSRF validation failed.");
    }
    $id = intval($_GET['delete']);
    $query = "DELETE FROM contact WHERE id = $id";
    if (mysqli_query($con, $query)) {
        echo "<script>window.location.href='enquiries?msg=deleted';</script>";
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
    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-dark"><i class="fas fa-inbox text-primary me-2"></i>All Enquiries</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" width="5%">#</th>
                        <th width="15%">Name</th>
                        <th width="18%">Contact</th>
                        <th width="15%">Location / Gender</th>
                        <th width="18%">Subject</th>
                        <th width="15%">Message</th>
                        <th width="9%">Date</th>
                        <th class="pe-4 text-center" width="5%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM contact ORDER BY id DESC";
                    $result = mysqli_query($con, $query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $msgSnippet = (strlen($row['msg']) > 35) ? substr(strip_tags($row['msg']), 0, 35) . '...' : $row['msg'];
                            $token = csrf_token();
                            
                            $attrName = htmlspecialchars($row['name'], ENT_QUOTES);
                            $attrEmail = htmlspecialchars($row['email'], ENT_QUOTES);
                            $attrMob = htmlspecialchars($row['mob'], ENT_QUOTES);
                            $attrLoc = htmlspecialchars($row['loc'], ENT_QUOTES);
                            $attrGender = htmlspecialchars($row['gender'], ENT_QUOTES);
                            $attrSub = htmlspecialchars($row['sub'], ENT_QUOTES);
                            $attrMsg = htmlspecialchars($row['msg'], ENT_QUOTES);
                            $attrDate = htmlspecialchars($row['date'], ENT_QUOTES);
                            
                            echo "<tr>
                                    <td class='ps-4 fw-bold text-muted'>{$row['id']}</td>
                                    <td><div class='fw-bold text-dark'>" . htmlspecialchars($row['name']) . "</div></td>
                                    <td>
                                        <div class='small text-muted'><i class='fas fa-envelope me-1 text-primary'></i> " . htmlspecialchars($row['email']) . "</div>
                                        <div class='small text-muted'><i class='fas fa-phone me-1 text-success'></i> " . htmlspecialchars($row['mob']) . "</div>
                                    </td>
                                    <td>
                                        <div class='fw-semibold small text-dark'><i class='fas fa-map-marker-alt text-danger me-1'></i> " . htmlspecialchars($row['loc']) . "</div>
                                        <span class='badge bg-light text-dark border small mt-1'>" . htmlspecialchars($row['gender']) . "</span>
                                    </td>
                                    <td><span class='fw-medium text-dark small'>" . htmlspecialchars($row['sub']) . "</span></td>
                                    <td>
                                        <div class='small text-muted text-truncate' style='max-width: 140px;'>" . htmlspecialchars($msgSnippet) . "</div>
                                        <button type='button' class='btn btn-link btn-sm p-0 text-primary fw-bold text-decoration-none view-enquiry-btn' 
                                            data-bs-toggle='modal' 
                                            data-bs-target='#enquiryModal' 
                                            data-name='{$attrName}'
                                            data-email='{$attrEmail}'
                                            data-mob='{$attrMob}'
                                            data-loc='{$attrLoc}'
                                            data-gender='{$attrGender}'
                                            data-sub='{$attrSub}'
                                            data-msg='{$attrMsg}'
                                            data-date='{$attrDate}'>
                                            <i class='fas fa-eye'></i> View Msg
                                        </button>
                                    </td>
                                    <td class='small text-muted'>" . htmlspecialchars($row['date']) . "</td>
                                    <td class='pe-4 text-center'>
                                        <a href='enquiries?delete={$row['id']}&csrf_token={$token}' class='btn btn-sm btn-outline-danger rounded-3' onclick='return confirm(\"Are you sure you want to delete this enquiry?\")' title='Delete Enquiry'><i class='fas fa-trash'></i></a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center py-4 text-muted'>No enquiries found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Single Shared Bootstrap 5 Modal for Enquiry Details -->
<div class="modal fade" id="enquiryModal" tabindex="-1" aria-labelledby="enquiryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="enquiryModalLabel"><i class="fas fa-info-circle me-2"></i>Enquiry Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <h6 class="text-muted text-uppercase small fw-bold mb-1">From</h6>
                    <p class="fs-5 fw-bold text-dark mb-0" id="modalSenderName"></p>
                    <span class="text-muted small"><i class="fas fa-venus-mars text-primary"></i> <span id="modalSenderGender"></span> | <i class="fas fa-map-marker-alt text-danger"></i> <span id="modalSenderLoc"></span></span>
                </div>
                <div class="mb-3 row g-2">
                    <div class="col-6">
                        <div class="p-2 bg-light rounded-3">
                            <h6 class="text-muted text-uppercase text-xs fw-bold mb-1">Email</h6>
                            <a href="#" id="modalSenderEmailLink" class="text-primary text-decoration-none fw-medium small text-break"></a>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light rounded-3">
                            <h6 class="text-muted text-uppercase text-xs fw-bold mb-1">Mobile</h6>
                            <a href="#" id="modalSenderMobLink" class="text-success text-decoration-none fw-medium small text-break"></a>
                        </div>
                    </div>
                </div>
                <hr class="text-muted opacity-25">
                <div class="mb-3">
                    <h6 class="text-muted text-uppercase small fw-bold mb-1">Subject</h6>
                    <p class="fw-bold text-dark mb-0" id="modalSubject"></p>
                </div>
                <div class="mb-1">
                    <h6 class="text-muted text-uppercase small fw-bold mb-1">Message</h6>
                    <div class="p-3 bg-light rounded-3 text-secondary" id="modalMessage" style="white-space: pre-wrap; max-height: 220px; overflow-y: auto;"></div>
                </div>
            </div>
            <div class="modal-footer border-0 py-3 bg-light d-flex justify-content-between">
                <span class="text-muted small"><i class="far fa-clock me-1"></i> <span id="modalReceivedDate"></span></span>
                <button type="button" class="btn btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const enquiryModal = document.getElementById('enquiryModal');
    if (enquiryModal) {
        enquiryModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;
            
            const name = button.getAttribute('data-name') || '';
            const email = button.getAttribute('data-email') || '';
            const mob = button.getAttribute('data-mob') || '';
            const loc = button.getAttribute('data-loc') || '';
            const gender = button.getAttribute('data-gender') || '';
            const sub = button.getAttribute('data-sub') || '';
            const msg = button.getAttribute('data-msg') || '';
            const date = button.getAttribute('data-date') || '';
            
            document.getElementById('modalSenderName').textContent = name;
            document.getElementById('modalSenderGender').textContent = gender;
            document.getElementById('modalSenderLoc').textContent = loc;
            
            const emailLink = document.getElementById('modalSenderEmailLink');
            emailLink.textContent = email;
            emailLink.href = 'mailto:' + email;
            
            const mobLink = document.getElementById('modalSenderMobLink');
            mobLink.textContent = mob;
            mobLink.href = 'tel:' + mob;
            
            document.getElementById('modalSubject').textContent = sub;
            document.getElementById('modalMessage').textContent = msg;
            document.getElementById('modalReceivedDate').textContent = 'Received: ' + date;
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>