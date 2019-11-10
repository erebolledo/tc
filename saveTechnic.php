<?php
    include_once 'functions.php';
    
    $conn = getConnection();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);      
    
    $technic = $_POST;   
    
    $technic['name'] = (empty($technic['name']))?'':$technic['name'];
    
    if (empty($_POST['id_technic'])){
        $sql = "INSERT INTO `technicians` (`name`) VALUES ('".$technic['name']."')";        
        $conn->exec($sql);
    }else{
        $sql = "UPDATE `technicians` SET `name` = '".$technic['name']."'"
                . " WHERE `technicians`.`id` = ".$technic['id_technic'];
        $conn->exec($sql);
    }
?>

<script language="javascript">
    let hostname = location.hostname;
    window.location = "http://"+hostname+"/tc/technic.php";
</script>




