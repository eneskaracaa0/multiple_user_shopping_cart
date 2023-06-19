<?php
require_once('config.php');

if(isset($_POST['submit'])){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $password=md5($_POST['password']);
     $cpassword=md5($_POST['cpassword']);

     if($password===$cpassword){
        $select=$db->prepare('SELECT * FROM users_form WHERE  email=? AND password=? ');
     $select->execute(array($email,$password));
     if($select->rowCount() > 0){
        $message='user already exist!';

     }else{
        //new user ınsert
        $ınsert=$db->prepare('INSERT INTO users_form (name,email,password) VALUES(?,?,?)');
        $ınsert->execute(array($name,$email,$password));
        $message='registered successfly';
        header('location:login.php');

     }
     }else{
        $message='passwords do not match
';
     }

     


    
}

?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
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
        <h3>register now</h3>
        <input type="text" name="name" required placeholder="enter name" class="box">
        <input type="text" name="email" required placeholder="enter email" class="box">
        <input type="password" name="password" required placeholder="enter password" class="box">
        <input type="password" name="cpassword" required placeholder="enter confirm password" class="box">
        
        <input type="submit" name="submit" class="btn" value="register now">
        <p>don't have an account <a href="login.php">login now</a></p>
        


    </form>



    </div>
        
    
       
        
        <script src="" async defer></script>
    </body>
</html>