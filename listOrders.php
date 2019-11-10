<?php 
    include_once 'functions.php';
    
    $offset = 0;
    $query = "SELECT * FROM orders WHERE ";
    
    if (isset($_GET['id'])) {
        $query .= "id LIKE '%".$_GET['id']."%' AND "; 
    }
    
    if ((isset($_GET['received_date']))&&($_GET['received_date']!=='')) {
        $query .= "received_date BETWEEN '".$_GET['received_date']." 00:00:00' AND '".$_GET['received_date']." 23:59:59' AND "; 
    }
    
    if (isset($_GET['ci'])) {
        $query .= "ci LIKE '%".$_GET['ci']."%' AND "; 
    }    

    if (isset($_GET['status'])) {
        if ($_GET['status']!=='Todos')
            $query .= "status = '".$_GET['status']."' AND "; 
    }    
    
    if (isset($_GET['offset']))
        $offset = $_GET['offset'];
     
    $query .= "true ORDER BY id DESC LIMIT 40 OFFSET ".$offset;

    $db = getConnection();
    $stmt = $db->prepare($query);
    $stmt->execute();            
    $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    
?>


<?php foreach($stmt->fetchAll() as $order):?> 
<?php $date = new Datetime($order['received_date']);?>
    <tr id="order<?=$order['id']?>" class="rows">
        <td><?=$order['id']?></td>
        <td><?=$order['ci']?></td>
        <td><?=$date->format('d/m/Y')?></td>        
        <td><?=$order['status']?></td>
        <td><?=getTechnicName($order['technic'])?></td>
    </tr>
<?php endforeach;?>

<script>
    $( document ).ready(function(){        
        $('.rows').click(function (){
            $('body').loadingModal({text: 'Espere, por favor...', 'animation': 'threeBounce'});                                        
            
            let id = this.id.replace('order','');
            show(id);            
        });
    });            
</script>

