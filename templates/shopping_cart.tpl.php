<?php 
declare(strict_types = 1);

require_once(__DIR__ . '/../classes/session.class.php');
require_once(__DIR__ . '/../classes/shopping_cart.class.php');
require_once(__DIR__ . '/../classes/product.class.php');
require_once(__DIR__ . '/../utils/product_utils.php');

function drawShoppingCart(PDO $db, $session){
    //draws the shopping cart page

    $userID = $session->getId();
    $productsInCart = ShoppingCart::getUserShoppingCart($db, $userID);
    $products = array();
    foreach($productsInCart as $productInCart){
        $productDB = Product::getProduct($db, $productInCart->getProductID());
        if($productDB !== null){ 
            $products[] = $productDB;
        }
    }
    ?>
    <section id = "shoppingCart">
        <header>
            <h1>Your Cart</h1>
        </header>
        <?php if(empty($products)){
            ?>
            <article><p>No products in the cart</p></article>
        <?php } else{
            drawProductsListInCart($products);
            ?>
            <div id = "buyButton">
            <a href = "/../pages/buy.php">BUY</a>
        </div>
       <?php } ?>
        

    </section>
<?php }

function drawProductsListInCart($products){
    //draws the list of products in the cart
    ?>
    <article class = "productsTable">
            <table>
                <tr><th>Name</th><th>Price</th><th>Delete</th></tr>
                <?php foreach($products as $product){
                    ?>
                    <tr id = "<?= $product->getId()?>"><td><a href = "/../pages/products.php/?id=<?=$product->getId()?>"><p><?= $product->getName() ?></p></a></td><td class = "price"><?= $product->getPrice()?></td><td><button type="button" class="delButton">Delete</button></td></tr>
                <?php } ?>
                <tr><th>Total</th><td id="totalPrice"><?= calculatePrice($products) ?></td><td></td></tr>
            </table>
        </article>
<?php }

function drawBuy(){
    //draws the buy page (subpage inside the shopping cart)
    ?>
    <section id="buy">
        <header>
            <h1>Finalize the buy</h1>
        </header>
        <article>
            <form action = "/../actions/action_buy.php" method = "post" id = "payment">
                <label>
                    <p>Payment Method</p>
                    <select name= "payment" id = "paymentMethod">
                        <option value="account">Bank Account</option>
                        <option value="card">Card</option>
                    </select>
                </label>
                <label>
                    <p>Account number</p>
                    <input type="text" required = "true">
                </label>
                <label>
                    <p>Address</p>
                    <input type="text" name = "address" required>
                </label>
                <input type="submit" value = "Buy">
                <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
            </form>
        </article>
    </section>
<?php }