<?php
require "dbconnect.php";

if (isset($_GET['category'])) {
    $category = $_GET['category'];
    $filterquery =  "SELECT tbl_products.product_id, tbl_products.product_name, tbl_products.product_image, tbl_products.unit_price, tbl_products.available_quantity, tbl_subcategories.subcategory_name
    FROM tbl_products
    INNER JOIN tbl_subcategories ON tbl_products.subcategory_id=tbl_subcategories.subcategory_id where tbl_subcategories.category='$category'";
    $result = mysqli_query($conn, $filterquery);
    // header('location: user_products.php');
} else {
    $sql = "SELECT tbl_products.product_id, tbl_products.product_name, tbl_products.product_image, tbl_products.unit_price, tbl_products.available_quantity, tbl_subcategories.subcategory_name
FROM tbl_products
INNER JOIN tbl_subcategories ON tbl_products.subcategory_id=tbl_subcategories.subcategory_id;";
    $result = mysqli_query($conn, $sql);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title> Products </title>
    <link rel="icon" href="ProductImages/CLogo.png">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;700&family=Didact+Gothic&family=Edu+TAS+Beginner&family=Edu+VIC+WA+NT+Beginner:wght@400;500;700&family=Fredoka+One&display=swap" rel="stylesheet">
</head>

<body>
    <div class="nav-area">
        <div class="logo">
            <img src="ProductImages\CLogo.png" width="120px">
        </div>

        <nav>
            <ul>
                <li><a href="homepage.php">Home</a></li>
                <li><a href="shop.php">My Shop</a></li>
                <li><a href="cart.php">My Purchases</a></li>
                <li><a href="wallet.php">Wallet</a></li>
                <li><a href="Registration.php">Register</a></li>
                <li><a href="Login.php">Login</a></li>
            </ul>
        </nav>
    </div>

    <!--featured products-->
    <div class="small container">
        <h2 class="title"> Products</h2>
        <div class="row">
            <?php
            while ($rows = mysqli_fetch_assoc($result)) {
            ?>
                <div class="col-4">
                    <a href="product_details.php">
                        <img src="./ProductImages/<?php echo $rows['product_image']; ?>">
                    </a>
                    <a href="product_details.php?productID=<?php echo $rows['product_id']; ?>">
                        <h4><?php echo $rows['product_name']; ?></h4>
                    </a>
                    <p>Ksh. <?php echo $rows['unit_price']; ?></p>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

</body>

</html>