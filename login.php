<?php include("data/conexion.php"); 
if ($_SESSION["logged_user"] <> ''){ /*  header('Location: index.php'); */ 
echo '<script> window.location.replace("login.php"); </script>';}

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AllPart</title>
    <link rel="shortcut icon" type="image/png" href="https://catalogo.allpart.mx/assets/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="css/iofrm-style.css">
    <link rel="stylesheet" type="text/css" href="css/iofrm-theme2.css">
</head>
<body>
<script type="text/javascript">


	function login(){
			<!-- var	iduser = "<?php echo $_SESSION["logged_user"] ?>" -->
			
			
			var usuario = document.getElementById("username").value;
			var contrasena = document.getElementById("password").value;
			
			jQuery.ajax({ //
				type: "POST",
				url: "data/login.php",
				data: {contrasena:contrasena,usuario:usuario},
				success: function(response)
				
				{
						if (response == 0) // usuario no existe
						{
						alert('El usuario no existe');
						}
						else if (response == 1) // acceso consedido
						{
						$(location).attr('href','index.php'); 
						}
						else if (response == 2) // contrasena incorrecta
						{
						alert('contrasena incorrecta');
						}
						
						
					
				//alert("Se registro pedido con exito!");
					//jQuery('#modal1 .modal-body').html(response);
				}
			});
		};
</script>

    <div class="form-body">
        <div class="website-logo">
            <a href="index.php">
                <div class="logo">
                    
                </div>
            </a>
        </div>
        <div class="row">
            <div class="img-holder">
                <div class="bg"></div>
                <div class="info-holder">

                </div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        <h3>Panel de acceso.</h3>
                        <p>Digite su nombre de usuario o correo electronico y contraseña.</p>
                        <div class="page-links">
                            <a href="login.php" class="active">Login</a>
                        </div>
                        <div>
                            <input class="form-control" type="text" name="username" id="username" placeholder="Usuario o Correo electronico" required>
                            <input class="form-control" type="password" name="password" id="password" placeholder="contraseña" required>
                            <div class="form-button">
                                <button id="login" type="buttom" class="ibtn" onclick="login() ">Accesar</button> <!-- <a href="forget2.html">Forget password?</a> -->
                            </div>
                        </div>
						
                       <!--  <div class="other-links">
                            <span>Or login with</span><a href="#">Facebook</a><a href="#">Google</a><a href="#">Linkedin</a>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<script>$(document).ready(function(){
$('#password').keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        login();
    }
});
}); </script>

</body>
</html>