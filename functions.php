<?php
    function getConnection()
    {
        $conf = parse_ini_file('config/config.ini');        
        try     
        {
            $conn = new PDO("mysql:host=$conf[db_server];dbname=$conf[db_name];charset=utf8", "$conf[db_user]", "$conf[db_password]");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
            return false;
        }                                    
    }        
    
    function getTechnicName($id)
    {
        //$query = "SELECT o.id, t.name FROM orders o, technicians t where o.id = $id_order AND o.technic = t.id";        
        $query = "SELECT name FROM technicians WHERE id = $id";
        if (empty($id)){
            return '---';
        }else{
            $db = getConnection();
            $stmt = $db->prepare($query);
            $stmt->execute();            
            $stmt->setFetchMode(PDO::FETCH_ASSOC);         

            $technic = $stmt->fetchAll();
            $technic_name = (empty($technic[0]['name'])?'---':$technic[0]['name']);        

            return $technic_name;            
        }

    }
    
    function getSpanishDate($date)
    {
        $spanish_date = str_replace('-', '/', date('d-m-Y', strtotime($date)));
        
        return $spanish_date;
    }
    
    function getNumberDiary($date, $action, $reason=null)
    {
        $reason_query = (empty($reason))?'':" AND reason = '$reason' "; 
        $query = "SELECT $action(cost) as count FROM orders WHERE status = 'Entregado' $reason_query AND delivered_date BETWEEN '$date 00:00:00' AND '$date 23:59:59'";
        
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute();            
        $stmt->setFetchMode(PDO::FETCH_ASSOC);         
        $res = $stmt->fetchAll();        
        
        $number = (empty($res[0]['count']))?0:$res[0]['count'];
        
        return $number;
    }