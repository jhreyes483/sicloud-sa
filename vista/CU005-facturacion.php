<?php
include_once '../controlador/controladorrutas.php';
include_once '../controlador/controllerFacturacion.php';
rutFromIni();
$objConF    = new ControllerFactura();
$objSession = new Session();
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

    if (isset($_GET)) extract($_GET);
    if (isset($_POST)) extract($_POST);
    if (isset($ID)) {
        $aC = $objConF->facturacion($ID);
        if ($aC['response_status'] == 'OK') {
            $aU    = $aC['response_msg'][0];
            $aP    = $aC['response_msg'][1];
            $aPgo  = $aC['response_msg'][2];
            $aTipV = $aC['response_msg'][3];
        } else {
            $error = '<div class="col-lg-6 col-12 col-sm-12 shadow-lg mx-auto text-center my-4  alert alert-danger alert-dismissible fade show" role="alert">
            <h1>' . $aC['response_msg'] . '</h1> <br> 
            <a class="btn btn-outline-primary"  href="">Seleccionar otra fecha</a>
         </div>';
        }
    }

    $v = new CifrasEnLetras();
    //Convertimos el total en letras
?>



    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Facturacion</title>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css" />
    </head>

    <body>
        <div class="container-fluid col-md-8 my-4">
            <?php
            if (isset($error)) die($error);
            if (isset($_SESSION['message'])) {
            ?>
                <!-- alerta boostrap -->
                <div class="text-center  alert alert-<?= $_SESSION['color']   ?> alert-dismissible fade show" role="alert">
                    <?= $_SESSION['message']  ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php
                setMessage();
            }
            ?>
            <div class="row">
                <!-- Formulario datos cliente---------------------------------------------------------------------------------------------- -->
                <div class="col-md-12">
                    <h2 class="my-4 e">Nueva venta</h2>
                    <p class="e">Datos de cliente</p>
                    <div class="card card-body">

                        <form action="" method="post">
                            <i class="fas fa-user-plus mr-2"></i>
                            <button type="submit" class="btn btn-outline-success col-md-1 my-1 btn-sm">Buscar</button>

                            <div class="card card-body shadow">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group"><label for="">Cedula Cliente</label><input type="text" value="" class="form-control" name="ID" id="nit_cliente" /></div>
                                    </div><!-- fin de primera divicion de 3 -->
                        </form>
                        <?php
                        if (isset($aU) && ($aU > 0)) {
                        ?>
                            <div class="col-md-4">
                                <div class="form-group"><label for="">Nombre</label><input type=»text» readonly=»readonly» value="<?= $aU[0][2]  ?>" class="form-control" name="nom1" id="nit_cliente" /></div>
                            </div><!-- fin de primera divicion de 3 -->
                            <div class="col-md-4">
                                <div class="form-group"><label for="">Telefono</label><input type=»text» readonly=»readonly» value="<?= $aU[0][13]  ?>" class="form-control" name="tel" id="nit_cliente" /></div>
                            </div><!-- fin de primera divicion de 3 -->
                    </div>
                    <div class="row">

                        <div class="col-md-12">
                            <label for="" class="text center mx-auto">Direccion</label>
                            <input class="form-control" type=»text» readonly=»readonly» value="<?= $aU[0][14] ?>"></div>
                    </div>
                <?php
                        }
                ?>
                </div>
            </div>
        </div>

        <table id="productos" class="tablesorter">
            <thead class="bg-dark text-white text-center">
                <tr>
                    <th>Id producto</th>
                    <th>Producto</th>
                    <th>Stock</th>
                    <th>Cantidad</th>
                    <th><i class="fas fa-dollar-sign mr-2"></i>Valor</th>
                    <th>Categora</th>
                    <th>
                        Agregar
                    </th>
                </tr>
            </thead>


            <tbody>
                <?php

                if (isset($aP)) {
                    $ID = (isset($ID)) ? $ID : 0;
                    foreach ($aP as $i => $d) {
                ?>

                        <tr>
                            <form action="../controlador/controllerFacturacion.php" method="POST">
                                <td><input class="form-control" type=»text» readonly=»readonly» name="ID_prod" value="<?= $d[0] ?>"></td>
                                <td><input class="form-control" type=»text» readonly=»readonly» name="nom_prod" value="<?= $d[2] ?>"></td>
                                <td><input class="form-control" type=»text» readonly=»readonly» name="stok_prod" value="<?= $d[4] ?>"></td>
                                <td><input class="form-control" type=number name="cantidad" value="<?= 1 ?>"></td>
                                <td><input class="form-control" type=»text» readonly=»readonly» name="val_prod" value="<?= ($d[3])  ?>"></td>
                                <td><input class="form-control" type=»text» readonly=»readonly» name="Cat" value="<?= $d[6] ?>"></td>
                                <input type="hidden" name="ID" value="<?= $ID ?? 0 ?>">
                                <input type="hidden" name="accion" value="agregar">
                                <td>
                                    <button type="submit" class="btn btn-circle btn-success btn-sm" href="" data-bs-toggle="tooltip" data-bs-placement="right" title="Agragar producto a factura">
                                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </form>
                        </tr>

            <?php
                    }
                }
                echo '</tbody>
                   ';
            }
            ?>
            <p class="e">Productos disponibles</p>
        </table>
        <?php
        if (isset($_SESSION['venta'])) {
        ?>
            <p class="e">Productos a facturar</p>
            <table>
                <thead class="bg-dark text-white text-center">
                    <tr>
                        <th>Id producto</th>
                        <th>Producto</th>
                        <th>Stock</th>
                        <th>Cantidad</th>
                        <th><i class="fas fa-dollar-sign mr-2"></i>Valor</th>
                        <th>Sub total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $ID = (isset($ID)) ? $ID : $u['usuario']['ID_us'];
                    foreach ($_SESSION['venta'] as $i => $d) {
                    ?>
                        <tr>
                            <form action="" method="post">
                                <td><input class="form-control" type=»text» readonly=»readonly» name="ID_prod" value="<?= $d[0] ?>"></td>
                                <td><input class="form-control" type=»text» readonly=»readonly» name="nom_prod" value="<?= $d[1] ?>"></td>
                                <td><input class="form-control" type=»number» readonly=»readonly» name="stok_prod" value="<?= $d[2] ?>"></td>
                                <td><input class="form-control" type=»number» readonly=»readonly» name="val_prod" value="<?= $d[3] ?>"></td>
                                <td><input class="form-control" type=»text» readonly=»readonly» name="Cat" value="$<?= number_format(($d[4] ?? 0), 0, ',', '.')  ?>"></td>
                                <td><input class="form-control" type=»number» readonly=»readonly» name="sub_total" value="$<?= number_format(($d[6] ?? 0), 0, ',', '.')  ?>"></td>
                                <td>
                                    <a class="btn btn-circle btn-danger" data-bs-toggle="tooltip" data-bs-placement="right" title="Eliminar porducto de factura" href="../controlador/controllerFacturacion.php?accion=eliminar&id_prod=<?php echo $i . '&ID=' . $ID; ?>">
                                        <i class="far fa-trash-alt" aria-hidden="true"></i>
                                    </a>
                                </td>
                                <input type="hidden" name="ID" value="<?= $ID ?>">
                                <input type="hidden" name="estado" value="Venta">
                            </form>
                        </tr>
                    <?php
                    }
                    $totFactura =  array_sum(array_column($_SESSION['venta'], 6));
                    $letra           =  ucfirst(($v->convertirEurosEnLetras($totFactura)));
                    $totFactura       =  number_format(($totFactura ?? 0), 0, ',', '.');

                    ?>
                    <tr>
                        <!-- la de los perros, no se acepta -->
                        <td colspan="4"> <em><?= $letra ?></em></td>
                        <div class="col-md-2">
                            <td colspan="1" class="mt-2" align="right"> <label for="total" class="lead">Total:</label> </td>
                        </div>
                        <div class="col-md-2 ">
                            <td colspan="" class="mt-2 lead" align="right"> $ <?= $totFactura ?> </td>
                        </div>
                    </tr>
                </tbody>
            </table>


            <!-- Datos venta---------------------------------------------------------------------------------------------- -->
            <div class="col-md-12">
                <br><br><br>
                <p class="e">Datos de venta</p>
                <div class="card card-body ">
                    <div class="card card-body  shadow">
                        <div class="row">
                            <form action="../controlador/controllerFacturacion.php" method="post">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <select class="form-control" name="pago">
                                            <?php
                                            foreach ($aPgo as $i => $row) {
                                            ?>
                                                <option value="<?= $row[0] ?>"><?= $row[1] ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <select class="form-control" name="tipo">
                                            <?php
                                            foreach ($aTipV as $i => $row) {
                                            ?>
                                                <option value="<?= $row[0] ?>"><?= $row[1] ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-12"></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 ">
                                        <input type="hidden" name="FK_tipo_doc" value="<?= $aU[0][0] ?>">
                                        <input type="hidden" name='ID' value="<?= $ID  ?>">
                                        <input type="hidden" name='accion' value="facturarInterno">
                                        <hr>
                                        <div class="card card-body shadow-lg">
                                            <button class="btn btn-success  my-2" type="submit"><i class="fas fa-file "></i>Facturar </button>
                                        </div>

                                    </div>
                                    <div class="col-lg-6 ">

                            </form>

                            <form action="../controlador/controllerFacturacion.php" method="post">
                                <input type="hidden" name="accion" value="anular">
                                <hr>
                                <div class="card card-body shadow-lg">
                                    <button class="btn btn-danger  my-2" type="submit"><i class="far fa-trash-alt"></i>Anular </button>
                                </div>

                            </form>
                        </div>

                    </div>
                    <img class="ml-auto" height="150" width="150" src="fonts/factura.png" alt="">
                </div>

                <p class="ml-auto">


                    <label for="">Vendedor</label><br>
                    <i class="fas fa-user-plus mr-2"></i>
                    <?php echo $u['usuario']['nom1'] . " " . $u['usuario']['ape1']; ?>
                </p>
            </div><!-- fin de row -->
            </div><!-- fin de row -->
            </div><!-- fin de card -->
            </div><br><br>
        <?php
        }
        ?>
        <div class="row">
            <div class="col-md-12 col-lg-12 mx-auto">
                <div class="card my-4">
                    <table id="tablaUsuarios" class="table table-striped bg-bordered bg-white table-sm col-md-12 col-sm-4 col-xs-12 mx-auto shadow rounded">
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
        <?php
        rutFromFin();
        ?>


        <script>
            $(document).ready(function() {
                $("#productos").tablesorter({
                    widgets: ['zebra'],
                    sortList: [
                        [4, 0]
                    ],
                    headers: {
                        0: {
                            sorter: false
                        },
                        1: {
                            sorter: false
                        },
                        2: {
                            sorter: false
                        }
                    }
                });
            });
        </script>
        <!--    Datatables-->


        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

        <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#tablaUsuarios').DataTable({
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
                    lengthMenu: [
                        [10, 25, -1],
                        [10, 25, "All"]
                    ],

                    "ajax": {
                        "url": "../controlador/api.php?apicall=selectUsuarioFactura",
                        "dataSrc": ""
                    },
                    "columns": [{
                            "data": "ID_us"
                        },
                        {
                            "data": "nom1"
                        },
                        {
                            "data": "ape1"
                        },
                        {
                            "data": "correo"
                        },
                        {
                            "data": "nom_rol"
                        },
                        {
                            "data": "estado"
                        },
                        {
                            "defaultContent": "<button class='btn btn-primary'>Detalles</button>"
                        }
                    ]
                });
            });
        </script>