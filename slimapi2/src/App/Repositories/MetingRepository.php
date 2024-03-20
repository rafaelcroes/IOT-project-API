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
}