<?php
include_once '../controlador/controladorrutas.php';
rutFromIni();
$objSession =new Session();
$u = $objSession->desencriptaSesion();

//comprobacion de rol
$in = false;
switch ($u['usuario']['ID_rol_n']) {
    case 1:
        $in = true;
    break;
  
    default:
        echo "<script>alert('No tiene permiso para ingresar a este modulo');</script>";
        echo "<script>window.location.replace('index.php');</script>";
    break;
}
if ($in == false) {
    echo "<script>alert('No tiene permiso para ingresar a este modulo');</script>";
    echo "<script>window.location.replace('index.php');</script>";
} else {




?>


<script>
    function eliminarCategria(id_to_delete){
        var confirmacion = 
confirm('Esta seguro que desea elminar usuario con id: ' + id_to_delete + ' ?');
if(confirmacion){
window.location = "../controlador/api.php?accion=eliminarUsuario&&id="+ id_to_delete;
}

   }
</script>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <div class="container my-5 mx-auto">
        <div class="card card-body">
            <h3 class="text-center">Consulta de Usuarios</h3>
        </div>
    </div>

    <?php

if (isset($_SESSION['message'])) {  ?>
    <!-- alerta boostrap -->
    <div class="alert text-center col-md-4 mx-auto alert-<?= $_SESSION['color']   ?> alert-dismissible fade show" role="alert">
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

        <div class="col-md-10 my-5 mx-auto">
            <table class="table table-bordered  table-striped bg-white table-sm mx-auto text-center">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">foto</th>
                        <th scope="col">ID</th>
                        <th scope="col">primer nombre</th>
                        <th scope="col">segundo nombre</th>
                        <th scope="col">primer apellido</th>
                        <th scope="col">segundo apellido</th>
                        <th scope="col">fecha</th>
                        <th scope="col">contraseña</th>
                        <th scope="col">correo</th>
                        <th scope="col">tipo documento</th>
                        <th scope="col">Accion</th>
                        <?php

                        $objus= new ControllerDoc();
                        $datos = $objus-> readUsuariosController();
                        

                            if(isset($datos)){

                                foreach ($datos as $i => $d){
                            
                           
                        ?>
                    </tr>
                </thead>

                <tbody>
                <tr>
                <td><img class="img-profile ml-3 rounded-circle mx-auto" src="fonts/us/<?= $d[7]; ?>" alt="Card image cap" height="65" width="70"></td>
                    <td><?= $d[0] ?></td>
                    <td><?= $d[1] ?></td>
                    <td><?= $d[2] ?></td>
                    <td><?= $d[3] ?></td>
                    <td><?= $d[4] ?></td>
                    <td><?= $d[5] ?></td>
                    <td><?= $d[7] ?></td>
                    <td><?= $d[8] ?></td>
                    <td><?= $d[9] ?></td>
                    <td>
                        <a href="EditarUsuario.php?ID_us=<?= $d[0] ?> " class="btn btn-circle btn-secondary">
                        <i class="fas fa-search fa-sm"></i>
                        <a href="../controlador/api.php?apicall=elimianarUsuario&&id=<?= $d[0] ?> "  class="btn btn-circle btn-danger">
                                <i class="far fa-trash-alt"></i>
                            </a>
                    </td>
                </tr>

                <?php

                                }
                            }
                        }
                            ?>
                </tbody>
            </table>
        </div>  


<?php 
include_once 'plantillas/cuerpo/footerN1.php'; 
include_once 'plantillas/cuerpo/finhtml.php';
?>