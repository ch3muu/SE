<ht<html>
<head>
    <meta charset="utf-8"/>
    <title>S&L </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="styles.css" rel="stylesheet">
    <!-- Google Font for Webpage-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Italianno&display=swap" rel="stylesheet">
</head>

  <section id="welcome">
        <div class="container">
            <h1>  MAVAZI</h1>
            
        </div>
    </section>


    <section id="welcomee">
        <div class="container">
        <h1> Dressing that makes you feel at home</h1>
            
           
        </div>
    </section>
    <section id="welcomee">
        <div class="container">
        <h1> YOUR CART</h1>
            
           
        </div>
    </section>


<?php
session_start();
$status="";
if (isset($_POST['action']) && $_POST['action']=="remove"){
    if(!empty($_SESSION["shopping_cart"])) {
        foreach($_SESSION["shopping_cart"] as $key => $value) {
          if($_POST["code"] == $key){
          unset($_SESSION["shopping_cart"][$key]);
          $status = "<div class='box' style='color:red;'>
          Product is removed from your cart!</div>";
          }
          if(empty($_SESSION["shopping_cart"]))
          unset($_SESSION["shopping_cart"]);
          }     
    }
}

if (isset($_POST['action']) && $_POST['action']=="change"){
  foreach($_SESSION["shopping_cart"] as &$value){
    if($value['code'] === $_POST["code"]){
        $value['available_quantity'] = $_POST["available_quantity"];
        break; // Stop the loop after we've found the product
    }
}
    
}
?>

<div class="cart">
<?php
if(isset($_SESSION["shopping_cart"])){
    $total_price = 0;
?>  
<table class="table">
<tbody>
<tr>
<td></td>
<td>ITEM NAME</td>
<td>QUANTITY</td>
<td>UNIT PRICE</td>
<td>ITEMS TOTAL</td>
</tr>   
<?php       
foreach ($_SESSION["shopping_cart"] as $product){
?>
<tr>
<td>
<img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($product['image']); ?>" />
</td>
<td><?php echo $product["product_name"]; ?><br />
<form method='post' action=''>
<input type='hidden' name='code' value="<?php echo $product["code"]; ?>" />
<input type='hidden' name='action' value="remove" />
<button type='submit' class='remove'>Remove Item</button>
</form>
</td>
<td>
<form method='post' action='ordered.php'>
<input type='hidden' name='code' value="<?php echo $product["code"]; ?>" />
<input type='hidden' name='product_name' value="<?php echo $product["product_name"]; ?>" />
<input type='hidden' name='unit_price' value="<?php echo $product["unit_price"]; ?>" />
<input type='hidden' name='action' value="change" />
<select name='available_quantity' class='available_quantity' onChange="this.form.submit()">
<option <?php if($product["available_quantity"]==1) echo "selected";?>
value="1">1</option>
<option <?php if($product["available_quantity"]==2) echo "selected";?>
value="2">2</option>
<option <?php if($product["available_quantity"]==3) echo "selected";?>
value="3">3</option>
<option <?php if($product["available_quantity"]==4) echo "selected";?>
value="4">4</option>
<option <?php if($product["available_quantity"]==5) echo "selected";?>
value="5">5</option>
<option <?php if($product["available_quantity"]==6) echo "selected";?>
value="6">6</option>
<option <?php if($product["available_quantity"]==7) echo "selected";?>
value="7">7</option>
</select>
<button type='submit'>Checkout</button>
</form>
</td>
<td><?php echo "$".$product["unit_price"]; ?></td>
<td><?php echo "$".$product["unit_price"]*$product["available_quantity"]; ?></td>
</tr>
<?php
$total_price += ($product["unit_price"]*$product["available_quantity"]);
}
?>
<tr>
<td colspan="5" align="right">
<strong>TOTAL: <?php echo "$".$total_price; ?></strong>
</td>
</tr>
</tbody>
</table>        
  <?php
}else{
    echo "<h3>Your cart is empty!</h3>";
    }
?>
</div>

<div style="clear:both;"></div>

<div class="message_box" style="margin:10px 0px;">
<?php echo $status; ?>
</div>