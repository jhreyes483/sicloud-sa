<?php
//comprobacion de rol
include_once '../controlador/controladorrutas.php';
rutFromIni();
$objSession = new Session();
$objCon     = new ControllerDoc();
$u          = $objSession->desencriptaSesion();




//comprobacion de rol
$in = false;
switch ($u['usuario']['ID_rol_n']) {
    case 1:
        $in = true;
    break;
    case 2:
        $in = true;
    break;
    case 3:
        $in = true;
    break;
    case 4:
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

cardtitulo("Alertas");
if (isset($_SESSION['message'])) {;
$datos = $objCon->verProductos();
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
<div class="card card-body col-md-8 mx-auto my-4">
    <div class="col-md-12 mx-auto">
        <div class="row">
            <!-- form filtro por catiegorias -->
            <div class="card col-md-4 my-4 shadow ">
                <div class="card-body ">
                    <h5 class="card-title text-center ">Seleccione Tipo de filtro</h5>
                    <!-- INI--FORM fitrol--------------------------------------------------------------------------------- -->
                    <form action="CU0014-alertas.php" method="GET">
                        <select name="filtro" class="form-control">
                            <option class="form-control" value="1">Producto</option>
                            <option class="form-control" value="2">Id producto</option>
                            <option class="form-control" value="3">Categoria</option>
                        </select>
                        <input type="hidden" name="accion" value='filtro'>
                        <br> <input class="btn btn-primary btn-block my-2" type="submit" name="select" value="consulta">
                    </form>
                    <a class="btn  btn-primary btn-block " name = "stockGeneral" type = "submit" href="CU0014-alertas.php?stockGeneral">Stock general</a>

                </div>
            </div>
            <!--   fin de form Filtro---------------------------------------------------------------------------------------------- -->
<!-- ---------------------------------------------------------------------- -->


            <?php        
if(isset($_REQUEST['filtro'])){
            // evento select producto--------------------------------------------------------------------------------------------------------
    switch ($_REQUEST['filtro']) {
        case 1:
               // if ($_GET['filtro'] == 1) {
            ?>
                    <div class="card col-md-8 mx-auto my-4 shadow ">
                        <div class="card-body ">
                            <h5 class="card-title text-center ">Seleccione Producto</h5>
                            <form action="CU0014-alertas.php" method="POST">
                                <select name="producto" class="form-control">
                                    <?php 
             
                                    $datos = $objCon->verProductos();
                                    foreach($datos as $i =>$row){
                                    ?>
                                        <option value="<?= $row['ID_prod']  ?>"><?= $row['nom_prod']  ?></option>
                                    <?php } // fin de while productos    
                                    ?>

                                </select>
                                <input type="hidden" name="accion" value='alertaVerProducto'>
                                <br> <input class="btn btn-primary btn-block my-2" type="submit" name="submit" value="consulta">
                            </form>
                        </div>
                    </div>

                <?php
             //   } // fin de ver productos-------------------------------------------------------------------
        break;
        case 2:
         ?>
                    <div class="card col-md-8 mx-auto my-4 shadow ">
                        <div class="card-body ">
                            <h5 class="card-title text-center ">Digite id de producto</h5>
                            <form action="CU0014-alertas.php" method="POST">
                                <div class="form-group"><input id="my-input" class="form-control" type="text" name="idProducto"></div>
                                <input type="hidden" name="accion" value='alertaVerProductoID'>
                                <br> <input class="btn btn-primary btn-block my-2" type="submit" name="submit" value="consulta">
                            </form>
                        </div>
                    </div>

                <?php
              //  } // fin de filtro 2 busqueda por ID---------------------------------------------------------------------
        break;
        case 3:
                // Evento de busquda por categoria
           //     if ($_GET['filtro'] == 3) {  ?>
                    <div class="card col-md-8 mx-auto my-4 shadow ">
                        <div class="card-body ">
                            <h5 class="card-title text-center ">Seleccione Producto</h5>
                            <form action="CU0014-alertas.php" method="POST">
                                <select name="categoria" class="form-control">
                                    <?php 
    
                                    $datos = $objCon->verCategoria();
                                    foreach($datos as $i =>$row){

                                    ?>
                                        <option value="<?= $row['ID_categoria']  ?>"><?= $row['nom_categoria']  ?></option>
                                    <?php } ?>
                                </select>
                                <input type="hidden" name="accion" value='selectCategoria'>
                                <br> <input class="btn btn-primary btn-block my-2" type="submit" name="consulta" value="consulta">
                            </form>
                        </div>
                    </div>
<?php
            // fin de filtro 3 busquda por categoria--------------------------------------------------------------------
        break;
        default:
        echo '<script>("la opcion no es valida")</script>';
        break;
             // fin de isset fitrO                       
    }
}

            ?>
        </div><!-- fin de row -->
    </div><!-- fin de col md  -->
</div><!-- fin de card body -->
<div class="row ">
    <!-- Content Column -->
    <div class="col-lg-8 mb-4 mx-auto">
        <!-- Project Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 shadow p-3 mb-5 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo "Producos" ?></h6>
            </div>
            <div class="card-body">
                <?php

//----------------------------------------------------------------------------------------------------------------------------------
                // CAPTURA DE DATOS SEGUN EL EVENTO
                if (isset($_POST['accion'])) {
                    // ver cantidad de productos por nombre
                    switch ($_POST['accion']) {
                        case 'alertaVerProducto':
                            $id = $_POST['producto'];
                            $prod = $objCon->verProductosId($id);
                        break;
                        case 'alertaVerProductoID':
                            $id = $_POST['idProducto'];
                            $prod = $objCon->verProductosId($id);
                        break;
                        case 'selectCategoria':
                            $id = $_POST['categoria'];
                            $prod = $objCon->verPorCategoria($id);
                        default:
                        break;
                    }
                    //FIN DE EVENTOS-----------------------------------------------------------------------------------------------------------
                    foreach($prod as $i =>$row){
                    //while ($row = $prod->fetch_array()) {
                        $p  =  $row['stok_prod'];
                        $po  = 10 * $row['stok_prod'];
                        $po = $po . "%";

                        $c = "text";
                        if ($p < 2) {
                            $c = "danger";
                        } elseif ($p <= 6) {
                            $c = "warning";
                        } elseif ($p >= 7) {
                            $c = "success";
                        }
                        $c = "bg-" . $c;

               ?>
                        <h4 class="small font-weight-bold"><?= $row['nom_prod']  ?> <span class="float-right"><?=  " Cantidad de productos; " . $p ?></span> </h4>
                        <div class="progress mb-4">
                            <div class="progress-bar <?= $c ?>" role="progressbar" style="width:<?= $po; ?>" aria-valuenow=<?= $c ?> aria-valuemin="0" aria-valuemax="100"></div>

                
                        </div>
                    <?php
                    } // fin de while producto
                    ?>
            </div><!-- fin de card body -->
        </div><!-- fin de col categoria  -->

<?php    } // fin de isset accion ?>
       

<?php  
if(isset($_GET['stockGeneral'])){
?>
        <div class="container">
            <div class="card card-body bg-while col-lg-12 shadow  mx-auto">
                <div class="row">
                    <table class="table table-striped table-hover bg-bordered bg-light table-sm col-lg-12 col-sm-4 col-xs-12 mx-auto text-center shadow p-3 mb-5 bg-white">
                        <thead>
                            <tr>
                                <th>Nombre Producto</th>
                                <th>Valor Producto</th>
                                <th>Stock </th>
                                <th>Estado del producto</th>
                                <th>categoria</th>
                                <th>Imagen</th>
                                <th>Medida</th>
                                <?php if($_SESSION['usuario']['ID_rol_n'] == 1 || $_SESSION['usuario']['ID_rol_n'] == 1 ){   ?>
                                    <th>Accion</th><?php }  ?>
                            </tr>
                        </thead>
                        <?php 
                        $datos = $objCon->verProductos();
                        foreach($datos as $i =>$row){
                        //while ($row = $datos->fetch_array()) {
                            $p  =  $row['stok_prod'];


                            $c = "text";
                            if ($p < 2) {
                                $c = "danger";
                            } elseif ($p <= 6) {
                                $c = "warning";
                            } elseif ($p >= 7) {
                                $c = "success";
                            }
                            $c = "bg-" . $c;

                        ?>
                            <tbody>
                                <tr>
                                    <td><?php echo $row['nom_prod'] ?></td>
                                    <td><?php echo "$".number_format(($row['val_prod']),0, ',','.' )   ; ?></td>
                                    <td class=" <?php echo  $c  ?>"><?php echo $row['stok_prod'] ?></td>
                                    <td><?php echo $row['estado_prod'] ?></td>
                                    <td><?php echo $row['nom_categoria'] ?></td>
                                    <td><img class="card card-body  mx-auto" src="fonts/img/<?php echo $row['img']; ?>" alt="Card image cap" height="130px" width="150px"></td>
                                    <td><?php echo $row['nom_medida'] ?></td>
                                
                                    <?php if($_SESSION['usuario']['ID_rol_n'] == 1 || $_SESSION['usuario']['ID_rol_n'] == 1 ){   ?>
                                    <td>
                                        <a class = "btn  btn-success" href="CU003-ingresoProducto.php?consulta=Validar+exitencia&&p=<?php echo $row['ID_prod']?>">ingreso</a>


                                    </td><?php  }  ?>
                                    <td>
                                        
                                    </td>
                                </tr>
                            </tbody>
                        <?php
                            }// fin de tabla StockGeneral
                       // } // fin de while tabla
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
        
<?php
}// fin de permisos por rol  



}

rutFromFin();


?>