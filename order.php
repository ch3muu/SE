<!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <title> C's Closet </title>
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
         <img src="C:\xampp\htdocs\eCommerce\Images\CLogo.png" width="120px">
        
    </div>

      <nav>
         <ul>
            <li><a href="http://localhost/eCommerce/homepage.php">Home</a></li>
            <li><a href="http://localhost/eCommerce/products.php">My Shop</a></li>
            <li><a href="http://localhost/eCommerce/cart.php">My Cart</a></li>
            <li><a href="http://localhost/eCommerce/wallet.php">Wallet</a></li>
            <li><a href="http://localhost/eCommerce/Registration.php">Register</a></li>
            <li><a href="http://localhost/eCommerce/Login.php">Login</a></li>
         </ul>
        </nav>
    </div>
     
    </div>
    </div>
<form method="post" action="order_submit.php">
    <fieldset class="fieldset">
        <h1>Order</h1>
        <ul>
 <legend>Item</legend>
 <div>
<label for=item> Jacket </label>
<input type="radio" id="item" name="item" value="Jacket"></div>
<label for="quantity" class="quantity"> Quantity</label>
<input type="number" id="quantity" name="quantity" placeholder="Enter the quantity">
<input type="submit" name="submit" value="Place order">
        </ul>
    </fieldset>
    </form>



 </body>
 </html>