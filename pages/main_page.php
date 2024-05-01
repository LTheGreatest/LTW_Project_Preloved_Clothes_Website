<?php

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../database/database_connection.db.php');
require_once(__DIR__ . '/../classes/product.class.php');
require_once(__DIR__ . '/../utils/sessions.php');
require_once(__DIR__ . '/../database/images.php');
$db = getDatabaseConnection();
$session = new Session();
drawHeader($session);

?>  <header id= "main_header">
        <img src = "../assets/main_image.jpg" alt = "clothes">
        <h2>Buy and Sell Pre-loved Clothes</h2>
    </header>
    <section id = "main_products">
            <h4>Products for you</h4>
            <div id="selectProducts">
            
            <?php 
                
                $products = product::getProductByPrice($db,3,150);
                foreach($products as $product){ 
                    $imagePathArray = getImagesPath($db, $product->getId());
                    $imagePath = $imagePathArray[0];
                ?>
                <a href="">
                    <article>
                        <img src = "../<?= $imagePath ?>" alt = "product image" >
                        <p class= "product_name"><?= $product->getName();  ?></p>
                        <p class= "product_price"><?= $product->getPrice(); ?></p>
                    </article> 
                </a>   

            <?php } ?>
                
            
            
        </div>
    </section>

<?php 
    drawFooter();
?>