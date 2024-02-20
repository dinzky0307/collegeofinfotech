<?php
//grade.php
function getsubject()
{
    $id = $this->getid();
    $q = "select * from studentsubject where studid=$id";

    if (isset($_GET['year']) && isset($_GET['semester'])) {
        $year = $_GET['year'];
        $sem = $_GET['semester'];
        $q .= " AND year = '$year' AND semester = '$sem'";
    } else {
        $q .= " AND year = '1' AND semester = 'First Semester'";
    }

    $r = mysql_query($q);
    $data = array();
    while ($row = mysql_fetch_array($r)) {
        $classid = $row['classid'];
        $year = $row['year'];
        $section = $row['section'];
        $sem = $row['semester'];
        $SY = $row['SY'];
        $subjectid = $row['subjectid'];

        $q3 = "select * from subject where id=$subjectid";
        $r3 = mysql_query($q3);
        while ($srow = mysql_fetch_array($r3)) {
            $subjectcode = $srow['code'];

            $q2 = "select * from class where year=$year AND section='$section' AND sem='$sem' AND SY='$SY' AND subject='$subjectcode'";
            $r2 = mysql_query($q2);
            $data[] = mysql_fetch_array($r2);
        }
    }
    return $data;
}
?>
<html>
<!-- index.php -->
<tbody>
    <?php
    // print_r($mysubject);
    // print_r($row);
    foreach ($mysubject as $row) : ?>

        <tr>
            <td>
                <?php echo $row['subject']; ?>
            </td>
            <td>
                <?php echo $row['description']; ?>
            </td>
            <?php $title = $grade->getsubjectitle($row['subject']); ?>
            <?php $mygrade = $grade->getgrade($row['year'], $row['section'], $row['sem'], $row['SY'], $row['subject']);
            // print_r($mygrade);
            ?>
            <td class="text-center">
                <?php if (isset($mygrade['prelim_grade'])) : ?>
                    <?php echo $grade->gradeconversion($mygrade['prelim_grade']); ?>
                <?php endif; ?>
            </td>

            <td class="text-center">
                <?php if (isset($mygrade['prelim_grade'])) : ?>
                    <?php
                    $prelimGrade = $mygrade['prelim_grade'];
                    if ($prelimGrade > 3) {
                        echo "<font color='red'>Failed</font>";
                    } else if ($prelimGrade == 0) {
                        echo "<font color='black'>NG</font>";
                    } else {
                        echo "<font color='green'>Passed</font>";
                    }
                    ?>
                <?php else :
                    echo "<font color='black'>NG</font>";

                ?>
                <?php endif; ?>
            </td>

            <td class="text-center">
                <?php if (isset($mygrade['midterm_grade'])) : ?>
                    <?php echo $grade->gradeconversion($mygrade['midterm_grade']); ?>
                <?php endif; ?>
            </td>
            <td class="text-center">
                <?php if (isset($mygrade['midterm_grade'])) : ?>
                    <?php
                    $prelimGrade = $mygrade['midterm_grade'];
                    if ($prelimGrade > 3) {
                        echo "<font color='red'>Failed</font>";
                    } else if ($prelimGrade == 0) {
                        echo "<font color='black'>NG</font>";
                    } else {
                        echo "<font color='green'>Passed</font>";
                    }
                    ?>
                <?php else :
                    echo "<font color='black'>NG</font>";

                ?>
                <?php endif; ?>
            </td>
            <td class="text-center">
                <?php if (isset($mygrade['finals_grade'])) : ?>
                    <?php echo $grade->gradeconversion($mygrade['finals_grade']); ?>
                <?php endif; ?>
            </td>
            <td class="text-center">
                <?php if (isset($mygrade['finals_grade'])) : ?>
                    <?php
                    $prelimGrade = $mygrade['finals_grade'];
                    if ($prelimGrade > 3) {
                        echo "<font color='red'>Failed</font>";
                    } else if ($prelimGrade == 0) {
                        echo "<font color='black'>NG</font>";
                    } else {
                        echo "<font color='green'>Passed</font>";
                    }
                    ?>
                <?php else :
                    echo "<font color='black'>NG</font>";

                ?>
                <?php endif; ?>
            </td>

            <td class="text-center">
                <?php if (isset($mygrade['total'])) : ?>
                    <?php echo sprintf("%.1f", $mygrade['total']); ?>
                <?php endif; ?>
            </td>


            <td class="text-center">
                <?php if (isset($mygrade['total'])) : ?>
                    <?php
                    $prelimGrade = $mygrade['total'];
                    if ($prelimGrade > 3) {
                        echo "<font color='red'>Failed</font>";
                    } else if ($prelimGrade == 0) {
                        echo "<font color='black'>NG</font>";
                    } else {
                        echo "<font color='green'>Passed</font>";
                    }
                    ?>
                <?php else :
                    echo "<font color='black'>NG</font>";

                ?>
                <?php endif; ?>
            </td>
            <!-- <td class="text-center"><?php echo $title[0]['unit']; ?></td>-->
        </tr>
        <!-- <td class="text-center"><?php echo $title[0]['unit']; ?></td>-->
        </tr>
    <?php endforeach; ?>
</tbody>

</html>