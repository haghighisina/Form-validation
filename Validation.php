<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        .error{color: red};
    </style>
    <script>
        function is_required(){
            var name = document.getElementById("name").value;
            var email = document.getElementById("email").value;
            if (name === ""){
                alert("name")
            }
            else if(email === ""){
                alert("email")
            }
        }
    </script>
</head>
</html>
<?php
$server = "localhost";
$name   = "root";
$pass   = "";
$dbName = "web";
$bace = mysqli_connect($server,$name,$pass,$dbName);
if (!$bace){
    die("connection failed :".mysqli_connect_error());
}
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name   =  test_input(mysqli_real_escape_string($bace, $_POST['name']));
    $email  =  test_input(mysqli_real_escape_string($bace, $_POST['email']));
    $select = "SELECT * FROM `user`";
    $con = mysqli_stmt_init($bace);
    $prepare = mysqli_stmt_prepare($con,$select);
    $execute = mysqli_stmt_execute($con);
    $result  = mysqli_stmt_get_result($con);
    if (empty($name)){
        $name_error = "name is required";
    }elseif(is_numeric($name)){
        $name_error = "name should not be number";
    }elseif (!preg_match("/^[a-zA-Z ]*$/",$name)){
        $name_error ="only valid name required";
    }elseif (empty($email)){
        $email_error = "email is required";
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $email_error = "only valid name required";
    }else {
        $insert = "INSERT INTO `user` (`name`, `email`) VALUES (?, ?)";
        mysqli_stmt_prepare($con, $insert);
        mysqli_stmt_bind_param($con, "ss", $name, $email);
        mysqli_stmt_execute($con);
        mysqli_stmt_close($con);
        $ok =  "sigh in";
    }
}
function test_input($data){
    
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = addslashes($data);
    $data = strip_tags($data);
    
    return $data;
}
?>
<div style="text-align: center;background-color: yellow">
    <form name="form" method="post" action="<?php htmlspecialchars(htmlentities("form.php"));?>" onsubmit="is_required()">
        <input type="text" name="name" id="name">
        <span class="error">*<?php if (isset($name_error)){ echo $name_error;};?></span><br>
        <input type="email" name="email" id="email">
        <span class="error">*<?php if (isset($email_error)){echo $email_error;};?></span><br>
        <input type="submit"> <?php if (isset($ok)){ echo $ok; }; ?>
    </form>
</div>


