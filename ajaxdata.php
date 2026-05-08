<?php

require 'connection.php';
if (isset($_POST['program_id'])) {
    $proid = $_POST['program_id'];
    $sel = $con->prepare("select * from courses where pid=? order by id DESC");
    $sel->bind_param("i", $proid);
    $sel->execute();
    $result = $sel->get_result();
    while ($resd = $result->fetch_object()) {
        echo '<option value=' . $resd->id . '>' . $resd->name . '</option>';
    }
    $sel->close();
} elseif (isset($_POST['course_id'])) {
    $proid = isset($_POST['program_id']) ? intval($_POST['program_id']) : 0;
    $cauid = $_POST['course_id'];
    $sel = $con->prepare("select * from subjects where pid=? and cid=? order by id DESC");
    $sel->bind_param("ii", $proid, $cauid);
    $sel->execute();
    $result = $sel->get_result();
    while ($resb = $result->fetch_object()) {
        echo '<option value=' . $resb->id . '>' . $resb->name . '</option>';
    }
    $sel->close();
}
?>