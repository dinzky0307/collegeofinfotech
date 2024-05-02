<?php

namespace Database;

use \PDO;

class DatabaseService
{
    protected $connection;

    public function __construct()
    {
        $servername = "localhost";
        $username = "u510162695_infotechMCC";
        $password = "infotechMCC2023";
        $dbname = "u510162695_infotechMCC";

        $this->connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function fetch($query)
    {
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);

        return $statement->fetchAll();
    }

    public function fetchRow($query)
    {
        $statement = $this->connection->prepare($query);
        $statement->execute();

        return $statement->fetch();
    }

    public function countRows($query)
    {
        $results = $this->connection->query($query);
        return $results->fetchColumn();
    }


    public function inserToConsultation($data)
    {
        $sql = "INSERT INTO consultations (requested_by, type, thru, date, requested_by_name, person_attendance, meeting_number, areas_concern, recommendation, conducted_by, student_id, consultant_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $prepared = $this->connection->prepare($sql);

        return $prepared->execute([
            $data['requested_by'],
            $data['type'],
            $data['thru'],
            $data['date'],
            $data['requested_by_name'],
            $data['person_attendance'],
            $data['meeting_number'],
            $data['areas_concern'],
            $data['recommendation'],
            $data['conducted_by'],
            $data['student_id'],
            $data['consultant_id']
        ]);
    }

    public function updateConsultation($data)
    {
        $sql = "UPDATE consultations SET recommendation='{$data['recommendation']}' WHERE id={$data['id']}";

        $prepared = $this->connection->prepare($sql);

        return $prepared->execute();
    }

    public function updatePassword($data)
    {
        $sql = "UPDATE userdata SET password='{$data['password_confirmation']}' WHERE id={$data['id']}";

        $prepared = $this->connection->prepare($sql);

        return $prepared->execute();
    }
}
