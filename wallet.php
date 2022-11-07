<?php

require "dbconnect.php";

session_start();

$userID = $_SESSION['userID'];
$selectquery = "select * from tbl_users where user_id='$userID'";
$result = mysqli_query($conn, $selectquery);
$rows = mysqli_fetch_assoc($result);

if (isset($_POST['add_wallet'])) {
    $walletBalance = $_POST['balance'];
    $addWallet = "insert into wallets (customer_id,amount_available, created_at, updated_at, is_deleted) values
     ('$userID', '$walletBalance', now(), now(), 0)";

    if (mysqli_query($conn, $addWallet)) {
        echo "Success";
        header('location: wallet.php');
    } else {
        echo "Error:" . mysqli_error($connector);
    }
}

if (isset($_POST['update_wallet'])) {
    $deposit = $_POST['deposit'];
    $updateWallet = "update wallets set amount_available=amount_available+'$deposit', updated_at=now() where customer_id='$userID'";

    if (mysqli_query($conn, $updateWallet)) {
        echo "Success";
        header('location: wallet.php');
    } else {
        echo "Error:" . mysqli_error($conn);
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['userID']);
    header('location: Login.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title> My Wallet </title>
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
    <h3>Your Information:</h3>
    <p>First Name : <?php echo $rows['first_name'] ?></p><br>
    <p>Last Name : <?php echo $rows['last_name'] ?></p><br>
    <p>Email : <?php echo $rows['email'] ?></p><br>
    <p>Username : <?php echo $rows['username'] ?></p><br>
    <p>Gender : <?php echo $rows['gender'] ?></p><br>
    <p>Date of Birth : <?php echo $rows['dob'] ?></p><br>
    <button><a href="wallet.php?logout='1'">LOGOUT</a></button>

    <?php
    $walletQuery = "select * from wallets where customer_id = '$userID'";
    $walletPresent = mysqli_query($conn, $walletQuery);
    $walletRows = mysqli_fetch_assoc($walletPresent);

    if (mysqli_num_rows($walletPresent) == 1) {
    ?>
        <br><br><h3>Wallet Details</h3>
        <p>Last Update: <?php echo $walletRows['updated_at'] ?></p><br>
        <p>Current Balance:<?php echo $walletRows['amount_available'] ?></p><br>
        <form action="wallet.php" method="post">
            <label>Deposit Cash in KSH</label><br>
            <input type="number" name="deposit" placeholder="Amount in KSH">
            <button type="submit" name="update_wallet">Make Deposit</button>
        </form>
    <?php
    } else {
    ?>
        <form action="wallet.php" method="post">
            <h4>Add Wallet</h4>
            <label>Deposit Amount</label><br>
            <input type="number" name="balance" placeholder="Amount in KSH">
            <button type="submit" name="add_wallet">Add Wallet</button>
        </form>

    <?php
    }
    ?>

</body>

</html>