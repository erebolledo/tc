<?php include_once 'header.php'?>
<?php include_once 'functions.php'?>
<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

    /*if (!isset($_SESSION['role']))
        header('Location: login.php');*/
    
    $date = date('Y-m-d');
    
    if (isset($_POST['date'])){        
        $date = $_POST['date'];
    }    
    
    $query = "SELECT * FROM orders WHERE delivered_date BETWEEN '".$date." 00:00:00' AND '".$date." 23:59:59' AND status = 'Entregado'";
    $db = getConnection();
    $stmt = $db->prepare($query);
    $stmt->execute();            
    $stmt->setFetchMode(PDO::FETCH_ASSOC);  
    
    $orders = $stmt->fetchAll();
    
    $datex = new DateTime($date);    
    $monday = date('Y-m-d', strtotime('Monday this week', $datex->getTimestamp()));         
    $sunday = date('Y-m-d', strtotime('Sunday this week', $datex->getTimestamp()));         

    $monday_print = date('d-m-Y', strtotime('Monday this week', $datex->getTimestamp()));             
    $saturday_print = date('d-m-Y', strtotime('Saturday this week', $datex->getTimestamp()));             
    
    $query = "SELECT t.name as name, COUNT(o.technic) as services, round(SUM(o.cost_technic_service/100 * o.perc_technic),2) as gain " 
            ."FROM orders o "
            ."LEFT JOIN technicians t ON o.technic = t.id "
            ."WHERE delivered_date BETWEEN '".$monday." 00:00:00' AND '".$sunday." 23:59:59' AND status = 'Entregado' AND reason = 'reparation'"            
            ."GROUP by o.technic ";                
    $db = getConnection();
    $stmt = $db->prepare($query);
    $stmt->execute();            
    $stmt->setFetchMode(PDO::FETCH_ASSOC);  
    
    $perc_technics = $stmt->fetchAll();        
    
?>

    <div class="container-fluid" style="margin-top: 70px">

        <div class="row">         
            <div class="col-md-12">
                <div class="small">
                    <form method="post" action="book.php" id="form_book">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>                                    
                                    <th colspan="13" style="padding: 0px;">
                                        <div class="form-group">
                                            <label class="control-label col-xs-2" for="email">Libro del día:</label>
                                            <div class="col-md-2 col-xs-4">
                                                <input class="form-control input-sm" id="book_received_date" name="date" type="date" value="<?=$date?>">
                                            </div>
                                        </div>                                                                    
                                    </th>
                                </tr>                                                            
                                <tr>
                                    <th class="" style="width: 5%">ORDEN</th>    
                                    <th class="" style="width: 5%">TELÉFONO</th>    
                                    <th class="" style="width: 5%">CÉDULA</th>
                                    <th class="" style="width: 30%">PRODUCTO/FALLA</th>                                    
                                    <th class="" style="width: 5%">CODIGO</th>
                                    <th class="" style="width: 5%">MOTIVO</th>    
                                    <th class="" style="width: 5%">TECNICO</th>
                                    <th class="" style="width: 5%">MONTO</th>                                    
                                    <th class="" style="width: 5%">ESTATUS</th>      
                                    <th class="" style="width: 5%">INGRESO</th>                                    
                                    <th class="" style="width: 5%">PAGO</th>
                                    <th class="" style="width: 5%">BANCO</th>                                    
                                    <th class="" style="width: 5%">REFERENCIA</th>                                                                        
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order):?>
                                <tr>
                                    <td><?=$order['id']?></td>
                                    <td><?=$order['phone']?></td>
                                    <td><?=$order['ci']?></td>
                                    <td><?=$order['reason']=='reparation'?$order['problem']:$order['product']?></td>
                                    <td><?=$order['code']?></td>
                                    <td><?=($order['reason']=='reparation'?'Reparación':'Venta')?></td>
                                    <td><?=($order['reason']=='reparation')?getTechnicName($order['technic']):$order['receipt_by']?></td>
                                    <td class="money"><?=$order['cost']?></td>
                                    <td><?=$order['status']?></td>
                                    <td><?= getSpanishDate($order['received_date'])?></td>
                                    <td><?=$order['payment_method']?></td>
                                    <td><?=$order['bank']?></td>
                                    <td><?=$order['reference']?></td>
                                </tr>
                                <?php endforeach;?>
                                <?php if (count($orders)==0):?>
                                    <td colspan="13" align='center'>No existen ordenes egresadas para este día</td>
                                <?php endif;?>
                            </tbody>                                
                        </table>                                        
                    </form>    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <h3>Resumen</h3>
                <table class="table table-hover table-bordered">
                    <thead>
                        <th>Ventas</th>
                        <th>Reparaciones</th>
                        <th>Monto en ventas</th>
                        <th>Monto en reparaciones</th>
                        <th>Monto total</th>
                    </thead>
                    <tbody>
                        <td><?= getNumberDiary($date, 'COUNT','sell')?></td>
                        <td><?= getNumberDiary($date, 'COUNT','reparation')?></td>
                        <td class="money"><?= getNumberDiary($date, 'SUM','sell')?></td>
                        <td class="money"><?= getNumberDiary($date, 'SUM','reparation')?></td>
                        <td class="money"><?= getNumberDiary($date, 'SUM')?></td>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-6">
                <h3>Ganancia técnicos del <?=$monday_print?> al <?=$saturday_print?></h3>
                <table class="table table-hover table-bordered">
                    <thead>
                        <th>Técnico</th>
                        <th>Reparaciones</th>
                        <th>Ganancia</th>
                    </thead>
                    <tbody>
                        <?php foreach ($perc_technics as $perc_technic):?>
                        <tr>
                            <td><?= $perc_technic['name']?></td>
                            <td><?= $perc_technic['services']?></td>
                            <td class="money" data-a-dec="," data-a-sep="." data-a-sign="Bs. "><?= $perc_technic['gain']?></td>
                        </tr>
                        <?php endforeach?>
                        <?php if (count($perc_technics)==0):?>
                            <td colspan="3" align='center'>Vacio</td>
                        <?php endif;?>                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php include_once 'footer.php';?>

<script type='text/javascript'>
$(function(){
$('.date').datepicker({
});
});

    $( document ).ready(function(){      
        $(".money").autoNumeric('init', { currencySymbol: '', digitGroupSeparator: '.', decimalCharacter: ','});
    });

</script>