<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_type'] != 1) {
    header("Location: login.php");
    exit();
}

require_once '../connection.php';

$message = '';
$messageType = '';

// Fetch existing settings
$settings_query = mysqli_query($con, "SELECT * FROM emp_settings LIMIT 1");
$settings = mysqli_fetch_assoc($settings_query);

if (!$settings) {
    mysqli_query($con, "INSERT INTO emp_settings (full_day_hours, half_day_hours, salary_divisor) VALUES (8.00, 4.00, 30)");
    $settings_query = mysqli_query($con, "SELECT * FROM emp_settings LIMIT 1");
    $settings = mysqli_fetch_assoc($settings_query);
}

// Ensure divisor key exists (fallback for existing rows before ALTER)
if (!isset($settings['salary_divisor'])) {
    $settings['salary_divisor'] = 30;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_rules'])) {
        $full = floatval($_POST['full_day_hours']);
        $half = floatval($_POST['half_day_hours']);
        $divisor = intval($_POST['salary_divisor']);
        
        $update = mysqli_query($con, "UPDATE emp_settings SET full_day_hours='$full', half_day_hours='$half', salary_divisor='$divisor' WHERE id=" . $settings['id']);
        if ($update) {
            $message = "Rules updated successfully.";
            $messageType = "success";
            $settings['full_day_hours'] = $full;
            $settings['half_day_hours'] = $half;
            $settings['salary_divisor'] = $divisor;
        } else {
            $message = "Error updating rules.";
            $messageType = "danger";
        }
    }

    if (isset($_POST['upload_bg'])) {
        if (isset($_FILES['slip_bg']['name']) && $_FILES['slip_bg']['name'] != '') {
            $filename = time() . '_' . basename($_FILES['slip_bg']['name']);
            $target = "../images/" . $filename;
            if (move_uploaded_file($_FILES['slip_bg']['tmp_name'], $target)) {
                $update = mysqli_query($con, "UPDATE emp_settings SET slip_bg_image='$filename' WHERE id=" . $settings['id']);
                if ($update) {
                    $message = "Background image uploaded.";
                    $messageType = "success";
                    $settings['slip_bg_image'] = $filename;
                }
            }
        }
    }

    if (isset($_POST['save_positions'])) {
        $positions = mysqli_real_escape_string($con, $_POST['positions_json']);
        $update = mysqli_query($con, "UPDATE emp_settings SET slip_text_positions='$positions' WHERE id=" . $settings['id']);
        if ($update) {
            $message = "Template text positions saved.";
            $messageType = "success";
            $settings['slip_text_positions'] = stripslashes($positions);
        }
    }

    if (isset($_POST['add_holiday'])) {
        $date = mysqli_real_escape_string($con, $_POST['h_date']);
        $title = mysqli_real_escape_string($con, $_POST['h_title']);
        $q = mysqli_query($con, "INSERT INTO emp_holidays (holiday_date, title) VALUES ('$date', '$title') ON DUPLICATE KEY UPDATE title='$title'");
        if ($q) {
            $message = "Holiday added/updated successfully.";
            $messageType = "success";
        }
    }

    if (isset($_GET['del_holiday'])) {
        $id = intval($_GET['del_holiday']);
        mysqli_query($con, "DELETE FROM emp_holidays WHERE id=$id");
        $message = "Holiday deleted.";
        $messageType = "warning";
    }
}

$positions = $settings['slip_text_positions'] ? json_decode($settings['slip_text_positions'], true) : [];
$defaultFields = ['EmpNo', 'Name', 'Designation', 'MonthYear', 'TotalDays', 'FullDays', 'HalfDays', 'Absents', 'FinalSalary'];

// Ensure all default fields exist in positions
foreach ($defaultFields as $field) {
    if (!isset($positions[$field])) {
        $positions[$field] = ['x' => 10, 'y' => 10, 'fontSize' => 16, 'color' => '#000000'];
    } else {
        // Fix legacy pixel coordinates that push elements off-screen
        if (isset($positions[$field]['x']) && $positions[$field]['x'] > 100) $positions[$field]['x'] = 10;
        if (isset($positions[$field]['y']) && $positions[$field]['y'] > 100) $positions[$field]['y'] = 10;
    }
}

include 'includes/header.php';
?>
        <h2 class="mb-4"><i class="fas fa-cogs me-2"></i>Employee & Salary Settings</h2>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Working Hours Rules -->
            <div class="col-md-4 mb-4">
                <div class="glass-panel p-4 h-100">
                    <h4>Salary & Attendance Rules</h4>
                    <hr>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Fixed Monthly Days</label>
                            <input type="number" name="salary_divisor" class="form-control" value="<?php echo $settings['salary_divisor']; ?>" required>
                            <div class="form-text text-info">
                                <i class="fas fa-info-circle me-1"></i>
                                Used as divisor: (Base Salary / <strong><?php echo $settings['salary_divisor']; ?></strong>)
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Full Day Minimum Hours</label>
                            <input type="number" step="0.1" name="full_day_hours" class="form-control" value="<?php echo $settings['full_day_hours']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Half Day Minimum Hours</label>
                            <input type="number" step="0.1" name="half_day_hours" class="form-control" value="<?php echo $settings['half_day_hours']; ?>" required>
                        </div>
                        <button type="submit" name="update_rules" class="btn btn-primary w-100">Save Rules</button>
                    </form>
                </div>
            </div>

            <!-- Template Background Upload -->
            <div class="col-md-4 mb-4">
                <div class="glass-panel p-4 h-100">
                    <h4>Official Holiday Calendar</h4>
                    <hr>
                    <form method="POST" class="mb-3">
                        <div class="input-group">
                            <input type="date" name="h_date" class="form-control" required>
                            <input type="text" name="h_title" class="form-control" placeholder="Holiday Name" required>
                            <button type="submit" name="add_holiday" class="btn btn-success"><i class="fas fa-plus"></i></button>
                        </div>
                    </form>
                    <div style="max-height: 200px; overflow-y: auto;">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Title</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $h_query = mysqli_query($con, "SELECT * FROM emp_holidays ORDER BY holiday_date ASC");
                                while($h = mysqli_fetch_assoc($h_query)):
                                ?>
                                <tr>
                                    <td><small><?php echo date('d-M', strtotime($h['holiday_date'])); ?></small></td>
                                    <td><small><?php echo $h['title']; ?></small></td>
                                    <td>
                                        <a href="?del_holiday=<?php echo $h['id']; ?>" class="text-danger" onclick="return confirm('Delete?')"><i class="fas fa-times"></i></a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="glass-panel p-4 h-100">
                    <h4>Salary Slip Template Background</h4>
                    <hr>
                    <form method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
                        <input type="file" name="slip_bg" class="form-control me-2" accept="image/*" required>
                        <button type="submit" name="upload_bg" class="btn btn-success text-nowrap">Upload Image</button>
                    </form>
                    <?php if ($settings['slip_bg_image']): ?>
                        <p class="mt-3 text-success"><i class="fas fa-check-circle"></i> Background image uploaded. Configure text positions below.</p>
                    <?php else: ?>
                        <p class="mt-3 text-warning"><i class="fas fa-exclamation-triangle"></i> Please upload a background image first.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Template Editor -->
        <?php if ($settings['slip_bg_image']): ?>
        <div class="glass-panel p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Template Editor</h4>
                <form method="POST" id="savePositionsForm">
                    <input type="hidden" name="positions_json" id="positions_json">
                    <input type="hidden" name="save_positions" value="1">
                    <button type="button" class="btn btn-primary" onclick="savePositions()"><i class="fas fa-save me-2"></i>Save Positions</button>
                </form>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-3">
                    <h5>Fields Formatting</h5>
                    <p class="text-muted small">Select a field on the canvas to change its style.</p>
                    <div id="field-settings" style="display: none;">
                        <h6 id="current-field-name" class="text-primary mb-3"></h6>
                        <div class="mb-3">
                            <label>Font Size (px)</label>
                            <input type="number" id="field-size" class="form-control form-control-sm" onchange="updateFieldStyle()">
                        </div>
                        <div class="mb-3">
                            <label>Color</label>
                            <input type="color" id="field-color" class="form-control form-control-sm" onchange="updateFieldStyle()">
                        </div>
                    </div>
                </div>
                <div class="col-md-9 text-center">
                    <p class="text-muted">Drag and drop the labels to position them on your background.</p>
                    <div id="canvas-container" style="position: relative; display: inline-block; border: 1px solid #ccc; overflow: hidden; background: #f8f9fa;">
                        <img src="../images/<?php echo $settings['slip_bg_image']; ?>" id="bg-image" style="max-width: 100%; pointer-events: none;">
                        
                        <?php foreach ($positions as $field => $style): ?>
                            <div class="draggable-field" 
                                 id="field-<?php echo $field; ?>" 
                                 data-field="<?php echo $field; ?>"
                                 style="position: absolute; cursor: move; user-select: none; padding: 2px 5px; border: 1px dashed transparent; font-weight: bold; left: <?php echo $style['x']; ?>%; top: <?php echo $style['y']; ?>%; font-size: <?php echo isset($style['fontSize']) ? $style['fontSize'] : 16; ?>px; color: <?php echo isset($style['color']) ? $style['color'] : '#000000'; ?>;">
                                {<?php echo $field; ?>}
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

<script>
    // Simple Drag and Drop implementation
    const draggables = document.querySelectorAll('.draggable-field');
    const container = document.getElementById('canvas-container');
    let activeField = null;
    let isDragging = false;
    let startX, startY, initialX, initialY;

    draggables.forEach(field => {
        field.addEventListener('mousedown', dragStart);
        field.addEventListener('click', selectField);
    });

    document.addEventListener('mousemove', drag);
    document.addEventListener('mouseup', dragEnd);

    function dragStart(e) {
        isDragging = true;
        activeField = e.target;
        selectField(e); // Also select it

        const rect = activeField.getBoundingClientRect();
        const containerRect = container.getBoundingClientRect();
        
        // Adjust for scrolling and container position
        initialX = activeField.offsetLeft;
        initialY = activeField.offsetTop;
        startX = e.clientX;
        startY = e.clientY;
    }

    function drag(e) {
        if (!isDragging || !activeField) return;

        e.preventDefault();
        const currentX = e.clientX - startX;
        const currentY = e.clientY - startY;

        let newX = initialX + currentX;
        let newY = initialY + currentY;

        // Optional: Keep inside container
        newX = Math.max(0, Math.min(newX, container.offsetWidth - activeField.offsetWidth));
        newY = Math.max(0, Math.min(newY, container.offsetHeight - activeField.offsetHeight));

        activeField.style.left = newX + 'px';
        activeField.style.top = newY + 'px';
    }

    function dragEnd(e) {
        isDragging = false;
    }

    function selectField(e) {
        draggables.forEach(f => f.classList.remove('active'));
        e.target.classList.add('active');
        
        const fieldName = e.target.getAttribute('data-field');
        document.getElementById('field-settings').style.display = 'block';
        document.getElementById('current-field-name').innerText = '{' + fieldName + '}';
        
        // Extract numbers safely
        const sizeStr = window.getComputedStyle(e.target).fontSize;
        document.getElementById('field-size').value = parseInt(sizeStr);
        
        // Convert rgb to hex for color input
        const color = window.getComputedStyle(e.target).color;
        document.getElementById('field-color').value = rgbToHex(color);
    }

    function updateFieldStyle() {
        const active = document.querySelector('.draggable-field.active');
        if (active) {
            const size = document.getElementById('field-size').value;
            const color = document.getElementById('field-color').value;
            active.style.fontSize = size + 'px';
            active.style.color = color;
        }
    }

    function savePositions() {
        const data = {};
        const img = document.getElementById('bg-image');
        const imgWidth = img.offsetWidth;
        const imgHeight = img.offsetHeight;

        draggables.forEach(field => {
            const name = field.getAttribute('data-field');
            data[name] = {
                x: (field.offsetLeft / imgWidth) * 100,
                y: (field.offsetTop / imgHeight) * 100,
                fontSize: parseInt(window.getComputedStyle(field).fontSize),
                color: window.getComputedStyle(field).color
            };
        });

        document.getElementById('positions_json').value = JSON.stringify(data);
        document.getElementById('savePositionsForm').submit();
    }

    function rgbToHex(rgb) {
        if (rgb.startsWith('#')) return rgb;
        const arr = rgb.match(/\d+/g);
        if(!arr) return '#000000';
        return "#" + ((1 << 24) + (parseInt(arr[0]) << 16) + (parseInt(arr[1]) << 8) + parseInt(arr[2])).toString(16).slice(1);
    }
</script>
<style>
    .draggable-field:hover, .draggable-field.active {
        border-color: #007bff !important;
        background: rgba(0, 123, 255, 0.1);
    }
</style>

<?php include 'includes/footer.php'; ?>
