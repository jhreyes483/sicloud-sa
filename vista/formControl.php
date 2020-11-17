<?php
include_once '../controlador/controladorrutas.php';
rutFromIni();

cardtitulo("Control de notificaciones");

$objModModi = new ControllerDoc();
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
        <div class="col-md-10 p-2 mx-auto ">
            <table class=" table table-bordered table-sm table-striped bg-white  mx-auto   text-center">
                <thead>

                   
                        <th>ID modicacion</th>
                        <th>Descripcion</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>ID usuario</th>
                        <th>Documento</th>
                        <th>P.nombre</th>
                        <th>P.apellido</th>
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
                    <td><?php  echo $row['ID_modifc'] ?></td>
                    <td><?php  echo $row['descrip'] ?></td>
                    <td><?php  echo $row['fecha'] ?></td>
                    <td><?php  echo $row['hora'] ?></td>
                    <td><?php  echo $row['FK_us'] ?></td>
                    <td><?php  echo $row['FK_doc'] ?></td>
                    <td><?php  echo $row['nom1'] ?></td>
                    <td><?php  echo $row['ape1'] ?></td>
                    <td><?php  echo $row['nom_modific'] ?></td>
                    <td><?php  echo $row['nom_rol'] ?></td>
                    <td>
                        <a href="../controlador/api.php?apicall=deleteLog&&id=<?= $row[0]  ?>" class="btn btn-circle btn-danger"><i class="far fa-trash-alt"></i></a>
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