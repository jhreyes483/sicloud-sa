<?php
include_once '../controlador/controladorrutas.php';
rutFromIni();

cardtitulo("Control de notificaciones");

$objModModi = new ControllerDoc();
rutFromFin();
?>

<<<<<<< HEAD
=======

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


>>>>>>> ddf88fe40d29fc61ec819bc71aff4d2fa3eaa775

<script>
    $(document).ready(function() {
        $("table").addClass("table-hover bg-white table-sm table-bordered")
        $("#actividad").tablesorter({
            widgets: ['zebra'],
            sortList: [
                [2, 1],
                [0, 0]
            ],
            headers: {
                5: {
                    sorter: false
                },
                10: {
                    sorter: false
                }
            }
        });
    });
</script>

<<<<<<< HEAD
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
    <div class="col-md-11 p-2 mx-auto ">
        <table id="actividad" class="tablesorter text-center">
            <thead>
                <tr>
                    <th> <i class="fas fa-arrows-alt-v"></i> ID modicacion</th>
                    <th> <i class="fas fa-arrows-alt-v"></i>  Descripcion</th>
                    <th> <i class="fas fa-arrows-alt-v"></i> Fecha de modificacion</th>
                    <th> <i class="fas fa-arrows-alt-v"></i> Hora</th>
                    <th> <i class="fas fa-arrows-alt-v"></i> ID usuario</th>
                    <th> <i class="fas fa-arrows-alt-v"></i> Documento</th>
                    <th> <i class="fas fa-arrows-alt-v"></i> Nombres</th>
                    <th> <i class="fas fa-arrows-alt-v"></i> Apellidos</th>
                    <th> <i class="fas fa-arrows-alt-v"></i> Modificacion</th>
                    <th> <i class="fas fa-arrows-alt-v"></i> Rol</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $datos = $objModModi->verJoinModificacionesDB();
                foreach ($datos as $i => $row) {
                ?>
                    <tr>
                        <!-- Los nombres que estan son los mismos de los atributos de la base de datos de lo contrario dara un error -->
                        <td><?= $row['ID_modifc'] ?></td>
                        <td><?= $row['descrip'] ?></td>
                        <td><?= $row['fecha'] ?></td>
                        <td><?= $row['hora'] ?></td>
                        <td><?= $row['FK_us'] ?></td>
                        <td><?= $row['FK_doc'] ?></td>
                        <td><?= $row['nom1'] . ' ' . $row['nom2'] ?></td>
                        <td><?= $row['ape1'] . ' ' . $row['ape2'] ?></td>
                        <td><?= $row['nom_modific'] ?></td>
                        <td><?= $row['nom_rol'] ?></td>
                        <td>
                            <a data-bs-toggle="tooltip" data-bs-placement="right" title="Eliminar actividad" href="../controlador/api.php?apicall=deleteLog&&id=<?= $row[0]  ?>" class="btn btn-circle btn-danger">
                                <i class="far fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php
=======
        <!-- inicia segunda divicion -->
        <div class="col-md-10 p-2 mx-auto ">
            <table class=" table table-bordered table-sm table-striped bg-white  mx-auto   text-center">
                <thead>

                   
                        <th>ID modicacion</th>
                        <th>Descripcion</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>ID usuario</th>
                        <th>Documento</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Modificacion</th>
                        <th>Rol</th>
                         </tr>
                        

                        <?php 

                        $datos = $objModModi->verJoinModificacionesDB();
                            foreach($datos as $i => $row){    
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los nombres que estan son los mismos de los atributos de la base de datos de lo contrario dara un error -->
                    <td><?= $row['ID_modifc'] ?></td>
                    <td><?= $row['descrip'] ?></td>
                    <td><?= $row['fecha'] ?></td>
                    <td><?= $row['hora'] ?></td>
                    <td><?= $row['FK_us'] ?></td>
                    <td><?= $row['FK_doc'] ?></td>
                    <td><?= $row['nom1'].' '.$row['nom2'] ?></td>
                    <td><?= $row['ape1'].' '.$row['ape2'] ?></td>
                    <td><?= $row['nom_modific'] ?></td>
                    <td><?= $row['nom_rol'] ?></td>
                    <td>
                        <a data-bs-toggle="tooltip" data-bs-placement="right" title="Eliminar actividad"
                        href="../controlador/api.php?apicall=deleteLog&&id=<?= $row[0]  ?>" 
                        class="btn btn-circle btn-danger">
                        <i class="far fa-trash-alt"></i>
                    </a>
                    </td>
                </tbody>
        <?php
>>>>>>> ddf88fe40d29fc61ec819bc71aff4d2fa3eaa775

                } //fin del while
                ?>
            </tbody>
        </table>


    </div><!-- fin de response table -->
</div><!-- Fin container -->

<?php
rutFinFooterFrom();

?>