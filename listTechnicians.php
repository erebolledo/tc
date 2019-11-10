<?php 
    include_once 'functions.php';

    $db = getConnection();    
    
    if (!(empty($_GET['id']))&&!(empty($_GET['action']))&&($_GET['action']=='delete')){
        $sql = "DELETE FROM `technicians` WHERE id = ".$_GET['id'];
        $db->exec($sql);
    }
    
    $query = "SELECT * FROM technicians";

    $stmt = $db->prepare($query);
    $stmt->execute();            
    $stmt->setFetchMode(PDO::FETCH_ASSOC); 
?>


<?php foreach($stmt->fetchAll() as $technic):?> 
    <tr id="technic<?=$technic['id']?>" class="rows">
        <td><?=$technic['id']?></td>
        <td><?=$technic['name']?></td>
        <td style="text-align: center">
            <a href="technic.php?id=<?=$technic['id']?>&action=delete" target="_self" class="btn btn-danger btn-xs" role="button">
                <span class="glyphicon"></span> Eliminar
            </a>            
        </td>
    </tr>
<?php endforeach;?>

<script>
    $( document ).ready(function(){        
        $('.rows').click(function (){
            let id = this.id.replace('technic','');
            showTechnic(id);
        });                
    });            
</script>

