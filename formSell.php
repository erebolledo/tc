<?php $products = ['','','','','','','','']?>
<?php for ($i=0;$i<8;$i++):?>
<div class="form-group" style="margin-bottom: 0px">
    <label class="control-label col-xs-3" for="email">Articulo:</label>
    <div class="col-xs-2">
        <input onkeyup="uppercase(this)" class="form-control input-sm" placeholder="Cant"
        id="good_quantity<?=$i?>" name="good_quantity<?=$i?>" value="<?=$soldQuantity[$i]?>" type="number">
    </div>    
    <div class="col-xs-4">
        <input onkeyup="uppercase(this)" class="form-control input-sm" placeholder="Descripción"
        id="good_description<?=$i?>" name="good_description<?=$i?>" value="<?=$soldDescription[$i]?>">
    </div>
    <div class="col-xs-3">
        <input onkeyup="uppercase(this)" class="form-control input-sm money goodTotal"  placeholder="Total"
        id="good_total<?=$i?>" name="good_total<?=$i?>" value="<?=$soldTotal[$i]?>" onchange="calcSoldTotal(this)">
    </div>    
</div>                 
<?php endfor;?>                                                   
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
