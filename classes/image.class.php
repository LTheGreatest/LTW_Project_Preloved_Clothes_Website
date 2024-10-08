<?php
declare(strict_types=1);


class Image{
    private int $id;
    private string $path;
    

    public function __construct(int $id, string $path){
        $this->id= $id;
        $this->path= $path;
        

    }


    //getters
    public function getId() : int{
        return $this->id;
    }

    public function getPath() : string{
        return $this->path;
    }

    

    //queries
    static public function getImageById(PDO $db, int $id){
        try{
            $stmt = $db->prepare('SELECT * FROM IMAGES WHERE imageID = ?');
            $stmt->execute(array($id));
            $imageDB = $stmt->fetch(); 

            return new Image(
                intval($imageDB['imageID']),
                $imageDB['path']
            );
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    static public function getAllImages(PDO $db){
        try{
            $stmt = $db->prepare('SELECT * FROM IMAGES');
            $stmt->execute();
            $images = array();
            while($imageDB = $stmt->fetch()){
                $image= new Image(intval($imageDB['imageID']),$imageDB['path']);

                $images[]=$image;
            }

            return $images;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    static function getImagesPath(PDO $db, int $productID){
        try{
            $stmt = $db->prepare('
            SELECT IMAGES.path
            FROM IMAGES JOIN IMAGES_OF_PRODUCT on IMAGES.imageid = IMAGES_OF_PRODUCT.imageid
            WHERE  IMAGES_OF_PRODUCT.productid = ?
            ');
            $stmt->execute(array($productID));
        
            $images = array();
            while($imageDB = $stmt->fetch()){
                $image = $imageDB['path'];
                $images[] = $image;
            }
        
            return $images;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    static function insertImageToProduct(PDO $db, string $path){
        try{
            $stmt = $db->prepare('Insert into images(path) values (?)');
            $stmt->execute(array($path));
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    static function getLastInsertedImageID(PDO $db){
        try {
            $stmt = $db->prepare("SELECT imageID FROM IMAGES ORDER BY imageID DESC LIMIT 1");
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['imageID'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    static function insertImageOfProduct(PDO $db, int $imageID, int $productID){
        try{
            $stmt = $db->prepare('Insert into images_of_product(imageID, productID) values (?,?)');
            $stmt->execute(array($imageID, $productID));
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    static function deleteImageOfProduct(PDO $db, int $productID){
        try{
            $stmt = $db->prepare('Delete from images_of_product where productID = ?');
            $stmt->execute(array($productID));
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    static function deleteImage(PDO $db, int $imageID){
        try{
            $stmt = $db->prepare('Delete from images where imageID = ?');
            $stmt->execute(array($imageID));
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    static function getImagesIdFromImageOfProduct(PDO $db, int $productID){
        try{
            $stmt = $db->prepare('SELECT imageID FROM IMAGES_OF_PRODUCT WHERE productID = ?');
            $stmt->execute(array($productID));
            $images = array();
            while($image = $stmt->fetch()){
                $images[] = $image['imageID'];
            }
            return $images;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>