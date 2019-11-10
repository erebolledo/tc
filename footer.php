    
        <footer class="blog-footer">
            <!--<p>Blog template built in <a href="http://getbootstrap.com">Bootstrap</a> by <a href="https://twitter.com/mdo">erebolledo</a>.</p>
            <p>
                <a href="#">Back to top</a>
            </p>-->
        </footer>
    
    </body>    
    
    <script language="javascript">

        function filter(){
            let hostname = location.hostname;
            let url = 'http://'+hostname+'/tc/listOrders.php?true';            

            if ($('#list_id_order').val()!=='')
                url += '&id='+$('#list_id_order').val();
            if ($('#list_status').val()!=='')
                url += '&status='+$('#list_status').val();            
            if ($('#list_received_date').val()!=='')
                url += '&received_date='+$('#list_received_date').val();                                        
            if ($('#list_ci').val()!=='')
                url += '&ci='+$('#list_ci').val();                        
            
            console.log(url);                    
            $.get(url, function(data, status){    
                $('#list_orders').html(data);
            });
        }
        
        function show(id){
            let hostname = location.hostname;
            let url = 'http://'+hostname+'/tc/formOrder.php?id='+id;                        
            
            console.log(url);
            $.get(url, function(data, status){    
                $('#form_order').html(data);
                $('body').loadingModal('destroy');
            });            
        }
        
        function showTechnic(id){
            let hostname = location.hostname;
            let url = 'http://'+hostname+'/tc/formTechnic.php?id='+id;                        
            
            console.log(url);
            $.get(url, function(data, status){    
                $('#form_technic').html(data);
            });            
        }                
        
        function verified(){
            console.log($('#technic').val()!=='');
            false;
            if (($('#client').val()!=='')&&($('#receipt_by').val()!=='')&&
                ($('#ci').val()!=='')&&($('#technic').val()!==0)){
                true;
            }else{
                false
            }            
        }
        
        function verify_form(){            
            
            if ($('#client').val()===''){
                swal("Error", "El campo 'Nombre' no puede estar vacio", "error");            
                $("#client").css("background-color", "yellow");
                $("#client").focus();
                
                return false;    
            }
            
            if ($('#ci').val()===''){
                swal("Error", "El campo 'Cédula' no puede estar vacio", "error");            
                $("#ci").css("background-color", "yellow");
                $("#ci").focus();
                
                return false;    
            }            
            
            if (($('#technic').val()==0) && ($('#reason').val()=='reparation')){
                swal("Error", "El campo 'Técnico' no puede estar vacio", "error");            
                $("#technic").css("background-color", "yellow");
                $("#technic").focus();
                
                return false;    
            }                        
                        
            if ($('#receipt_by').val()===''){
                swal("Error", "El campo 'Recibido por/Vendido por' no puede estar vacio", "error");            
                $("#receipt_by").css("background-color", "yellow");
                $("#receipt_by").focus();
                
                return false;    
            }
            
            if (verified()){
                $('body').loadingModal({text: 'Guardando...', 'animation': 'threeBounce'});                            
                
                return true;
            }

            return true;
        }        
        
        function enable_print(){
            if (verified()){
                $('#btn_print').addClass("active");
            }else{
                $('#btn_print').addClass("disabled");
            }
        }
        
        $( document ).ready(function(){ 
            
            let url = window.location.href; 
            $("nav ul li a").each(function() { 
                if (url == (this.href)) 
                    $(this).closest("li").addClass("active");
            });                        
            
            $('thead input').keyup(function (){
                filter();                
            });             
            
            $('#list_status').change(function (){
                filter();
            });                   
            
            $('#list_received_date').change(function (){                
                filter();
            });                         
            
            $('#book_received_date').change(function (){                
                $("#form_book").submit();
            });                         
                        
        });
                
    </script>    
</html>
