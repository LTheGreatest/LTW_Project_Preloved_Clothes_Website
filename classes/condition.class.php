<?php
declare(strict_types=1);


class Condition{
    private int $id;
    private string $name;
    private string $description;

    public function __construct(int $id, string $name, string $description){
        $this->id= $id;
        $this->name= $name;
        $this->description=$description;

    }


    //getters
    public function getId() : int{
        return $this->id;
    }

    public function getName() : string{
        return $this->name;
    }

    public function getDescription() : string{
        return $this->description;
    }

    //queries
    static public function getConditionById(PDO $db, int $id){
        try{
            $stmt = $db->prepare('SELECT * FROM CONDITION WHERE conditionID = ?');
            $stmt->execute(array($id));
            $conditionDB = $stmt->fetch(); 

            return new Condition(
                intval($conditionDB['conditionID']),
                $conditionDB['name'],
                $conditionDB['description']
            );
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    static public function getConditionByName(PDO $db, string $name){
        try{
            $stmt = $db->prepare('SELECT * FROM CONDITION WHERE name = ?');
            $stmt->execute(array($name));
            $conditionDB = $stmt->fetch();
            
            return new Condition(
                intval($conditionDB['conditionID']),
                $conditionDB['name'],
                $conditionDB['description']
            );
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    static public function getAllCondition(PDO $db){
        try{
            $stmt = $db->prepare('SELECT * FROM CONDITION');
            $stmt->execute();
            $conditions = array();
            while($conditionDB = $stmt->fetch()){
                $condition= new Condition(intval($conditionDB['conditionID']),$conditionDB['name'],$conditionDB['description']);

                $conditions[]=$condition;
            }

            return $conditions;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    static function getConditionsOfProduct(PDO $db , int $id){
        try{
            $stmt = $db->prepare('SELECT conditionID FROM CONDITION_OF_PRODUCT WHERE productID = ?');
            $stmt->execute(array($id));
            $conditions = array();
            while($conditionID=$stmt->fetch()){
                $condition = Condition::getConditionById($db,intval($conditionID['conditionID']));
                $conditions[] = $condition;
            }
            return $conditions;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    static function insertConditionOfProduct(PDO $db , int $idProduct, int $idCondition){
        try{
            $stmt = $db->prepare('INSERT INTO Condition_OF_PRODUCT (conditionID, productID) VALUES (?, ?)');
            $stmt->execute(array($idCondition, $idProduct));
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    static function deleteConditionOfProduct(PDO $db , int $idProduct){
        try{
            $stmt = $db->prepare('Delete from Condition_OF_PRODUCT where productID = ?');
            $stmt->execute(array($idProduct));
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>