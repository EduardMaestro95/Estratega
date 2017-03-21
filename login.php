<!doctype html>
<html>
<head>
     <style>
        .margenes{
            position: relative;
            margin: auto;
            width: 984px;
            height: 758px;
            border: 3px solid;
            border-color: black;
            background-color: #959595;
        }
        .ventanas{
            position: relative;
            top: 60px;
            left: 50px
        }
        .boton_login{
            position: relative;
            left: 73px;
            border-color: black
        }
        .frase_registro{
            position: relative;
            left: 30px;
            top: 80px
        }
        .borde_negro{border-color: black}
        .blanco{color: white}
        .naranja{color: orange}
    </style>
    <title>Estratega</title>
    <link rel="icon" type"image/png" href="favicon.png">
</head>
<?php
$error="";
if(isset($_POST["submit"])){
	$user=$_POST['user'];
	$pass=$_POST['pass'];

	$con=mysqli_connect('localhost','root','') or die(mysqli_error());
	mysqli_select_db($con, 'estratega') or die("cannot select DB");

	$query=mysqli_query($con, "SELECT * FROM registro WHERE username='".$user."' AND password='".$pass."'");
	$numrows=mysqli_num_rows($query);
	if($numrows!=0)
	{
	while($row=mysqli_fetch_assoc($query))
	{
	$dbusername=$row['username'];
	$dbpassword=$row['password'];
	}

	if($user == $dbusername && $pass == $dbpassword)
	{
	session_start();
	$_SESSION['Suser']=$user;

	/* Redirect browser */
	  header("Location: principal.php");
	}
	} else {
	  $error="Invalid username or password";
	}
}
?>
<script lenguage="JavaScript">
    function todoRellenado (){
      if(document.getElementById(1).value == "" || document.getElementById(2).value == ""){
        document.getElementById(3).innerHTML = "All fields are required";
        return false;
      }
      else{
        return true;
      }
    }
</script>
<body BGCOLOR=#404040>
<form action="" method="POST" onsubmit="return todoRellenado()">
<div class="margenes">
    <div class="ventanas">
        Username: <input type="text" name="user" class="borde_negro" id="1"><br /><br />
        Password: &nbsp<input type="password" name="pass" class="borde_negro" id="2"><br /><br />
        <input class="boton_login" type="submit" value="Login" name="submit" /><br /><br />
    </div>
    <div class="frase_registro">
        If you don't have an account you have to <a href="register.php" class="blanco">register</a>.<br /><br />
        <msg class=naranja id=3><?php echo $error ?></msg>
    </div>
</div>
</form>
</body>
</html>