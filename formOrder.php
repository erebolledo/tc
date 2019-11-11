<?php 
    include_once 'functions.php';
    
    $string = file_get_contents("data/status.json");
    $json_status = json_decode($string, true);    
    
    $string = file_get_contents("data/receipt.json");
    $json_receipt = json_decode($string, true);    

    $string = file_get_contents("data/review.json");
    $json_review = json_decode($string, true);            
    $review = $json_review[0]['review'];
    
    $string = file_get_contents("data/payment_method.json");
    $json_payment_method = json_decode($string, true);        
            
    $order = ['id'=>'','client'=>'','status'=>'','cost'=>0,'technic'=>'','observations'=>'', 'total'=>'',
        'phone'=>'','equipment'=>'','imei'=>'','accesories'=>'','receipt_by'=>'','problem'=>'', 'powered_off'=>1,
        'no_review'=>1,'payment_method'=>'','bank'=>'','reference'=>'','ci'=>'','receipt'=>'', 'password'=>'', 'guarantee'=>'',
        'product'=>'', 'code'=>'', 'reason'=>'reparation','cost_technic_service'=>0];
    
    $new = true;
    $succesful = false;
    $id_order = 0;
    $print_disabled = ($id_order==0)?'':'disabled';
    
    if (isset($_GET['id'])) {
        $query = "SELECT * FROM orders WHERE id = ".$_GET['id']; 
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute();            
        $stmt->setFetchMode(PDO::FETCH_ASSOC);         
        
        $order = $stmt->fetchAll();              
        $order = $order[0];

        $id_order = $_GET['id'];
        $new = false;    
    }        

    $query = "SELECT * FROM technicians"; 
    $db = getConnection();
    $stmt = $db->prepare($query);
    $stmt->execute();            
    $stmt->setFetchMode(PDO::FETCH_ASSOC);         

    $technicians = $stmt->fetchAll();              

    if ($_POST){

        $order_post = $_POST;   

        $order_post['client'] = (empty($order_post['client']))?'':$order_post['client'];
        $order_post['ci'] = (empty($order_post['ci']))?'':$order_post['ci'];
        $order_post['phone'] = (empty($order_post['phone']))?'':$order_post['phone'];
        $order_post['password'] = (empty($order_post['password']))?'':$order_post['password'];    
        $order_post['equipment'] = (empty($order_post['equipment']))?'':$order_post['equipment'];
        $order_post['imei'] = (empty($order_post['imei']))?'':$order_post['imei'];
        $order_post['accesories'] = (empty($order_post['accesories']))?'':$order_post['accesories'];
        $order_post['receipt_by'] = (empty($order_post['receipt_by']))?'':$order_post['receipt_by'];        
        $order_post['problem'] = (empty($order_post['problem']))?'':$order_post['problem'];
        $order_post['observations'] = (empty($order_post['observations']))?'':$order_post['observations'];
        $order_post['cost'] = str_replace(['Bs. ', '.'], "", $order_post['cost']);
        $order_post['cost'] = str_replace([','], ".", $order_post['cost']);
        //$order_post['cost'] = (empty($order_post['cost']))?0:str_replace(['Bs. ', '.', ',00'], "", $order_post['cost']);    
        $order_post['powered_off'] = (empty($order_post['powered_off']))?0:$order_post['powered_off'];
        $order_post['no_review'] = (empty($order_post['no_review']))?0:$order_post['no_review'];
        $order_post['total'] = (empty($order_post['total']))?0:$order_post['total'];
        $order_post['payment_method'] = (empty($order_post['payment_method']))?'':$order_post['payment_method'];            
        $order_post['bank'] = (empty($order_post['bank']))?'':$order_post['bank'];
        $order_post['reference'] = (empty($order_post['reference']))?'':$order_post['reference'];
        $order_post['select_status'] = (empty($order_post['select_status']))?'Recibido':$order_post['select_status'];
        $order_post['guarantee'] = (empty($order_post['guarantee']))?0:$order_post['guarantee'];
        $order_post['product'] = (empty($order_post['product']))?'':$order_post['product'];
        $order_post['code'] = (empty($order_post['code']))?'':$order_post['code'];
        $order_post['reason'] = (empty($order_post['reason']))?'reparation':$order_post['reason'];
        $order_post['technic'] = (empty($order_post['technic']))?0:$order_post['technic'];
        $order_post['select_status'] = ($order_post['reason']=='sell')?'Entregado':$order_post['select_status'];
        $order_post['cost_technic_service'] = str_replace(['Bs. ', '.'], "", $order_post['cost_technic_service']);
        $order_post['cost_technic_service'] = str_replace([','], ".", $order_post['cost_technic_service']);        
        $order_post['perc_technic'] = (empty($order_post['perc_technic']))?0:$order_post['perc_technic'];

        if (empty($_POST['id_order'])){
            $sql = "INSERT INTO `orders` (`client`, `ci`, `phone`, `password`, `equipment`, `imei`, `accesories`, `receipt_by`, `status`, `technic`, "
                . "`problem`, `observations`, `cost`, `received_date`, `powered_off`, `no_review`, `total`, `payment_method`, `bank`, "
                . "`reference`, `guarantee`, `product`, `code`, `reason`, cost_technic_service, perc_technic) "
                . "VALUES ('".$order_post['client']."', '".$order_post['ci']."', '".$order_post['phone']."', '".$order_post['password']."', '".$order_post['equipment']."', "
                . "'".$order_post['imei']."', '".$order_post['accesories']."', '".$order_post['receipt_by']."', '".$order_post['select_status']."', ".$order_post['technic'].", "
                . "'".$order_post['problem']."', '".$order_post['observations']."', '".$order_post['cost']."', '".date("Y-m-d H:i:s")."', '".$order_post['powered_off']."', "
                . "'".$order_post['no_review']."', '".$order_post['total']."', '".$order_post['payment_method']."', '".$order_post['bank']."', '".$order_post['reference']."', '".$order_post['guarantee']."',"
                . "'".$order_post['product']."', '".$order_post['code']."', '".$order_post['reason']."', '".$order_post['cost_technic_service']."', '".$order_post['perc_technic']."')";        
            $succesful = $db->exec($sql);            
            $id_order  = $db->lastInsertId();
            $action = 'creada';
            
        }else{
            $sql = "UPDATE `orders` SET `client` = '".$order_post['client']."', `ci` = '".$order_post['ci']."', `phone` = '".$order_post['phone']."', "
                    . "`password` = '".$order_post['password']."', `equipment` = '".$order_post['equipment']."', `imei` = '".$order_post['imei']."', "
                    . "`accesories` = '".$order_post['accesories']."', `receipt_by` = '".$order_post['receipt_by']."', `status` = '".$order_post['select_status']."', "
                    . "`problem` = '".$order_post['problem']."', `observations` = '".$order_post['observations']."', `cost` = '".$order_post['cost']."', "
                    . "`powered_off` = '".$order_post['powered_off']."', `no_review` = '".$order_post['no_review']."', `total` = '".$order_post['total']."', "
                    . "`payment_method` = '".$order_post['payment_method']."', `bank` = '".$order_post['bank']."', `reference` = '".$order_post['reference']."', "
                    . "`technic` = '".$order_post['technic']."', `code` = '".$order_post['code']."', `guarantee` = '".$order_post['guarantee']."', "
                    . "`reason` = '".$order_post['reason']."', `product` = '".$order_post['product']."', `cost_technic_service` = '".$order_post['cost_technic_service']."', "
                    . "`perc_technic` = '".$order_post['perc_technic']."' WHERE `orders`.`id` = ".$order_post['id_order'];
            $succesful = $db->exec($sql);
            $id_order = $order_post['id_order'];            
            $action = 'editada';

        }        


        if (($order_post['select_status']=="Entregado")&&(empty($order_post['delivered_date']))){
            $sql = "UPDATE orders SET delivered_date = '".date("Y-m-d H:i:s")."' WHERE orders.id = ".$id_order;
            $db->exec($sql);            
        }
        
        if (($order_post['select_status']!="Entregado")&&(!empty($order_post['delivered_date']))){
            $sql = "UPDATE orders SET delivered_date = NULL WHERE orders.id = ".$id_order;
            $db->exec($sql);            
        }        

        if ($order_post['select_status']=="Garantía"){
            $sql = "UPDATE orders SET received_date = '".date("Y-m-d H:i:s")."', delivered_date = null,
                payment_method = null, bank = null, reference = null
                WHERE orders.id = ".$id_order;
            $db->exec($sql);            
        }

        $query = "SELECT * FROM orders WHERE id = ".$id_order; 
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute();            
        $stmt->setFetchMode(PDO::FETCH_ASSOC);         
        
        $order = $stmt->fetchAll();              
        $order = $order[0];        
        
        $order['id_order'] = $id_order;
        $order['id'] = $id_order;
    }    
    
    if (!empty($order['delivered_date'])){
        $delivered_date = new Datetime($order['delivered_date']);
        $delivered = " Entregado: ".$delivered_date->format('d/m/Y');        
    }else{
        $delivered = "";
    }

?>
<thead>
    <tr>
        <th style="text-align: center">
            Orden <?=($id_order==0)?'':$id_order.$delivered?>
            <span style="float: right">
                <!--<input class="btn btn-primary btn-xs" type="button" value="Nueva" onClick="window.location.reload()">-->
                <a class="btn btn-primary btn-xs" href="/tc/order.php" role="button">Nueva</a>
            </span>
        </th>
    </tr>
</thead>
<tbody class="small" style="background-color: #f5f5f5;">                                                            
    <tr>
        <td>
            <input type="hidden" id="id_order" name="id_order" value="<?=$order['id']?>" >
            <input type="hidden" id="delivered_date" name="delivered_date" value="<?=$order['delivered_date']?>" >
            <input type="hidden" id="message" name="message" value="<?=($succesful)?"La orden número $id_order fue $action de manera exitosa":""?>" >          
            <div class="form-group" style="margin-bottom: 0px">
                <label class="control-label col-xs-3" for="email">Nombre:</label>
                <div class="col-xs-9">
                    <input onkeyup="uppercase(this)" class="form-control input-sm" id="client" name="client" value="<?=$order['client']?>">
                </div>
            </div>                            
            <div class="form-group" style="margin-bottom: 0px">
                <label class="control-label col-xs-3" for="email">Cédula:</label>
                <div class="col-xs-3">
                    <input onkeyup="uppercase(this)" class="form-control input-sm" id="ci" name="ci" value="<?=$order['ci']?>">
                </div>

                <label class="control-label col-xs-2" for="email">Teléfono:</label>
                <div class="col-xs-4">
                    <input onkeyup="uppercase(this)" class="form-control input-sm" id="phone" name="phone" value="<?=$order['phone']?>">
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 0px">
                <label class="control-label col-xs-3" for="email" style="font-size: 10px" id="label_receipt"><?=($order['reason']=='sell')?'Vendido por:':'Recibido por:'?></label>
                <div class="col-xs-3">
                    <select class="form-control input-sm" id="receipt_by" name="receipt_by">
                        <option></option>
                    <?php foreach ($json_receipt as $receipt_name => $receipt):?>
                        <option <?=($order['receipt_by']===$receipt['name'])?'selected':''?>><?= $receipt['name']?></option>
                    <?php endforeach?>
                    </select>            
                </div>
                <label class="control-label col-xs-2" for="email">Motivo:</label>
                <div class="col-xs-4">
                    <select class="form-control input-sm" id="reason" name="reason">
                        <option value="reparation" <?=($order['reason']=='reparation')?'selected':''?>>Reparación</option>                        
                        <option value="sell" <?=($order['reason']=='sell')?'selected':''?>>Venta</option>                    
                    </select>            
                </div>
            </div>                              
            <div id="case_sell" style="<?=($order['reason']=='reparation')?'display: none':''?>">
                <div class="form-group" style="margin-bottom: 0px">
                    <label class="control-label col-xs-3" for="email">Producto:</label>
                    <div class="col-xs-9">
                        <input onkeyup="uppercase(this)" class="form-control input-sm" id="product" name="product" value="<?=$order['product']?>">
                    </div>
                </div>                                                    
                <div class="form-group" style="margin-bottom: 0px">
                    <label class="control-label col-xs-3" for="email">Codigo:</label>
                    <div class="col-xs-9">
                        <input onkeyup="uppercase(this)" class="form-control input-sm" id="code" name="code" value="<?=$order['code']?>">
                    </div>
                </div>                                                                    
            </div>
            <div id="case_reparation" style="<?=($order['reason']=='sell')?'display: none':''?>">
                <div class="form-group" style="margin-bottom: 0px">
                    <label class="control-label col-xs-3" for="email">Tecnico:</label>
                    <div class="col-xs-9">
                        <select class="form-control input-sm" id="technic" name="technic">
                            <option value="0"></option>
                        <?php foreach ($technicians as $technic):?>
                            <option <?=($order['technic']===$technic['id'])?'selected':''?> value="<?= $technic['id']?>"><?= $technic['name']?></option>
                        <?php endforeach?>
                        </select>            
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 0px">
                    <label class="control-label col-xs-3" for="email">Equipo:</label>
                    <div class="col-xs-9">
                        <input onkeyup="uppercase(this)" class="form-control input-sm" id="equipment" name="equipment" value="<?=$order['equipment']?>">
                    </div>
                </div>                                                    
                <div class="form-group" style="margin-bottom: 0px">
                    <label class="control-label col-xs-3" for="email">Clave:</label>
                    <div class="col-xs-3">
                        <input class="form-control input-sm" id="password" name="password" value="<?=$order['password']?>">
                    </div>                    
                    <label class="control-label col-xs-2" for="email">IMEI:</label>
                    <div class="col-xs-4">
                        <input class="form-control input-sm" id="imei" name="imei" value="<?=$order['imei']?>">
                    </div>
                </div>                                                                
                <div class="form-group" style="margin-bottom: 0px">
                    <label class="control-label col-xs-3" for="email">Accesorios:</label>
                    <div class="col-xs-9">
                        <textarea onkeyup="uppercase(this)" class="form-control input-sm" rows="2" id="accesories" name="accesories"><?=$order['accesories']?></textarea>                
                    </div>
                </div>                                                            
                <div class="form-group" style="margin-bottom: 0px">
                    <label class="control-label col-xs-3" for="email">Apagado:</label>
                    <div class="col-xs-3">
                        <label><input type="radio" name="powered_off" value="1" <?=($order['powered_off']==1)?'checked':''?>>Sí</label>
                        <label><input type="radio" name="powered_off" value="0" <?=($order['powered_off']==0)?'checked':''?>>No</label>
                    </div>
                    <label class="control-label col-xs-2" for="email">Revisado:</label>
                    <div class="col-xs-4">
                        <label><input type="radio" name="no_review" value="1" <?=($order['no_review']==1)?'checked':''?>>Sí</label>
                        <label><input type="radio" name="no_review" value="0" <?=($order['no_review']==0)?'checked':''?>>No</label>
                    </div>
                </div>                                  
                <div class="form-group" style="margin-bottom: 0px">
                    <label class="control-label col-xs-3" for="email">Condiciones:</label>
                    <div class="col-xs-9">
                        <textarea onkeyup="uppercase(this)" class="form-control input-sm" rows="2" id="observations" name="observations" maxlength="100"><?=$order['observations']?></textarea>
                    </div>
                </div>                                                                
                <div class="form-group" style="margin-bottom: 0px">
                    <label class="control-label col-xs-3" for="email">Falla:</label>
                    <div class="col-xs-9">
                        <textarea onkeyup="uppercase(this)" class="form-control input-sm" rows="2" id="problem" name="problem" maxlength="200"><?=$order['problem']?></textarea>
                    </div>
                </div>                                                                
                <div class="form-group" style="margin-bottom: 0px">
                    <label class="control-label col-xs-3" for="email">Garantía:</label>
                    <div class="col-xs-3">
                        <input class="form-control input-sm" type="number" id="guarantee" name="guarantee" 
                               style="" value="<?=($order['guarantee']==0)?'':$order['guarantee']?>" placeholder="días">
                    </div>
                    <label class="control-label col-xs-2" for="email">Estatus:</label>
                    <div class="col-xs-4">
                        <select class="form-control input-sm" id="select_status" name="select_status">
                        <?php foreach ($json_status as $status_name => $status):?>
                        <?php if ($order['status']===$status['name']):?>
                            <option selected><?= $status['name']?></option>
                        <?php else:?>
                            <option><?= $status['name']?></option>
                        <?php endif;?>
                        <?php endforeach?>
                        </select>            
                    </div>
                </div>
            </div>
            <div id="case_delivered" style="display: <?=($order['status']=='Entregado')?'block':'none'?>">
                <div class="form-group" style="margin-bottom: 0px">
                    <label class="control-label col-xs-3" for="email">Método pago:</label>
                    <div class="col-xs-9">
                        <select class="form-control input-sm" id="payment_method" name="payment_method">
                            <option></option>
                        <?php foreach ($json_payment_method as $payment_id => $payment_name):?>
                            <option <?=($order['payment_method']===$payment_name)?'selected':''?>><?= $payment_name?></option>
                        <?php endforeach?>
                        </select>            
                    </div>                    
                </div>                                                                                
                <div class="form-group" style="margin-bottom: 0px">
                    <label class="control-label col-xs-3" for="email">Banco:</label>
                    <div class="col-xs-9">
                        <input onkeyup="uppercase(this)"class="form-control input-sm" id="bank" name="bank" value="<?=$order['bank']?>">
                    </div>
                </div>                                                                                
                <div class="form-group" style="margin-bottom: 0px">
                    <label class="control-label col-xs-3" for="email">Referencia:</label>
                    <div class="col-xs-9">
                        <input class="form-control input-sm" id="reference" name="reference" value="<?=$order['reference']?>">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 0px">
                    <label class="control-label col-xs-3" for="email">Costo Serv. Téc.:</label>
                    <div class="col-xs-9">
                        <input class="form-control input-sm money" id="cost_technic_service" name="cost_technic_service" value="<?=$order['cost_technic_service']?>">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 0px">
                    <label class="control-label col-xs-3" for="email">% Técnico:</label>
                    <div class="col-xs-9">
                        <input class="form-control input-sm" id="perc_technic" type="number" name="perc_technic" value="<?=$order['perc_technic']?>" max="100">
                    </div>
                </div>                
            </div>
            <div class="form-group" style="margin-bottom: 0px">
                <label class="control-label col-xs-3" for="email">Costo Total:</label>
                <div class="col-xs-9">
                    <input class="form-control input-sm money" id="cost" name="cost" style="" value="<?=$order['cost']?>">
                </div>
            </div>                
        </td>            
    </tr>    
    <tr><td style="padding: 0px"><input type="submit" class="btn btn-success btn-block" value="Aceptar"></td></tr>  
    <tr>
        <td style="padding: 0px">
            <a href="printOrder.php?id=<?=$id_order?>" target="_blank" class="btn btn-info btn-block <?=($id_order==0)?'disabled':''?>" role="button">
                Imprimir
            </a>
            <!--<a href="#" onclick='window.open("printOrder.php?id=<?=$id_order?>");return false;' class="btn btn-info btn-block <?=($id_order==0)?'disabled':''?>" role="button">
                Imprimir
            </a> -->           
        </td>
    </tr>
</tbody>    

<script language="javascript">
    
    function uppercase(input){
        input.value = input.value.toUpperCase();
    }
    
    $( document ).ready(function(){ 
        console.log($('#message').val());
        if ($('#message').val()!='')
            swal("Info", $('#message').val(), "info");            

        $('.money').priceFormat({prefix: 'Bs. ', centsSeparator: ',', thousandsSeparator: '.'});
        //$(".money").autoNumeric('init', { currencySymbol: '', digitGroupSeparator: '.', decimalCharacter: ','});
        //new AutoNumeric('#cost', { currencySymbol: 'Bs. ', digitGroupSeparator: '.', decimalCharacter: ','});

        $('#select_status').change(function (){
            if ( $('#select_status').val()==="Entregado"){
                //$('#case_delivered').fadeIn();
                $('#case_delivered').show();
            }else{
                /*$('#payment_method').val('');
                $('#bank').val('');
                $('#reference').val('');*/
                //$('#guarantee').val('');
                //$('#case_delivered').fadeOut();                    
                $('#case_delivered').hide();                    
            }
        }); 

        $('#observations').keyup(function (){
            if ($('#observations').val().length==$('#observations').attr('maxlength')){
                //alert("Ha llegado al limite del campo 'Condiciones'");
                swal("Precaución", "Ha llegado al limite de caracteres del campo 'Condiciones'", "warning");            
            }              
        });             

        $('#problem').keyup(function (){
            if ($('#problem').val().length==$('#problem').attr('maxlength')){
                //alert("Ha llegado al limite del campo 'Falla'");
                swal("Precaución", "Ha llegado al limite de caracteres del campo 'Falla'", "warning");            
            }              
        });   
        
        $('#reason').change(function (){    
            if ($('#reason').val()=='sell'){                
                //$('#case_reparation').fadeOut();            
                //$('#case_sell').fadeIn();                
                $('#case_reparation').hide();            
                $('#case_sell').show();
                $('#case_delivered').show();   
                $('#label_receipt').text('Vendido por:');
            }
            
            if ($('#reason').val()=='reparation'){                
                //$('#case_sell').fadeOut();
                //$('#case_reparation').fadeIn();                
                $('#case_sell').hide();
                $('#case_reparation').show();
                if ( $('#select_status').val()!=="Entregado")                
                    $('#case_delivered').hide();                
                else                                 
                    $('#case_delivered').show();                                
                $('#label_receipt').text('Recibido por:');
            }                
        });                                             
    });    

</script>
