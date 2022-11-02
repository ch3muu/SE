<?php
require "dbconnect.php";

session_start();

if (!isset($_SESSION['userID'])) {
    $_SESSION['msg'] = "You are not logged in!";
    header('location: Login.php');
}

$user_id = $_SESSION['userID'];
$sql;

if (isset($_GET['past'])) {
    $sql = "SELECT tbl_products.product_name, tbl_products.product_image,tbl_orderdetails.orderdetails_id, tbl_orderdetails.order_quantity, tbl_orderdetails.product_price, tbl_orderdetails.orderdetails_total, tbl_orderdetails.order_id
    FROM tbl_orderdetails
    INNER JOIN tbl_products ON tbl_orderdetails.product_id=tbl_products.product_id INNER JOIN tbl_orders ON tbl_orderdetails.order_id=tbl_orders.order_id
    where tbl_orderdetails.is_deleted=1 or tbl_orders.order_status='completed' and tbl_orders.customer_id='$user_id'";
} else {
    $sql = "SELECT tbl_products.product_name, tbl_products.product_image,tbl_orderdetails.orderdetails_id, tbl_orderdetails.order_quantity, tbl_orderdetails.product_price, tbl_orderdetails.orderdetails_total, tbl_orderdetails.order_id, tbl_orders.order_amount
    FROM tbl_orderdetails
    INNER JOIN tbl_products ON tbl_orderdetails.product_id=tbl_products.product_id INNER JOIN tbl_orders ON tbl_orderdetails.order_id=tbl_orders.order_id
    where tbl_orderdetails.is_deleted=0 and tbl_orders.order_status='pending payment' and tbl_orders.customer_id='$user_id'";
}

$result = mysqli_query($conn, $sql);

if (isset($_GET['orderdetailsID'])) {
    $orderdetailsID = $_GET['orderdetailsID'];
    $orderID = $_GET['orderID'];
    $amount = $_GET['singleOrderAmount'];
    $orderquery = "update tbl_orders set order_amount=order_amount-'$amount' where order_id='$orderID'";
    $deletequery = "delete from tbl_orderdetails where orderdetails_id='$orderdetailsID'";
    mysqli_query($conn, $orderquery);
    mysqli_query($conn, $deletequery);
    header('location: cart.php');
}

?>
<?php
$checkWall = "select amount_available from wallets where customer_id='$user_id'";
$checkBal = mysqli_query($conn, $checkWall);
$balRow = mysqli_fetch_assoc($checkBal);
// echo $balRow['amount_available'];
?>

<?php

if (isset($_GET['singleCheckoutID'])) {
    $order_checkout_id = $_GET['singleCheckoutID'];
    $item_amount = $_GET['itemAmount'];
    $order_id = $_GET['orderID'];
    $checkoutquery = "update tbl_orderdetails set is_deleted=1 where orderdetails_id='$order_checkout_id'";
    $updateOrder = "update tbl_orders set order_amount=order_amount-'$item_amount' where order_id='$order_id'";

    $checkWallet = "select amount_available from wallets where customer_id='$user_id'";
    $updateWallet = "update wallets set amount_available=amount_available-'$item_amount' where customer_id='$user_id'";
    $checkBalance = mysqli_query($conn, $checkWallet);
    $balRows = mysqli_fetch_assoc($checkBalance);
    if ($balRows['amount_available'] < $item_amount || !$checkBalance) {
        //You can add a banner or prompt to inform the user that their balance is insufficient
        die("Insufficient Balance");
        exit();
    }
    if (mysqli_query($conn, $checkoutquery)) {
        mysqli_query($conn, $updateOrder);
        mysqli_query($conn, $updateWallet);
        echo "<p>Payment Successfull</p>";
        header('location: cart.php');
    } else {
        echo "<p>Failed to Checkout</p>";
    }
}

if (isset($_GET['allCheckoutID'])) {
    $checkout_id = $_GET['allCheckoutID'];
    $totalAmount = $_GET['totalAmount'];

    $completeOrder = "update tbl_orders set order_amount=order_amount-'$totalAmount', order_status='completed', is_deleted=1 where order_id='$checkout_id'";

    $checkWallet = "select amount_available from wallets where customer_id='$user_id'";
    $updateWallet = "update wallets set amount_available=amount_available-'$totalAmount' where customer_id='$user_id'";
    $checkBalance = mysqli_query($conn, $checkWallet);
    $balRows = mysqli_fetch_assoc($checkBalance);
    if ($balRows['amount_available'] < $totalAmount || !$checkBalance) {
        //You can add a banner or prompt to inform the user that their balance is insufficient
        die("Insufficient Balance");
        exit();
    }
    if (mysqli_query($conn, $completeOrder)) {
        mysqli_query($conn, $updateWallet);
        echo "<p>Payment Successfull</p>";
        header('location: cart.php');
    } else {
        echo "<p>Failed to Checkout</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title> My Cart </title>
    <link rel="icon" href="ProductImages/CLogo.png">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;700&family=Didact+Gothic&family=Edu+TAS+Beginner&family=Edu+VIC+WA+NT+Beginner:wght@400;500;700&family=Fredoka+One&display=swap" rel="stylesheet">
</head>

<body>
    <div class="header">
        <div class="container">
            <div class="nav-area">
                <div class="logo">
                    <img src="ProductImages/CLogo.png" width="120px">
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
        </div>
    </div>


    <!--cart items details-->
    <?php
    if (!isset($_GET['past'])) {
        echo "<a href=\"cart.php?past=true\">View Past Purchases</a>";
    } else {
        echo "<a href=\"cart.php\">View Current Cart</a>";
    }
    ?>

    <div class="small-container cart-page">
        <p>Balance : <?php echo $balRow['amount_available'] ?></p>
        <?php
        if (mysqli_num_rows($result) > 0) {
        ?>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <?php if (!isset($_GET['past'])) { ?>
                        <th></th>
                    <?php } ?>
                </tr>
                <?php
                while ($rows = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td>
                            <div class="cart-info">
                                <img src="./ProductImages/<?php echo $rows['product_image']; ?>">
                                <div>
                                    <p><?php echo $rows['product_name']; ?></p>
                                    <small>Price:Ksh <?php echo $rows['product_price']; ?></small>
                                    <br>
                                    <a title="Remove from Cart" href="cart.php?orderdetailsID=<?php echo $rows['orderdetails_id']; ?>&orderID=<?php echo $rows['order_id']; ?>&singleOrderAmount=<?php echo $rows['orderdetails_total']; ?>">Remove</a>
                                </div>
                            </div>
                        </td>

                        <td><?php echo $rows['order_quantity']; ?></td>
                        <td>Ksh. <?php echo $rows['orderdetails_total']; ?></td>
                        <?php if (!isset($_GET['past'])) { ?>
                            <td><a href="cart.php?singleCheckoutID=<?php echo $rows['orderdetails_id']; ?>&itemAmount=<?php echo $rows['orderdetails_total']; ?>&orderID=<?php echo $rows['order_id']; ?>">Checkout Item</a></td>
                        <?php } ?>
                    </tr>
                <?php
                }
                ?>

            </table>

            <?php if (!isset($_GET['past'])) { ?>
                <div class="total-price">
                    <table>
                        <?php
                        $finalsql = "SELECT tbl_orderdetails.order_id,tbl_orders.order_amount
                    FROM tbl_orderdetails
                    INNER JOIN tbl_products ON tbl_orderdetails.product_id=tbl_products.product_id INNER JOIN tbl_orders ON tbl_orderdetails.order_id=tbl_orders.order_id
                    where tbl_orderdetails.is_deleted=0 and tbl_orders.customer_id='$user_id' limit 1";
                        $finalResut = mysqli_query($conn, $finalsql);
                        $finalRow = mysqli_fetch_assoc($finalResut);
                        $total = $finalRow['order_amount'];
                        $totalShip = $total + 500;
                        ?>
                        <tr>
                            <td>
                                <h5>Subtotal</h5>
                            </td>
                            <td>ksh <?php echo $total ?></td>
                        </tr>
                        <tr>
                            <td>Shipping</td>
                            <td>ksh 500</td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td>ksh <?php echo $totalShip ?></td>
                        </tr>
                        <button class="checkout-btn"><a href="cart.php?allCheckoutID=<?php echo $finalRow['order_id']; ?>&totalAmount=<?php echo $totalShip; ?>">Checkout All</a></button>
                    </table>
                </div>
            <?php } ?>

        <?php } else { ?>
            <p>You have no pending orders</p>
            <img src="ProductImages/empty-cart.png" alt="Empty Cart">
        <?php } ?>

    </div>

</body>

</html>