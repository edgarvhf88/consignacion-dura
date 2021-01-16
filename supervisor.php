<!DOCTYPE html>
<html lang="en">
<head>
<?php include("data/constructor.php"); 
if ((isset($_SESSION["logged_user"])) &&($_SESSION["logged_user"] == '')){ header('Location: login.php'); }
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
	header('Location: vendor.html');
	break;
	case 4:
	header('Location: index.php');
	break;
	case 5:
	//header('Location: supervisor.php');
	break;
}
/////////////codigo para validar empresa y cargar pagina con su estilo ////////////////////////////////////////////////////////////////////
$Display = '';			
$display_empresas = display_empresas();
$id_empresa_user_activo = id_empresa($_SESSION["logged_user"]);
foreach ($display_empresas as $id => $display_name){
	if ($id_empresa_user_activo == $id){
		$Display = $display_name;
		
	}
	//echo $id." ** ".id_empresa($_SESSION["logged_user"])."<br />"; 
};

include("displays/".$Display.".php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
				url: "data/pedidos_supervisados.php",
				data: {id_user:id_user},
				success: function(resultados)
				
				{ ////// 
								
				$("#area_resultados").html(resultados);		
		
				//alert("Se registro pedido con exito!");
					//jQuery('#modal1 .modal-body').html(response);
				}
			});
		return false;	
   
   
   };
function detalle_pedido(id,folio,total_pedido){
		//alert("Articulo ID= "+id);
		
		 
		  
			jQuery('#pedido_detalle').modal('show', {backdrop: 'static'});
			
			jQuery.ajax({ //
				type: "POST",
				url: "data/pedido_det.php",
				data: {id:id,folio:folio,total_pedido:total_pedido},
				success: function(resultados)
				{
					jQuery('#pedido_detalle .modal-body').html(resultados);
				}
			});
		};

</script>

<!-- Header -->

<?php  
echo $header_supervisor;
echo $container_supervisor;


?>



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
	 <script type="text/javascript" charset="utf8" src="assets/js/datatable.js"></script>
	
	<script>
	$("#div_new_empresa").hide();
	$("#div_new_departamento").hide();
	$("#div_new_puesto").hide();
	
	
	lista_pedidos();
	</script>
</body>
</html>



