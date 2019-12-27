<?php
    include_once 'functions.php';
    include_once 'config/config.php';
    
    $string = file_get_contents("data/review.json");
    $json_review = json_decode($string, true);            
    $review = $json_review[0]['review'];    
        
    $db = getConnection();
    $stmt = $db->prepare("SELECT * FROM orders WHERE id=".$_GET['id']);
    $stmt->execute();            
    $stmt->setFetchMode(PDO::FETCH_ASSOC);         

    $order = $stmt->fetchAll();              
    $order = $order[0];
    
    $query = "SELECT * FROM goods_sold WHERE id_order = ".$_GET['id']; 
    $db = getConnection();
    $stmt = $db->prepare($query);
    $stmt->execute();            
    $stmt->setFetchMode(PDO::FETCH_ASSOC);         
    
    $solds = $stmt->fetchAll();
    $soldQuantity = ["","","","","","","",""];
    $soldDescription = ["","","","","","","",""];
    $soldTotal = ["","","","","","","",""];      

    $indexSold = 0;
    foreach ($solds as $sold) {
      $soldQuantity[$indexSold] = $sold['quantity'];
      $soldDescription[$indexSold] = $sold['description'];
      $soldTotal[$indexSold] = $sold['total'];
      $indexSold++;        
    }

    $technic = '';
    /*$db = getConnection();
    $stmt = $db->prepare("SELECT * FROM technicians WHERE id=".$order['technic']);
    $stmt->execute();            
    $stmt->setFetchMode(PDO::FETCH_ASSOC);         

    $res = $stmt->fetchAll();              
    if (isset($res[0])){
        $technic = $res[0];            
    }*/
    
    /*
     *Definicion de todas las variales del recibo
     */
    $left = 10;
    $top = 15;

    $row1 = "
        <div style='text-align: center; width: 100%'>".
            $companyHeader
        ."</div>                            
    ";
    $row2 = "
        <div style='text-align: center;padding: 15;'><strong>VENTA</strong></div>        
    ";
    $row3 = "
        <div>
            <div class='' style='width: 10%; float: left'>FECHA:</div>
            <div class='' style='border-bottom: 1px solid black; width: 10%; float: left'>&nbsp;".date('d/m/Y')."</div>
            <div class='' style='width: 60%; float: left'>&nbsp;</div>
            <div class='' style='width: 10%; float: left'>N° de Control:</div>
            <div class='' style='border-bottom: 1px solid black; width: 10%; float: left'>&nbsp;".$order['id']."</div>
        </div>                
    ";
    $row4 ='
        <div>
            <div class="" style="width: 20%;float: left;">NOMBRE DEL CLIENTE:</div>
            <div class="" style="border-bottom: 1px solid black;width: 80%;float: left;">&nbsp;'.$order['client'].'</div>
        </div>                
    ';
    $row5 = '
        <div>
            <div class="" style="width: 5%; float: left">C.I.:</div>
            <div class="" style="border-bottom: 1px solid black; width: 25%; float: left">&nbsp;'.$order['ci'].'</div>
            <div class="" style="width: 20%; float: left">TELÉFONO CONTACTO:</div>
            <div class="" style="border-bottom: 1px solid black; width: 50%; float: left">&nbsp;'.$order['phone'].'</div>                
        </div>                
        <div>&nbsp;</div>
        <div>&nbsp;</div>
    ';
    $row6_1 = '
        <div>    
        <table border="1" width="90%" align="center">
            <tr><th width="10%">CANT</th><th>DESCRIPCION</th><th width="15%">TOTAL</th></tr>';

    $row6_2 = '';            
    for($i=0;$i<8;$i++) {
        $row6_2 .= '<tr><td style="text-align: center">'.$soldQuantity[$i].'&nbsp;</td><td>&nbsp;&nbsp;'.$soldDescription[$i].'</td><td style="text-align: right">'.$soldTotal[$i].'&nbsp;</td></tr>';
    }
    $row6_3 = '
        </table>
        </div>                
    ';
    $row6 = $row6_1.$row6_2.$row6_3;    
    $row7 = '
        <div>&nbsp;</div>
        <div>
            <div class="" style="width: 10%; float: left">&nbsp;</div>                    
            <div class="" style="width: 10%; float: left">GARANTÍA:</div>
            <div class="" style="border-bottom: 1px solid black; width: 10%; float: left">&nbsp;(&nbsp;&nbsp;&nbsp;) DÍAS</div>
            <div class="" style="width: 30%; float: left">&nbsp;</div>            
            <div class="" style="width: 15%; float: left">TOTAL PAGADO:</div>
            <div class="" style="border-bottom: 1px solid black; width: 15%; float: left">&nbsp;'.$order['cost'].'</div>                
            <div class="" style="width: 10%; float: left">&nbsp;</div>                        
        </div>                
    ';
    $row8 = '
        <div>
            <div class="" style="width: 15%; float: left">CONDICIONES:</div>
            <div class="" style="border-bottom: 1px solid black; width: 85%; float: left;">&nbsp;'.$order['observations'].'</div>                                                
        </div>                            
    ';
    $row9 = '
        <div>
            <div class="" style="width: 20%; float: left">FALLA SEGÚN CLIENTE:</div>  
            <div class="" style="border-bottom: 1px solid black; width: 80%; float: left;">&nbsp;'.$order['problem'].'</div>                                                
        </div>    
    ';
    $row10 = '
        <div>
            <div class="" style="width: 100%; float: left"><u>CONDICIONES DE SERVICIO:</u></div>
        </div>
        <div style="float: left">
            <ol>
                <li>LA REVISIÓN NO ES REEMBOLSABLE, TODA REVISIÓN TIENE UN COSTO DE <span class="money" data-a-dec="," data-a-sep="."></span></li>
                <li>EL DOCUMENTO VALIDO PARA RETIRAR EL EQUIPO ES LA ORDEN DE SERVICIO TÉCNICO Ó CÉDULA DE IDENTIDAD</li>
                <li>POR POLÍTICAS DE LA EMPRESA LOS CAMBIOS DE MICA, TÁCTIL Y PANTALLAS (LCD) SON A RIESGO DEL CLIENTE. POR LO TANTO ACEPTA LAS CONDICIONES ESTABLECIDAS</li>
                <li>NO NOS HACEMOS RESPONSABLES POR PANTALLAS PARTIDAS, ÉQUIPOS APAGADOS Y DAÑOS OCULTOS</li>
            </ol>
        </div>                
        <div>
            <div class="" style="width: 100%; float: left"><u>GARANTÍA:</u></div>
        </div>
        <div style="float: left">
            <ol>
                <li>LAS PANTALLAS Y TÁCTIL, TIENEN 72 HORAS DE GARANTÍA A PARTIR DE LA FECHA SIEMPRE Y CUANDO SE ENCUENTRE EN EL MISMO ESTADO QUE SALIÓ DE LA EMPRESA. (LAS GARANTÍAS DE REPARACIONES SON DE LUNES A JUEVES SIN EXCEPCIÓN)</li>
                <li>SERÁ TOMADO COMO PERDIDA DE GARANTÍA LA REPARACIÓN, SI EL EQUIPO PRESENTA SULFATO, GOLPES O USO INDEBIDO</li>
                <li>DESPUÉS DE 30 DÍAS EL EQUIPO EN LA EMPRESA, NO SE HACE RESPONSABLE POR EL MISMO</li>
            </ol>
        </div>                    
    ';
    $review = ($order['no_review']==0)?'Si':'No';
    $guarantee = ($order['guarantee']=='0')?'&nbsp;&nbsp;&nbsp;&nbsp;':$order['guarantee'];
    $row11 = '
        <div>
            <div class="" style="width: 40%; float: left">EQUIPO INGRESA SIN PREVIA REVISIÓN DE FUNCIONES</div>
            <div class="" style="border-bottom: 1px solid black; width: 5%; float: left">&nbsp;'.$review.'</div>                                                                
            <div class="" style="width: 10%; float: left">&nbsp;</div>
            <div class="" style="width: 30%; float: left">MONTO DE LA REPARACIÓN Y/O REVISIÓN:</div>
            <div class="money" data-a-dec="," data-a-sep="." style="border-bottom: 1px solid black; width: 15%; float: left">'.$order['cost'].'</div>                                
        </div>
        <div><div class="" style="width: 100%; float: left">&nbsp;</div></div>
        <div>
            <div class="" style="width: 10%; float: left">GARANTÍA:</div>
            <div class="" style="border-bottom: 1px solid black; width: 10%; float: left">&nbsp;('.$guarantee.') DÍAS</div>
            <div class="" style="width: 10%; float: left">TÉCNICO:</div>
            <div class="" style="border-bottom: 1px solid black; width: 25%; float: left">&nbsp;'.$technic.'</div>
            <div class="" style="border-bottom: 1px solid white; width: 45%; float: left">&nbsp;</div>

        </div>            
        <div>
            <div class="display" style="width: 30%; float: left">&nbsp;</div>
            <div class="display" style="border-bottom: 1px solid black;width: 40%; float: left">&nbsp;</div>
            <div class="display" style="border-bottom: 1px solid white; width: 30%; float: left">&nbsp;</div>
        </div>                        
        <div>
            <div class="" style="width: 30%; float: left">&nbsp;</div>
            <div class="" style="width: 40%; float: left; text-align: center">FIRMA Y CÉDULA DEL CLIENTE</div>
            <div class="" style="width: 30%; float: left">&nbsp;</div>
        </div>                                    
        <div>
            <div class="" style="width: 10%; float: left">RECIBIDO POR</div>
            <div class="" style="border-bottom: 1px solid black; width: 25%; float: left">&nbsp;'.$order['receipt_by'].'</div>                
            <div class="" style="width: 55%; float: left; text-align: center">&nbsp;</div>
            <div class="" style="width: 10%; float: left">PATRON</div>
        </div>  
    ';

    //print_r($order);
    /*header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment; filename=orden.doc");*/
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="public/css/bootstrap.min.css"> 
        <script src="public/js/jquery-1.12.4.js"></script>
        <script src="public/js/autonumeric@4.1.0"></script>
        <script src="public/js/autoNumeric.js"></script>        
        <style>
            div{
                margin-bottom: 2px;
            }
            
            .display{
                padding-top: 15px;
            }

            th {
                text-align: center;
                font-size: 12px;
            }

            td {
                font-size: 12px;
            }

            th, td {
                padding: 3px;
            }                        
        </style>
    </head>
        <body style="font-size: 12" onload="window.print(); window.close();">
        <!--<body style="font-size: 12">-->
        <div class="container-fluid" style="border: 1px black solid; width: 210mm; height: 135mm; padding: 10px; margin: 0">
            <img src="images/logo.png" alt="logo" width="160" height="50" style="
                position:  absolute;
                left: <?=$left?>px;
                top: <?=$top?>px;
            ">
            <br>
            <br>
            <?=$row2?>
            <?=$row3?>            
            <?=$row4?>      
            <?=$row5?>      
            <?=$row6?>      
            <?=$row7?> 
            <div style="padding-top:30px">&nbsp;</div>
            <?=$row1?>            
        </div>
        
        <div style="padding: 10px"></div>
        
        <div class="container-fluid" style="border: 1px black solid; width: 210mm; height: 135mm; padding: 10px; margin: 0">
            <img src="images/logo.png" alt="logo" width="160" height="50" style="
                position:  absolute;
                left: <?=left?>px;
                top: <?=535+$top?>px;
            ">
            <br>
            <br>
            <?=$row2?>
            <?=$row3?>            
            <?=$row4?>      
            <?=$row5?>      
            <?=$row6?>      
            <?=$row7?> 
            <div style="padding-top:30px">&nbsp;</div>
            <?=$row1?>
        </div>        
    </body>
</html>

<script language="javascript">    
    $( document ).ready(function(){      
        $(".money").autoNumeric('init', { currencySymbol: '', digitGroupSeparator: '.', decimalCharacter: ','});
    });        
</script>




