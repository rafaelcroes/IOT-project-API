<?php 

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

class MetingRepository
{   
    public function __construct(private Database $database)
    {
        
    }

    public function getAllMetingen():array
    {   

        
        $pdo = $this->database->getConnection();
    
        $stmt = $pdo->query("SELECT * FROM meting");
    
        return $stmt->fetchALL(PDO::FETCH_ASSOC);


    }
    public function getMetingBySensorId(int $id) : array | bool
    {

        $sql = 'SELECT *
                FROM meting
                where SensorID = :id
                Order by MetingID desc';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchall(PDO::FETCH_ASSOC);
}

public function addMeting(array $data) : string {
    $sql = 'INSERT INTO meting (SensorID, Waarde)
            VALUES (:sensorid, :waarde)';   

    $pdo = $this -> database -> getConnection(); // Connectie naar de database

    $stmt = $pdo -> prepare($sql); // Prepare de query

    $stmt -> bindValue(':sensorid', $data['SensorID'], PDO::PARAM_INT);
    
    $stmt -> bindValue(':waarde', $data['Waarde'], PDO::PARAM_STR);

    $stmt -> execute(); // Voer de query uit

    return $pdo -> lastInsertId(); // Return het laatst ingevoerde ID
}

public function getFilteredMetingen($params)
{

    $pdo = $this -> database -> getConnection();


    $sql = 'SELECT * FROM meting WHERE 1=1';

    if(!empty($params['startDatum']))   {
        $sql .= ' AND Timestamp >= :startDatum';
    }
    if(!empty($params['eindDatum']))   {
        $sql .= ' AND Timestamp <= :eindDatum';
    }
    if(!empty($params['sensorID']))   {
        $sql .= ' AND SensorID = :sensorID';
    }

    if(!empty($params['sorteerVolgorde']) && 
       in_array(strtolower($params['sorteerVolgorde']), ['asc', 'desc']))
    {
        $sql .= ' ORDER BY MetingID ' .
        strtoupper($params['sorteerVolgorde']);
    }

    if(!empty($params['aantal']) && is_numeric($params['aantal']))   {
        $sql .= ' LIMIT :aantal';
    }
    
    $stmt = $pdo -> prepare($sql);

    if(!empty($params['startDatum']))   {
        $stmt -> bindValue(':startDatum', $params['startDatum'], PDO::PARAM_STR);
    }

    if(!empty($params['eindDatum']))   {
        $stmt -> bindValue(':eindDatum', $params['eindDatum'], PDO::PARAM_STR);
    }

    if(!empty($params['sensorID']))   {
        $stmt -> bindValue(':sensorID', $params['sensorID'], PDO::PARAM_INT);
    }


    if(!empty($params['aantal']) && is_numeric($params['aantal']))   {
        $stmt -> bindValue(':aantal', (int)$params['aantal'], PDO::PARAM_INT);
    }

    $stmt -> execute();

    return $stmt -> fetchAll(PDO::FETCH_ASSOC);

}
}