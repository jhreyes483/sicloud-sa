<!DOCTYPE html>
<html lang="es">


<?php
include_once '../../../controlador/controladorrutas.php';
rutIniFromCorreoContrseña();

//echo "<pre>"; print_r($_SESSION); echo "</pre>";


?>


<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="./style.css">

</head>

<body>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <div class="form-gap"></div>
    <div class="container mx-auto">
        <div class="row">
            <div class="col-md-4  mx-auto ">
                <div class="panel panel-default">
                    <div class="card card-body">
                        <div class="text-center">
                            <h3><i class="fa fa-lock fa-4x"></i></h3>
                            <h2 class="text-center">Olvidó la contraseña?</h2>
                            <p>Puedes cambiar tu contraseña aquí.</p>
                            <div class="panel-body">

                                <form id="register-form"  action="../../../controlador/api.php?apicall=cambioContrasenaCorreo"  role="form" autocomplete="off" class="form" method="post">

                                    <div class="form-group">
                                        <div class="">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                            <div class="form-group">
                                             <select name="tipo_doc" id="" class ="form-control">
                                                <option value="0">Seleccione documento</option>
                                                 <option value="CC">Cedula</option>
                                                 <option value="CE">D. extranjero</option>
                                                 <option value="TI">T. identidad<i class="fa fa-creative-commons" aria-hidden="true"></i></option>
                                             </select>
                                            </div>
                                            <div class="form-group"><input  id="email" name="documento" placeholder="documento" class="form-control" type="text"></div>
                                            <div class="form-group">  <input id="email" name="email" placeholder="correo" class="form-control" type="email"></div>
                                            <?php $fecha = date('Y-m-d')  ;   $Fini = substr( $fecha , 0,8 );     $diaActual= substr($fecha , -2); 
                                            $diaCaduc = $diaActual + 1; $fechaCaduc = ($Fini.$diaCaduc)
                                            ?>
                                             <input type="text" name="fActual" value =" <?= $fecha  ;  ?>"  >
                                             
                                             <input type="text" name="fCaduc" value =" <?= $fechaCaduc;  ?>" >
                                             <input type="hidden"  name="token" id="token" value="sicloud">
                                            
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Validar correo" type="submit">
                                    </div>
                                </form>

                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
            
            if (isset($_SESSION['message'])) {
?>
<!-- alerta boostrap -->
<div class="col-md-4 mx-auto alert alert-<?php echo $_SESSION['color']   ?> alert-dismissible fade show" role="alert">
    <?php
                echo  $_SESSION['message']  ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php
                setMessage();
            }

?>


<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
   <script src="https://kit.fontawesome.com/451d49791e.js" crossorigin="anonymous"></script>

   </body>

</html>
    <!-- partial -->

