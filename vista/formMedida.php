<?php

include_once '../controlador/controladorrutas.php';
rutFromIni();
cardtitulo("Medida");
?>


<script>

function eliminarMed(id_med){
    var conf = confirm('Esta seguro de eliminar medida con id' + id_med + " ?");
    if(conf){
        window.location ="../controlador/api.php?apicall=eliminarMedida&&id=" + id_med ;
    }
}
</script>

<div class="container col-md col-xl-6  ">
    <div class="row">

        <div class=" col-8 col-sm-6 col-md-4 col-lg-3 col-xl-4  mx-auto">
            <!-- formulario de registro -->
            <?php
            if (isset($_SESSION['message'])) {  ?>
            
                <!-- alerta boostrap -->
                <div class="alert alert-<?php echo $_SESSION['color']   ?> alert-dismissible fade show" role="alert">
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

<div class="container">
            <div class="   card ">
                <div class="card-header">Registro</div>
                <div class="card-body">
                    <form action="../controlador/api.php?apicall=insertMedida" method="POST">
                        <div class="form-group"><input class="form-control" type="text" name="nom_medida" placeholder="Medida" required autofocus maxlength="35"></div>
                        <div class="form-group"><input class="form-control" type="text" name="acron_medida" placeholder="Acronimo" required autofocus maxlength="5"></div>
                        <input type="hidden" name="accion" value="insertMedida">
                        <div class="form-group"><input class="form-control btn btn-primary" type="submit" name="submit" value="enviar"></div>
                    </form>
                </div><!-- fin card body -->
            </div><!-- fin de card -->
  

    </div>

    </div>


        <div class="col-lg-6 p2 mt-3">
            <table class=" table table-bordered  table-striped bg-white table-sm text-center">
                <thead>

                    <tr>
                        <th>ID_medida</th>
                        <th>Medida</th>
                        <th>Acronimo</th>
                        <th>Accion</th>
                        <?php 

                        
                        $objModMed = new ControllerDoc();
                        $datos  = $objModMed->verMedida();
                        if (isset($datos)) {
                            //while ($row = $datos->fetch_array()) { 
                                foreach($datos as $i => $row){
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los nombres que estan son los mismos de los atributos de la base de datos de lo contrario dara un error -->
                    <td><?php echo $row['ID_medida'] ?></td>
                    <td><?php echo $row['nom_medida'] ?></td>
                    <td><?php echo $row['acron_medida'] ?></td>
                    <td>
                        <a href=" formEdicionMedida.php?accion=editarMedia&&id=<?php echo $row['ID_medida'] ?> " class="btn btn-circle btn-secondary"><i class="fas fa-marker"></i></a>
                        <a onclick="eliminarMed(<?php echo $row['ID_medida'] ?>)" href="#" class="btn btn-circle btn-danger"><i class="far fa-trash-alt"></i></a>
                    </td>
                </tbody>
        <?php

                           } //fin del while
                        }
        ?>
            </table>
        </div>
    
</div><!-- fin de row -->
</div><!-- Fin container -->
<?php
//finhtml();
rutFinFooterFrom();
rutFromFin();
?>