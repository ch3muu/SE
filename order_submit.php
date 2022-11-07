<?php
$quantity=$_POST['quantity'];
echo"Order Succesful! Her is what you ordered";
for($q=1;$q<=$quantity;$q++){
    echo "<img src='M001.jpg'>";}
if (empty($quantity) || $quantity==0){
echo "http_response_code(400)";
}
?>