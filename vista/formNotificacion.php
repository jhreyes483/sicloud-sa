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
        <div class="mx-auto col-lg-4 text-center alert alert-<?= $_SESSION['color'] ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['message'] ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php setMessage();
    }
    rutFromFin();
    ?>

    <script>
        $(document).ready(function() {
            $("table").addClass("table-hover bg-white table-sm table-bordered")
            $("#notificacion").tablesorter({
                widgets: ['zebra'],
                sortList: [
                    [3, 0],[4, 0],[1,0]
                ],
                headers: {
                    5: {
                        sorter: false
                    },
                }
            });
        });
    </script>


    <!-- inicia segunda divicion -->
    <div class="col-md-8 p-2 mx-auto ">
        <table id="notificacion" class="tablesorter  mx-auto text-center">
            <thead>
                <tr>
                    <th> <i class="fas fa-arrows-alt-v"></i> ID notificacion</th>
                    <th> <i class="fas fa-arrows-alt-v"></i> Estado</th>
                    <th> <i class="fas fa-arrows-alt-v"></i> Descripcion</th>
                    <th> <i class="fas fa-arrows-alt-v"></i> Rol</th>
                    <th> <i class="fas fa-arrows-alt-v"></i> Tipo de notificacion</th>
                    <th> Actividad</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $datos = $objCon->verNotificacionesT();
                foreach ($datos as $d) {
                ?>
                    </tr>
                    <td><?= $d[0] ?></td>
                    <td><?= $d[1] ?></td>
                    <td><?= $d[2] ?></td>
                    <td><?= $d[3] ?></td>
                    <td><?= $d[4] ?></td>
                    <td>
                        <a href="../controlador/api.php?apicall=deleteNotific&&id=<?= $d[0]  ?>" class="btn btn-circle btn-danger" data-bs-toggle="tooltip" data-bs-placement="right" title="Eliminar notificacion">
                            <i class="far fa-trash-alt"></i>
                        </a>
                        <a href="../controlador/api.php?apicall=notificLeida&&id=<?= $d[0]  ?>" class="btn btn-circle btn-success btn" data-bs-toggle="tooltip" data-bs-placement="right" title="Marcar como leida"><i class="fas fa-arrow-right"></i>
                        </a>
                    </td>
                    </tr>

                <?php

                } //fin del while

                ?>
            </tbody>
        </table>


    </div><!-- fin de response table -->
</div><!-- Fin container -->

<?php
rutFinFooterFrom();

?>