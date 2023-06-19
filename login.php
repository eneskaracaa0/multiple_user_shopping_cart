<?php
require_once('config.php');
session_start();

if(isset($_POST['submit'])){
   
    $email=$_POST['email'];
    $password=md5($_POST['password']);
   
        $select=$db->prepare('SELECT * FROM users_form WHERE  email=? AND password=? ');
     $select->execute(array($email,$password));
     if($select->rowCount() > 0){
        $row=$select->fetch(PDO::FETCH_ASSOC);
        echo $_SESSION['id']=$row['id'];
        header('Location:index.php');
        
       
     }else{
        $message='incorrect password';
       

     }
     

       
    }

?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Login</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>

    <?php
   
   if (isset($message)) {
    
        echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
    
}
    ?>

    <div class="form-container">

    <form action="" method="post">
        <h3>Login now</h3>
      
        <input type="text" name="email" required placeholder="enter email" class="box">
        <input type="password" name="password" required placeholder="enter password" class="box">
       
        <input type="submit" name="submit" class="btn" value="register now">
        <p>already have an account <a href="register.php">register now</a></p>


    </form>



    </div>
        
    
       
        
        <script src="" async defer></script>
    </body>
</html>