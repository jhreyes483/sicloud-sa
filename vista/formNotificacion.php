<?php
include_once '../controlador/controladorrutas.php';
rutFromIni();

cardtitulo("Control de modificaiones");

$objCon = new ControllerDoc();
?>

<div class="container-fluid ">
    <!-- formulario de registro -->
    <?php
    if (isset($_SESSION['message'])) {
    ?>
        <div class="mx-auto col-lg-4 text-center alert alert-<?php echo $_SESSION['color']   ?> alert-dismissible fade show" role="alert">
            <?php
            echo  $_SESSION['message']  ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

    <?php setMessage();
    } ?>


    <!-- inicia segunda divicion -->
    <div class="col-md-8 p-2 mx-auto ">
        <table class=" table table-bordered table-sm table-striped bg-white  mx-auto   text-center">
            <thead>
                <th>ID notificacion</th>
                <th>Estado</th>
                <th>Descripcion</th>
                <th>Rol</th>
                <th>Tipo de notificacion</th>
                </tr>


                <?php

                $datos = $objCon->verNotificacionesT();

               // ControllerDoc::ver($datos, 1);
                foreach ($datos as $d) {
                ?>
                    </tr>
            </thead>
            <tbody>
                <!-- Los nombres que estan son los mismos de los atributos de la base de datos de lo contrario dara un error -->
                <td><?= $d[0] ?></td>
                <td><?= $d[1] ?></td>
                <td><?= $d[2] ?></td>
                <td><?= $d[3] ?></td>
                <td><?= $d[4] ?></td>
                <td>
                    <a href="../controlador/api.php?apicall=deleteNotific&&id=<?= $d[0]  ?>" class="btn btn-circle btn-danger"><i class="far fa-trash-alt"></i></a>
                    <a href="../controlador/api.php?apicall=notificLeida&&id=<?= $d[0]  ?>" class="btn btn-circle btn-success btn-"><i class="fas fa-arrow-right"></i></a>
                </td>
            </tbody>
        <?php

                } //fin del while

        ?>
        </table>


    </div><!-- fin de response table -->
</div><!-- Fin container -->

<?php
rutFinFooterFrom();
rutFromFin();
?>