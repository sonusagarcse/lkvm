<?php
// ajaxdata.php
require 'connection.php';

if (isset($_POST['program_id'])) {
    $proid = intval($_POST['program_id']);
    $sel = $con->prepare("SELECT * FROM courses WHERE pid=? ORDER BY id DESC");
    if ($sel) {
        $sel->bind_param("i", $proid);
        $sel->execute();
        $result = $sel->get_result();
        while ($resd = $result->fetch_object()) {
            echo '<option value="' . $resd->id . '">' . htmlspecialchars($resd->name) . '</option>';
        }
        $sel->close();
    }
}
elseif (isset($_POST['course_id'])) {
    $proid = isset($_POST['program_id']) ? intval($_POST['program_id']) : 0;
    $cauid = intval($_POST['course_id']);
    $sel = $con->prepare("SELECT * FROM subjects WHERE pid=? AND cid=? ORDER BY id DESC");
    if ($sel) {
        $sel->bind_param("ii", $proid, $cauid);
        $sel->execute();
        $result = $sel->get_result();
        while ($resb = $result->fetch_object()) {
            echo '<option value="' . $resb->id . '">' . htmlspecialchars($resb->name) . '</option>';
        }
        $sel->close();
    }
}
?>