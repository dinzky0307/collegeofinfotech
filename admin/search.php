<?php
// search.php
include('../database.php');
include('data/teacher_model.php');

$teacher = new Datateacher($connection);
$search = isset($_POST['search']) ? $_POST['search'] : null;
$teachers = $teacher->getteacher($search);

if (is_array($teachers)) {
    foreach ($teachers as $number => $teacher) {
        echo '<tr>';
        echo '<td>' . $number . '</td>';
        echo '<td class="text-center">' . $teacher['teachid'] . '</td>';
        echo '<td>' . $teacher['lname'] . '</td>';
        echo '<td>' . $teacher['fname'] . '</td>';
        echo '<td>' . $teacher['mname'] . '</td>';
        echo '<td class="text-center">' . $teacher['sex'] . '</td>';
        echo '<td>' . $teacher['email'] . '</td>';
        echo '<td class="text-center">';
        echo '<div style="display: inline-block;">';
        echo '<a href="edit.php?type=teacher&id=' . $teacher['id'] . '" title="Update">';
        echo '<i class="fa fa-edit fa-lg text-primary"></i>';
        echo '</a>';
        echo '</div>';
        // Add other action buttons here
        echo '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr>';
    echo '<td colspan="8">No teachers found.</td>';
    echo '</tr>';
}
?>

