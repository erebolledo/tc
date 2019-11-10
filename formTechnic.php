<?php 
    include_once 'functions.php';
    
    $technic = ['id'=>'', 'name'=>''];
            
    if (isset($_GET['id'])) {
        $query = "SELECT * FROM technicians WHERE id = ".$_GET['id']; 
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute();            
        $stmt->setFetchMode(PDO::FETCH_ASSOC);         
        
        $technic = $stmt->fetchAll();              
        $technic = $technic[0];
        
        $new = false;    
    }        
    //print_r($order);            
?>
<thead>
    <tr>
        <th style="text-align: center">
            Técnico
        </th>
    </tr>
</thead>
<tbody class="small">                                                            
    <tr>
        <td>
            <input type="hidden" id="id_technic" name="id_technic" value="<?=$technic['id']?>" >          
            <div class="form-group" style="margin-bottom: 0px">
                <label class="control-label col-sm-3" for="email">Nombre:</label>
                <div class="col-sm-9">
                    <input class="form-control input-sm" id="name" name="name" value="<?=$technic['name']?>">
                </div>
            </div>                            
        </td>
    </tr>
    <tr><td style="padding: 0px"><input type="submit" class="btn btn-success btn-block" value="Aceptar"></td></tr>            
</tbody>    


