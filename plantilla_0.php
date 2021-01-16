<!DOCTYPE html>
<html lang="en">
<head>
<?php include("data/constructor.php"); 
if ((isset($_SESSION["logged_user"])) &&($_SESSION["logged_user"] == '')){ header('Location: login.html'); }
else { $tipo_usuario = validar_usuario($_SESSION["logged_user"]);}
/// $tipo_usuario = 0 == admin
switch($tipo_usuario)
{
	case 1:
	header('Location: admin.html');
	break;
	case 2:
	header('Location: index.html');
	break;
	case 3:
	//header('Location: vendor.html');
	break;
	
}


?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Catalogos-Allpart</title>

    <!-- CSS -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
	

</head>
<body class="archive">


<script>
     function lista_pedidos(){
  var id_user = <?php echo $_SESSION["logged_user"]; ?>
   
   jQuery.ajax({ //
				type: "POST",
				url: "data/lista_pedidos.php",
				data: {id_user:id_user},
				success: function(resultados)
				
				{ ////// 
								
				$("#div_mis_pedidos").html(resultados);		
				$("#area_resultados").html("");		
				$("#div_lista_pedido").html("");		
					
				//alert("Se registro pedido con exito!");
					//jQuery('#modal1 .modal-body').html(response);
				}
			});
		return false;	
   
   
   };


</script>

<!-- Header -->
<header class="hero overlay">
    <nav class="navbar">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="fa fa-bars"></span>
            </button>
            <a href="/index.html" class="brand">
                <img src="assets/images/logo-allpart.png" alt="Knowledge">
            </a>
        </div>
        <div class="navbar-collapse collapse" id="navbar-collapse">
            <?php echo $menu_bar; ?>
        </div>
    </div>
</nav>
    
</header>

<section class="topics">
    <div class="container">
        <div class="row">
            <div class="col-lg-12"  id="area_resultados">
				<h3>En construccion</h3>
               
              
			  </div>
			  <div class=""  id="resultados_js"></div>
			  <div id="pedidos" class=" col-lg-12"></div> 
			  
        </div>
    </div>
</section>


<footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-3">
                    <a href="#" class="brand">
                        <img src="assets/images/logo-f.png" alt="Knowledge">
                        <span class="circle"></span>
                    </a>
                </div>
                <div class="col-lg-7 col-md-5 col-sm-9">
                    <ul class="footer-links">
                        <li><a href="#">Buzon de sugerencias</a></li>
                        <li><a href="#">Contactos</a></li>
                        <li><a href="#">otro campo mas</a></li>	
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="copyright">
                        <p>© 2019 All Part Productos y Servicios</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="assets/js/jquery-1.12.3.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
	
	<script>
	$("#div_new_empresa").hide();
	$("#div_new_departamento").hide();
	$("#div_new_puesto").hide();
	
	mostrar_user();
	mostrar_empresas();
	</script>
</body>
</html>



