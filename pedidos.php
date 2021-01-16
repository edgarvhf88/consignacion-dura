<!DOCTYPE html>
<html lang="en">
<head>
<?php// include("data/conexion.php"); 
// if ($_SESSION["logged_user"] == ''){ header('Location: login.html'); }
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

<!-- Header -->
<header class="hero overlay">
    <nav class="navbar">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="fa fa-bars"></span>
            </button>
            <a href="index.html" class="brand">
                <img src="assets/images/logo-allpart.png" alt="Knowledge">
            </a>
        </div>
        <div class="navbar-collapse collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <!-- <li>
                    <a href="/">
                        Home
                    </a>
                </li>
                <li>
                    <a href="archive.html">
                        Archive
                    </a>
                </li>
                <li>
                    <a href="browse.html">
                        Browse
                    </a>
                </li> -->
                <li>
                    <a href="login.html">
                        <?php// echo Nombre($_SESSION["logged_user"]); ?>
                    </a>
                </li>
                <li>
                     <a href="pedidos.html  " type="button" class="btn btn-primary nav-btn">Pedido</a>
                </li>
                <li>
                     <a href="data/logout.php" type="button" class="btn btn-success nav-btn">cerrar</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
    <div class="masthead single-masthead">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <form>
                        <input type="text" id="txt_buscar" class="search-field" placeholder="Busqueda inteligente ... "/>
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>
                <div class="col-md-4">
                    <a href="#" class="btn btn-hero">
                        <img src="assets/images/LOGOGEN.png"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<!--
<div class="breadcrumbs">
    <div class="container">
        <ol>
            <li><a href="/">Home</a></li>
            <li><a href="archive.html">Knowledge Base</a></li>
            <li class="active">Explore Topics</li>
        </ol>
    </div>
</div>
-->

<!-- Topics -->

<section class="topics">
    <div class="container">
        <div class="row">
            <div class="col-lg-12"  id="area_resultados">

                <header>
                    <h2></i><span class="fa fa-list-alt"></span> Pedidios</h2>                    
                </header>

                <div class="row">

                    <!--Modal de pedido -->

                    <div class="modal fade" id="pedido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <!-- Header de la ventana -->
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h3 class="modal-title">
                                            Pedido
                                        </h3>
                                    </div>
                                    <!-- Contenido de la ventana -->
                                    <div class="modal-body">
                                        <p class="h4">Modal para pedido.</p>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <tr class="info">
                                                    <th>Clave</th>
                                                    <th>Nombre</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                </tr>
                                             
                                            </table>
                                        </div>
                                    </div>
                                    <!-- Footer de la ventana -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary btn-lg">Pedido</button>
                                        <button type="button" class="btn btn-success btn-lg" data-dismiss="modal">Continuar</button>
                                    </div>
                                    
                                </div>
                            </div>
                    </div>

                    <!--Final Modal de pedido -->

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                    	
                        <tr class="info">
                    		<th>Fecha</th>
                    		<th>Folio</th>
                    		<th>Estatus</th>
                    		<th>Total</th>
                    	</tr>
                    	<tr href="#pedido" data-toggle="modal">
                    
                    		<td>Fecha</td>
                    		<td>Folio</td>
                    		<td>Estatus</td>
                    		<td>Total</td>
                    	</tr>
                    	<tr>
                            <td>Fecha</td>
                            <td>Folio</td>
                            <td>Estatus</td>
                            <td>Total</td>
                        </tr>
                    	<tr>
                            <td>Fecha</td>
                            <td>Folio</td>
                            <td>Estatus</td>
                            <td>Total</td>
                        </tr>
                    </table>
                </div>

                </div>
                <div class="row">
                	<a href="#ventana1" class="btn btn-primary btn-lg" data-toggle="modal">Enviar</a>
                	<div class="modal fade" id="ventana1" tabindex="-1">
                		<div class="modal-dialog">
                			<div class="modal-content">
                				<!-- Header de la ventana -->
                				<div class="modal-header">
                					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                					<h3 class="modal-title">
                						Pedido
                					</h3>
                				</div>
                				<!-- Contenido de la ventana -->
                				<div class="modal-body">
                					<p class="h4">Articulo agregado satisfactoriamente al pedido.</p>
                				</div>
                				<!-- Footer de la ventana -->
                				<div class="modal-footer">
                					<button type="button" class="btn btn-primary btn-lg">Pedido</button>
                					<button type="button" class="btn btn-success btn-lg" data-dismiss="modal">Continuar</button>
                				</div>
                				
                			</div>
                		</div>
                	</div>
                </div>
            </div>

            <!-- Sidebar 
            <div class="col-lg-4">
                <div class="sidebar">
                    <div class="widget widget-support-forum">
                        <span class="icon icon-forum"></span>
                        <h4>Looking for help? Join Community</h4>

                        <p>Couldn’t find what your are looking for ? Why not join out support forums and let us help
                            you.</p>


                        <a href="#" class="btn btn-success">Support Forum</a>
                    </div>

                    <div class="pt-50">
                        <div class="widget fix widget_categories">
                            <span class="icon icon-folder"></span>
                            <h4>Popular Knowledgebase Topics</h4>
                            <ul>
                                <li><a href="#"> Installation & Activation </a></li>
                                <li><a href="#"> Premium Members Features </a></li>
                                <li><a href="#"> API Usage & Guide lines </a></li>
                                <li><a href="#"> Getting Started & What is next. </a></li>
                                <li><a href="#"> Installation & Activation </a></li>
                                <li><a href="#"> Premium Members Features </a></li>
                                <li><a href="#"> API Usage & Guide lines </a></li>
                                <li><a href="#"> Getting Started & What is next. </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>-->
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
</body>
</html>



