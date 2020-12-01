<?php

include_once '../controlador/controladorrutas.php';
rutFromIni();
$objCon    = new ControllerDoc();
$objSession =new Session();
$u = $objSession->desencriptaSesion();

//comprobacion de rol
$in = false;
switch ($u['usuario']['ID_rol_n']) {
    case 1:
        $in = true;
    break;
    case 4:
        $in = true;
    break;
    case 0:
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


    //------------------------------------------------------------------------------------



?>
    <!-- col 12 -->
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <?php //include_once 'js/scripts.php';  

        ?>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Facturacion</title>
        <script type="text/javascript" src = "js/funcions.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css"/>  
    </head>
    <body>
        
<div class="container-fluid col-md-10 my-4">
   <div class="row">
            <!-- Formulario datos cliente---------------------------------------------------------------------------------------------- -->
            <div class="col-md-12">
                <h2 class="my-4 e">Nueva venta</h2>
                <p class="e">Datos de cliente</p>
                <div class="card card-body">
                    <form action="CU005-facturacion.php"> 
                        <button type="submit" class="btn btn-outline-success col-md-1 my-1 btn-sm">Buscar</button>
                        <div class="card card-body shadow">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group"><label for="">Cedula Cliente</label><input type="text" value= "" class="form-control" name="ID" id="nit_cliente" /></div>
                                </div><!-- fin de primera divicion de 3 -->
                                </form>
                    <?php
                    if (isset($_GET['ID'])){
                        $id_us=$_GET['ID'];
                        $objModFact = new ControllerDoc();
                        $datos = $objModFact->verUsuarioFactura($id_us);
                        //$us = Factura::verUsuarioFactura($_GET['ID']);
                    //while ($row = $us->fetch_assoc()) {
                        foreach($datos as $i=>$row){
                       ?>

                        <div class="col-md-4">
                            <div class="form-group"><label for="">Nombre</label><input type=»text» readonly=»readonly» class="form-control" value="<?php echo $row['nom1'] . $row['nom2'] . " " . $row['ape1'] . " " . $row['ape2'];  ?>" /></div>
                        </div><!-- fin de segunda divicion de 3 -->
                        <div class="col-md-4">
                            <div class="form-group"><label for="">Telefono</label><input type=»text» readonly=»readonly» class="form-control" value="<?php echo $row['tel'] ?>" /></div>
                        </div><!-- fin de tercera divicion de 3 -->
                        <div class="form-group col-md-12"><label for="">Direccion</label><input type=»text» readonly=»readonly» class="form-control" value="<?php echo $row['dir'] ?>" /></div>
                        <?php       } 
                            } ?>
                </div><!-- fin de div row -->
            </div><!-- fin de card -->
        </div><!-- fin de card -->
    </div>
    <!-- Formulario datos cliente---------------------------------------------------------------------------------------------- -->

<div class="row mx-auto ">
    <div class="col-md-12">
        <div class="card my-4">

                <div class="card-body">
                
                    <table id="tablaUsuarios" class="table table-striped bg-bordered bg-white table-sm col-md-10 col-sm-4 col-xs-12 mx-auto">
                        <thead class="table-dark">
                            <tr>
                                <th>Numero de Documento</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            
                            </tr>
                        </thead>

                        <tbody>
            
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
</div>


    <!-- Formulario datos cliente---------------------------------------------------------------------------------------------- -->
    <div class="col-md-12">
        <br><br><br>
        <p class="e">Datos de venta</p>
        <div class="card card-body ">
            <div class="card card-body  shadow">
                <div class="row">
                    <p>
                        <label for="">Vendedor</label><br>
                        <?php echo $u['usuario']['nom1'] . " " . $u['usuario']['ape1']; ?>
                    </p>

                    <div class="ml-auto"><label for="">Accion</label><br><a href="#" class="btn btn-danger  ">Anular</a></div>
                </div><!-- fin de row -->
            </div><!-- fin de row -->
        </div><!-- fin de card -->
    </div><br><br>
    <!-- Formulario datos cliente---------------------------------------------------------------------------------------------- -->
    </div>


    <!-- tabla de productos 01-------------------------------------------------------------------------------------- -->



    <div class="col-md-12">
        <table class="table table-striped bg-bordered bg-white table-sm col-md-12 col-sm-4 col-xs-12 my-4 text-center mx-auto">
            <thead class="bg-dark text-white text-center">
                <tr>
                    <th>Codigo</th>    
                    <th>Producto</th>
                    <th>Existencia</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Precio Total</th>
                    <th>Accion</th>         
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td><input type="text" name="txt_cod_producto" id="txt_cod_producto"></td>
                    <td id= "txt_description">-</td>
                    <td id= "txt_existencia">-</td>
                    <td><input type="text" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled></td>
                    <td id="txt_precion" class="text-right">0.00</td>
                    <td id="txt_precion" class="text-right">0.00</td>
                    <td><a href="#" id="add_product_venta" class="btn btn-circle btn-success"><i class ="fass fa-plus"></i>
                </a></td>
                </tr>

                    <?php
                    if (isset($_GET['id_p'])) {
                   //     $datos = Producto::verProductosId($_GET['id_p']);
                    $objModProd = new ControllerDoc();
                    $datos = $objModProd->verProductosId($id_p);
                   //     while ($row = $datos->fetch_array()) {
                    foreach($datos as $i => $row){
                    ?>

                <thead class="bg-dark text-white text-center">
                <tr>
                    <th>Codigo</th>
                    <th colspan="2">Descripcion</th>
                    <th>Cavtidad</th>
                    <th class="text-right">Precio</th>
                    <th class="text-right">Precio Total</th>
                    <th>Accion</th>
                </tr>
                </thead>
            </tbody>
    <?php  } ?>
        </table>

        <div class="col-lg-2 mx-auto">
            <div class="card card-body ">
            <!--  -->
                <a class = "btn btn-blok btn-dark" type="text" href="ajax/showFactura.php">Factura</a>
            </div>
        </div>
  </div>
</div>
<?php

}
 } ;// fin de validacion permisos de ingreso


rutFromFin();
?>

<!--    Datatables-->


<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>

<script>
      $(document).ready(function() {
          $('#tablaUsuarios').DataTable( {
            language: {
                    processing: "Tratamiento en curso...",
                    search: "Buscar&nbsp;:",
                    lengthMenu: "Ordenar por _MENU_ ",
                    info: "Mostrando _START_ al _END_ de un total de _TOTAL_",
                    infoEmpty: "No existen datos.",
                    infoFiltered: "(filtrado de _MAX_ elementos en total)",
                    infoPostFix: "",
                    loadingRecords: "Cargando...",
                    zeroRecords: "No se encontraron datos con tu busqueda",
                    emptyTable: "No hay datos disponibles en la tabla.",
                    paginate: {
                        first: "Primero",
                        previous: "Anterior",
                        next: "Siguiente",
                        last: "Ultimo"
                    },
                    aria: {
                        sortAscending: ": active para ordenar la columna en orden ascendente",
                        sortDescending: ": active para ordenar la columna en orden descendente"
                    }
                },
                scrollY: 400,
                lengthMenu: [ [10, 25, -1], [10, 25, "All"] ],

            "ajax":{
                "url": "../controlador/api.php?apicall=selectUsuarioFactura",
                "dataSrc":""
            },
            "columns":[
                {"data": "ID_us"},
                {"data": "nom1"},
                {"data": "ape1"},
                {"data": "correo"},
                {"data": "nom_rol"},
                {"data": "estado"},
                {"defaultContent": "<button class='btn btn-primary'>Detalles</button>"}
            ]  
          });
      });
    </script>