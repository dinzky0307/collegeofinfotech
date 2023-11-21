<?php
include('../database.php');

class Data
{
    private $connection; // Add a private property to store the connection

    function __construct($connection)
    {
        $this->connection = $connection;

        if (!isset($_SESSION['id'])) {
            header('location:../../');
        }
    }
    
    function logs($act)
    {
        $date = date('m-d-Y h:i:s A');
        $q = "INSERT INTO log VALUES (null, ?, ?)";
        $stmt = $this->connection->prepare($q);
        $stmt->execute([$date, $act]);
        return true;
    }

    function getsubject($search, $semester = null)
    {
        $q = "SELECT * FROM subject WHERE (code LIKE ? OR title LIKE ?)";

        if ($semester !== null) {
            $q .= " AND semester = ?";
        }

        $stmt = $this->connection->prepare($q);

        if ($semester !== null) {
            $stmt->execute(["%$search%", "%$search%", $semester]);
        } else {
            $stmt->execute(["%$search%", "%$search%"]);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // function getsubject($search)
    // {
    //     $q = "SELECT * FROM subject WHERE code LIKE ? OR title LIKE ? ORDER BY code ASC";
    //     $stmt = $this->connection->prepare($q);
    //     $stmt->execute(["%$search%", "%$search%"]);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    function getSubjectsByFilter($year, $semester)
    {
        global $connection;

        $query = "SELECT * FROM subject WHERE 1";

        if (!empty($year)) {
            $query .= " AND year = :year";
        }

        if ($semester !== '') {
            $query .= " AND semester = :semester";
        }

        $query .= " ORDER BY code ASC";

        $pdo_statement = $connection->prepare($query);

        if (!empty($year)) {
            $pdo_statement->bindParam(':year', $year, PDO::PARAM_INT);
        }

        if ($semester !== '') {
            $pdo_statement->bindParam(':semester', $semester, PDO::PARAM_INT);
        }

        $pdo_statement->execute();
        $subjects = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);

        return $subjects;
    }

    function getsubjectbyid($id)
    {
        $q = "SELECT * FROM subject WHERE id=?";
        $stmt = $this->connection->prepare($q);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function addsubject()
    {
        // Include '../../config.php' if needed
        // $code = $_POST['code'];
        // $title = $_POST['title'];
        // $lecunit = $_POST['lecunit'];
        // $labunit = $_POST['labunit'];
        // $totalunit = $_POST['totalunit'];
        // $pre = $_POST['pre'];
        // $q = "INSERT INTO subject VALUES (null, ?, ?, ?, ?, ?, ?)";
        // $stmt = $this->connection->prepare($q);
        // $stmt->execute([$code, $title, $lecunit, $labunit, $totalunit, $pre]);
    }

    function updatesubject()
    {
        include('../../config.php');

        $id = $_GET['id'];
        $code = $_POST['code'];
        $title = $_POST['title'];
        $lecunit = $_POST['lecunit'];
        $labunit = $_POST['labunit'];
        $totalunit = $_POST['totalunit'];
        $pre = $_POST['pre'];
        $semester = $_POST['semester'];
        $year = $_POST['year'];

        $q = "UPDATE subject SET code=?, title=?, lecunit=?, labunit=?, totalunit=?, pre=?, semester=?, year=? WHERE id=?";
        $stmt = $this->connection->prepare($q);
        $stmt->execute([$code, $title, $lecunit, $labunit, $totalunit, $pre, $semester, $year, $id]);
        $act = "update subject $code - $title";
        $this->logs($act);
        header('location:../subject.php');
    }

    // function delete()
    // {
    //     if (isset($_GET['id'])) {
    //         $table = $_GET['table'];
    //         $id = $_GET['id'];
    //         $page = $_GET['page'];

    //         $q = "DELETE FROM $table WHERE id=?";
    //         $stmt = $this->connection->prepare($q);
    //         $stmt->execute([$id]);
    //         print_r($table);
    //         // Perform any additional actions, such as logging the deletion

    //         header('location:../' . $page . '.php?r=deleted');
    //     }
    // }


    function fetchRow($query, $params = [])
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Function to get all subjects
    public function getAllSubjects()
    {
        $sql = "SELECT * FROM subject";
        $pdo_statement = $this->connection->prepare($sql);

        try {
            $pdo_statement->execute();
            $subjects = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);
            return $subjects;
        } catch (PDOException $e) {
            // Handle the exception (e.g., log the error, display an error message, etc.)
            die("Error fetching subjects: " . $e->getMessage());
        }
    }
}

if (isset($_POST['addSubject'])) {
    $sql = "INSERT INTO subject (code, title, lecunit, labunit, totalunit, pre, semester, year)
    VALUES (?,?,?,?,?,?,?,?)";

    $connection->prepare($sql)->execute([
        $_POST['code'],
        $_POST['title'],
        $_POST['lecunit'],
        $_POST['labunit'],
        $_POST['totalunit'],
        $_POST['pre'],
        $_POST['semester'],
        $_POST['year'],
    ]);

    echo "<script type='text/javascript'>";
    echo "Swal.fire({
       title: 'Subject successfully added',
       icon: 'success',
     })";
    echo "</script>";
}

$sql = 'SELECT * FROM subject WHERE year AND (id LIKE :keyword OR code LIKE :keyword OR title LIKE :keyword) ORDER BY title, code, id ASC ';

$query = $sql;
$pdo_statement = $connection->prepare($query);
$pdo_statement->bindValue(':keyword', '%' . '%', PDO::PARAM_STR);
$pdo_statement->execute();
$subjects = $pdo_statement->fetchAll();

?>
