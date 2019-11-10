<?php 
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

    /*session_start();
    if (!isset($_SESSION['role']))
        header('Location: login.php');*/
?>
<?php include_once 'header.php'?>
<?php $init_date = (isset($_GET['date'])?$_GET['date']:date('Y-m-d'))?>

        <div class="container-fluid" style="margin-top: 70px">
            <div class="row">
                <div class="col-md-5 col-md-push-7"> 
                    <form class="form-horizontal" method="POST" action="order.php" onsubmit="return verify_form()">                    
                        <table class="table table-bordered table-condensed" id="form_order">
                            <?php include_once 'formOrder.php';?>
                        </table>                        
                    </form>    
                </div>                
                <div class="col-md-7 col-md-pull-5">
                    <div>
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="col-xs-2">Orden<input class="form-control input-sm" id="list_id_order" type="text" value=""></th>    
                                    <th class="col-xs-2">Cédula<input class="form-control input-sm" id="list_ci" type="text" value=""></th>
                                    <th class="col-xs-3">Ingreso<input class="form-control input-sm" style="width: 140px" id="list_received_date" type="date" value=""></th>                                    
                                    <th class="col-xs-2">Estatus
                                        <select class="form-control input-sm" id="list_status">
                                            <option>Todos</option>
                                        <?php foreach ($json_status as $status_name => $status):?>
                                            <option><?= $status['name']?></option>
                                        <?php endforeach?>
                                        </select>            
                                    </th>
                                    <th class="col-xs-3">Técnico</th>
                                </tr>
                                <tr>
                                    <th colspan="6" style="padding: 0px">
                                        <a class="btn btn-primary btn-block" href="/tc/order.php" role="button">Resetear filtros</a>
                                        <!--<input class="btn btn-primary btn-block" type="button" value="Resetear filtros" onClick="window.location.reload()">-->
                                    </th>
                                </tr>                                
                            </thead>
                            <tbody id="list_orders">
                                <?php include_once 'listOrders.php'?>                                                   
                            </tbody>                                
                        </table>                                        
                    </div>
                </div>
            </div>
        </div>
        <?php include_once 'footer.php';?>

