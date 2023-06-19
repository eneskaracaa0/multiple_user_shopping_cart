<?php

   try
    {
	//PDO Nesnesi. Parametreler: dsn,username,passwd
        $db = new PDO('mysql:host=localhost;dbname=shop','root',''); 
        $message= 'başarılı';
    }
    catch (PDOException $e)
    {
        //bağlantı başarısız olursa hatayı yakalayıp bilgi alıyoruz.
       $message= $e -> getMessage();
    }





?>