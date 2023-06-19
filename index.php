<?php
require_once('config.php');
session_start();
$user_id =$_SESSION['id'];

if(!isset($user_id)){
    header('location:login.php');
    $message='Yetkisiz erişim';
};
if(isset($_GET['logout'])){
    unset($user_id);
    session_destroy();
     header('location:login.php');

}

if(isset($_POST['add_to_cart'])){
    $product_name=$_POST['product_name'];
    $product_price=$_POST['product_price'];
    $product_image=$_POST['product_image'];
    $product_quantity=$_POST['product_quantity'];

    $select_cart=$db->prepare('SELECT * FROM cart WHERE name=? AND user_id=? ');
    $select_cart->execute(array($product_name,$user_id));

    if($select_cart->rowCount() > 0){
        $message='Product already added to cart!';
    }else{
        $insert_cart=$db->prepare('INSERT INTO cart(user_id,name,price,image,quantity) VALUES(?,?,?,?,?)');
        $insert_cart->execute([$user_id,$product_name,$product_price,$product_image,$product_quantity]);
         $message='Product  added to cart!';

    }
}


?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Shopping Cart</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/style.css">
        <style>
            .container {
    padding: 0 20px;
    margin: 0 auto;
    max-width: 1200px;
    padding-bottom: 70px;
}

.container .user-profile {
    
    padding: 20px;
    text-align: center;
    background-color: white;
    border: 1px solid #000;
    box-shadow: rgba(0, 0, 0, 1);
    border-radius: 5px;
    margin: 20px auto;
    max-width: 500px;

}
.container .user-profile p{
    margin-bottom: 15px;
    font-size: 20px;
    color: #000;
}
.container .user-profile p span{
    color: red;
}
.container .user-profile .flex{
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    column-gap: 10px;
    align-items:flex-end ;
}
.container .products .box-container{
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
}
.container .products .box-container .box{
    text-align: center;
    border-radius: 5px;
    border: 1px solid #000;
    position: relative;
    padding: 20px;
    background-color: white;
    width: 300px;
    

}

.container .products .box-container .box img{
    height: 250px;
}

.container .products .box-container .box .name{
    font-size: 20px;
    color: #000;
    text-transform: uppercase;
}
.container .products .box-container .box .price{
    position: absolute;
    top: 10px;left: 10px;
    padding: 5px 10px;
    border-radius:5px ;
    background-color: #e63946;
    color: #fff;
    font-size: 15px;

}
.container .products .box-container .box input[type="number"]{
margin: 10px 0;
width:100% ;
border:1px solid #000 ;
font-size: 20px;
color: #000;
padding: 10px 14px;
}
.container .shopping-cart{
    padding: 20px 0;

}
.container .shopping-cart table{
    width: 100%;
    text-align: center;
    border: 1px solid #000;
    border-radius: 5px;
    background-color: #fff;
}
.container  .shopping-cart table thead{
    background-color: #000;
    
}
.container  .shopping-cart table thead th{
    color: #fff;
    padding: 10px 15px;
}
.container  .shopping-cart table .table-bottom{
background-color: lightblue;
}
.container  .shopping-cart table tr td{
    padding: 10px;
    font-size: 20px;
    color: #000;
}



        </style>
    </head>
    <body>

     <?php
   
   if (isset($message)) {
    
        echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
    
}
    ?>

    <div class="container">
        <div class="user-profile">
            <?php
            $select_user=$db->prepare('SELECT * FROM users_form WHERE id=?');
            $select_user->execute(array($user_id));
            if($select_user->rowCount() > 0){
                $row=$select_user->fetch(PDO::FETCH_ASSOC);
            }
            ?>
            <p>username :<span><?php echo $row['name'];?></span> </p>
             <p>email :<span><?php echo $row['email'];?></span> </p>
              <div class="flex">
            <a href="login.php" class="btn">login</a>
            <a href="register.php" class="option-btn">register</a>
            <a href="index.php?logout=<?php echo $user_id;?>" onclick="return confirm('are your sure want to logout');" class="delete-btn">logout</a>
        </div>
        </div>

        <div class="products">
            <div class="box-container">
                 <?php
            $select_product=$db->prepare('SELECT * FROM products ');
            $select_product->execute();
            if($select_product->rowCount() > 0){
                while ( $row=$select_product->fetch(PDO::FETCH_ASSOC)) {
                 ?>  
                 <form action="" method="post" class="box">
            <img src="images/<?php echo $row['image'];?>" alt="product">
            <div class="name"><?php echo $row['name'];?></div>
            <div class="price"><?php echo $row['price'];?> $</div>
            <input type="number" min="1" name="product_quantity" value="1">
            <input type="hidden" name="product_image" value="<?php echo $row['image'];?>">
            <input type="hidden" name="product_name" value="<?php echo $row['name'];?>">
            <input type="hidden" name="product_price" value="<?php echo $row['price'];?>">
            <input type="submit" value="add to cart" name="add_to_cart" class="btn">
        </form>
                 <?php
                   }
               
            }
           
                 ?>
              
       
        
                

        
           
            
           
            </div>
        </div>

        <div class="shopping-cart">
            <h1 class="heading">shopping cart</h1>

            <table>
                <thead>
                    <th>image</th>
                    <th>name</th>
                    <th>price</th>
                    <th>quantity</th>
                    <th>total price</th>
                    <th>action</th>
                </thead>
                <tbody>
    <?php
            $cart_query=$db->prepare('SELECT * FROM cart WHERE user_id=? ');
            $cart_query->execute(array($user_id));
           
                while ( $fetch_cart=$cart_query->fetch(PDO::FETCH_ASSOC)) {
                 ?>  
                 <tr>
                    <td><img src="images/<?php echo $fetch_cart['image'];?> " height="100" width="80"></td>
                      <td><?php echo $fetch_cart['name'];?></td>
                    <td>$<?php echo $fetch_cart['price'];?>-</td>
                    
                    <td>
                        <form action="" method="POST">
                            <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id'];?>">
                            <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity'];?>">
                            <input type="submit" name="update_cart"value="update" class="option-btn">
                        </form>
                    </td>
                    <td>$<?php echo $sub_total=number_format($fetch_cart['price']*$fetch_cart['quantity']);?>-</td>
                    <td> <a href="index.php?remove=<?php echo $fetch_cart['id']; ?> "class='delete-btn' onclick="return confirm('remove item from cart?')">Remove</a></td>

                    
                </tr>

                  
                
                 

                 <?php }; ?>

                 <?php
                 $grand_total +=$sub_total;
                 ?>
                 <tr class="table-bottom">
                    <td colspan="4">grand total : </td>
                    <td><?php echo $grand_total; ?></td>
                    <td><a href="index.php?delete_all" onclick="return confirm('delete all from cart?')" class="delete-btn">Delete All</a></td>
                 </tr>
                
                </tbody>
                
            </table>
        </div>



    </div>
       
        
        <script src="" async defer></script>
    </body>
</html>