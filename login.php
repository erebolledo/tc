<?php 
session_start(); 

$error = false;

if ($_POST){
    $string = file_get_contents("data/users.json");
    $users = json_decode($string, true);    

    $error = true;
    foreach ($users as $user){
        if ($user['user']==$_POST['user']){
            if ($user['password']==$_POST['password']){
                $error =false;
                $_SESSION['role'] = $user['role'];
                header('Location: book.php');
            }
        }
    }
}
?>

<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">    
  <link href="public/css/fonts.googleapis.com" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="public/css/materialize.min.css">
  <link rel="icon" type="image/png" href="images/9EQsu.png">      
  <style>
    body {
      display: flex;
      min-height: 100vh;
      flex-direction: column;
    }

    main {
      flex: 1 0 auto;
    }

    body {
      background: #fff;
    }

    .input-field input[type=date]:focus + label,
    .input-field input[type=text]:focus + label,
    .input-field input[type=email]:focus + label,
    .input-field input[type=password]:focus + label {
      color: #e91e63;
    }

    .input-field input[type=date]:focus,
    .input-field input[type=text]:focus,
    .input-field input[type=email]:focus,
    .input-field input[type=password]:focus {
      border-bottom: 2px solid #e91e63;
      box-shadow: none;
    }
  </style>
</head>

<body onload="<?=($error)?'errors()':''?>">
  <div class="section"></div>
  <main>
    <center>
      <div class="section"></div>

      <h5 class="indigo-text">Por favor introduce tus credenciales</h5>
      <div class="section"></div>

      <div class="container">
        <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE; width: 364px">

          <form class="col s12" method="post">
            <div class='row'>
              <div class='col s12'>
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s12'>
                <input class='validate' type='text' name='user' id='user' required/>
                <label for='email'>Usuario</label>
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s12'>
                <input class='validate' type='password' name='password' id='password' required/>
                <label for='password'>Contraseña</label>
              </div>
            </div>

            <br />
            <center>
              <div class='row'>
                <button type='submit' name='btn_login' class='col s12 btn btn-large waves-effect indigo'>Entrar</button>
              </div>
            </center>
          </form>
        </div>
      </div>
    </center>

    <div class="section"></div>
    <div class="section"></div>
  </main>

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
  <script type="text/javascript" src=" https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
</body>

<script>
    function errors(){
        M.toast({html: 'El usuario y/o contraseña es incorrecto'});        
    }
</script>
</html>
