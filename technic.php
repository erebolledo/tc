<?php
    /*session_start();
    if (!isset($_SESSION['role']))
        header('Location: login.php');*/
?>
<?php include_once 'header.php'?>
<?php $init_date = (isset($_GET['date'])?$_GET['date']:date('Y-m-d'))?>

        <div class="container-fluid" style="margin-top: 70px">
            <div class="row">
                <div class="col-md-4 col-md-push-8"> 
                    <form class="form-horizontal" method="POST" action="saveTechnic.php" onsubmit="return verify_form()">                    
                        <table class="table table-bordered table-condensed" id="form_technic">
                            <?php include_once 'formTechnic.php';?>
                        </table>                        
                    </form>    
                </div>                
                <div class="col-md-8 col-md-pull-4">
                    <div>
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="col-sm-2">ID</th>    
                                    <th class="col-sm-9">Tecnico</th>
                                    <th class="col-sm-1" style="text-align: center">Operaciones</th>
                                </tr>
                            </thead>
                            <tbody id="list_orders">
                                <?php include_once 'listTechnicians.php'?>                                                   
                            </tbody>                                
                        </table>                                        
                    </div>
                </div>
            </div>
        </div>
        <?php include_once 'footer.php';?>