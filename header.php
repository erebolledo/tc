<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    setlocale(LC_ALL,"es_ES");
    $string = file_get_contents("data/status.json");
    $json_status = json_decode($string, true);
    include_once 'config/config.php';        
?>    

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?=$companyTitle?></title>
        <script src="public/js/jquery-1.12.4.js"></script>
        <script src="public/js/jquery-ui.js"></script>
        <script src="public/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="public/css/bootstrap.min.css">
        <script src="public/js/autonumeric@4.1.0"></script>
        <script src="public/js/jquery.loadingModal.min.js" type="text/javascript"></script>
        <script src="public/js/sweetalert.min.js" type="text/javascript"></script>
        <link rel="stylesheet" href="public/css/jquery.loadingModal.min.css" type="text/css" />
        <script src="public/js/autoNumeric.js"></script>
        <link href="public/css/style.css" rel="stylesheet" type="text/css"/>       
        <link rel="icon" type="image/png" href="images/9EQsu.png">  
        <script src="public/js/jquery.priceformat.min.js"></script>    
    </head>
    <body>
        <header>
            <nav class="navbar navbar-inverse navbar-fixed-top">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                          <span class="sr-only">Toggle navigation</span>
                          <span class="icon-bar"></span>
                          <span class="icon-bar"></span>
                          <span class="icon-bar"></span>
                        </button>
                        <a href="/tc" class="navbar-brand">
                            <h1 style="font-weight: bolder;color: #01274b; color: white; margin: 0px;">
                                <?=$companyName?>
                            </h1>
                        </a>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="/tc/order.php">Ordenes</a></li>
                            <li><a href="/tc/technic.php">Técnicos</a></li>
                            <li><a href="/tc/book.php">Libro Diario</a></li>
                            <li><a href="#">Equipos</a></li>
                            <li><a href="/tc/logout.php">Salir</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div><!--/.container-fluid -->
            </nav>                        
        </header>