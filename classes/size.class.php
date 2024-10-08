<?php
declare(strict_types=1);


class Size{
    private int $id;
    private string $name;

    public function __construct(int $id, string $name){
        $this->id= $id;
        $this->name= $name;

    }


    //getters
    public function getId() : int{
        return $this->id;
    }

    public function getName() : string{
        return $this->name;
    }

    //queries
    static public function getSizeById(PDO $db, int $id){
        try{
            $stmt = $db->prepare('SELECT * FROM SIZE WHERE sizeID = ?');
            $stmt->execute(array($id));
            $sizeDB = $stmt->fetch(); 

            return new Size(
                intval($sizeDB['sizeID']),
                $sizeDB['name']
            );
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    static public function getSizeByName(PDO $db, string $name){
        try{
            $stmt = $db->prepare('SELECT * FROM SIZE WHERE name = ?');
            $stmt->execute(array($name));
            $sizeDB = $stmt->fetch();
            
            return new Size(
                intval($sizeDB['sizeID']),
                $sizeDB['name']
            );
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    static public function getAllSizes(PDO $db){
        try{
            $stmt = $db->prepare('SELECT * FROM SIZE');
            $stmt->execute();
            $sizes = array();
            while($sizeDB = $stmt->fetch()){
                $size= new Size(intval($sizeDB['sizeID']),$sizeDB['name']);

                $sizes[]=$size;
            }

            return $sizes;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    static function getSizesOfProduct(PDO $db , int $id){
        try{
            $stmt = $db->prepare('SELECT sizeID FROM SIZE_OF_PRODUCT WHERE productID = ?');
            $stmt->execute(array($id));
            $sizes = array();
            while($sizeID = $stmt->fetch()){
                $size = Size::getSizeById($db,intval($sizeID['sizeID']));
                $sizes[] = $size;
            }
            return $sizes;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    static function insertSizeOfProduct(PDO $db , int $idProduct, int $idSize){
        try{
            $stmt = $db->prepare('INSERT INTO Size_OF_PRODUCT (sizeID, productID) VALUES (?, ?)');
            $stmt->execute(array($idSize, $idProduct));
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    static function deleteSizeOfProduct(PDO $db , int $idProduct){
        try{
            $stmt = $db->prepare('Delete from Size_OF_PRODUCT where productID = ?');
            $stmt->execute(array($idProduct));
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>