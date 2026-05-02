<?php
// admin/email_templates.php
require_once '../connection.php';
include 'includes/header.php';

$message = '';
$error = '';

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_template'])) {
    $id = (int)$_POST['id'];
    $subject = mysqli_real_escape_string($con, $_POST['subject']);
    $body = mysqli_real_escape_string($con, $_POST['body']);

    $sql = "UPDATE email_templates SET subject='$subject', body='$body' WHERE id=$id";
    if (mysqli_query($con, $sql)) {
        $message = "Template updated successfully!";
    } else {
        $error = "Update Failed: " . mysqli_error($con);
    }
}

// Fetch templates
$templatesQ = mysqli_query($con, "SELECT * FROM email_templates");
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="h3 mb-0 text-gray-800">Email Templates</h2>
        <p class="text-muted small">Customize the emails sent to donors automatically.</p>
    </div>
</div>

<?php if ($message) echo '<div class="alert alert-success">' . $message . '</div>'; ?>
<?php if ($error) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>

<div class="row">
    <?php while($tpl = mysqli_fetch_assoc($templatesQ)): ?>
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-bold text-primary">
                    <i class="fas fa-file-code me-2"></i>
                    <?php echo ucwords(str_replace('_', ' ', $tpl['template_key'])); ?>
                </h5>
                <span class="badge bg-light text-dark border small">Key: {{<?php echo $tpl['template_key']; ?>}}</span>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $tpl['id']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Subject</label>
                        <input type="text" name="subject" class="form-control" value="<?php echo htmlspecialchars($tpl['subject']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Body (HTML Supported)</label>
                        <textarea name="body" class="form-control font-monospace" rows="15" required><?php echo htmlspecialchars($tpl['body']); ?></textarea>
                        <div class="form-text mt-2">
                            <strong>Available Placeholders:</strong> 
                            <code>{{donor_name}}</code>, <code>{{amount}}</code>, <code>{{payment_id}}</code>, 
                            <code>{{date}}</code>, <code>{{org_name}}</code>, <code>{{org_email}}</code>
                        </div>
                    </div>

                    <hr>
                    <button type="submit" name="update_template" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Template
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="previewTemplate(<?php echo $tpl['id']; ?>)">
                        <i class="fas fa-eye"></i> Preview
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<!-- Preview Modal (Static for now) -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Template Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="previewContainer" style="border:1px solid #ddd; padding:0; background:#f4f4f4; border-radius:8px; overflow:hidden;">
                    <!-- Preview will be injected here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewTemplate(id) {
    // In a real app, you'd fetch with AJAX or just render the current textarea content
    var body = document.querySelector('textarea[name="body"]').value;
    
    // Simple placeholder replacement for preview
    var preview = body
        .replace(/{{donor_name}}/g, 'John Doe')
        .replace(/{{amount}}/g, '1000.00')
        .replace(/{{payment_id}}/g, 'pay_Sample123')
        .replace(/{{date}}/g, '<?php echo date('d M Y'); ?>')
        .replace(/{{org_name}}/g, '<?php echo addslashes($settings['site_name'] ?? 'Your Organization'); ?>')
        .replace(/{{org_email}}/g, '<?php echo addslashes($settings['email'] ?? 'contact@example.com'); ?>');

    document.getElementById('previewContainer').innerHTML = preview;
    var myModal = new bootstrap.Modal(document.getElementById('previewModal'));
    myModal.show();
}
</script>

<?php include 'includes/footer.php'; ?>
