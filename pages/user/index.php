<?php
session_start();

if(isset($_SESSION["user_id"])){
    

?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>Welcome <?=$_SESSION["user_firstname"]." ".$_SESSION["user_lastname"]?> </h1>
        <a href="sign_out.php"><button>Sign_Out</button></a>
    </body>
</html>

<?php
}else{
    header("Location:../../");
}
    