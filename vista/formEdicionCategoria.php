<?php
include_once '../controlador/controladorrutas.php';
rutFromIni();

//-----------------------------------------------------------------------------------

cardtitulo('Edicion categoria');
//Accion editar 
if ((isset($_GET['id']))) {
    $id = $_GET['id'];
?>
    <div class="col-md-2 col col-mx-10 mx-auto">
        <div class="card">
            <div class="card-header">Registro</div>
            <div class="card-body">
                <form action="../controlador/api.php?id=<?php echo $_GET['id'] ?>&&apicall=insertUdateCategoria" method="POST">
                    <?php 
                    $objModCat = new ControllerDoc();
                    $datos = $objModCat->verCategoriaId($id);
                    foreach($datos as $i=> $row){
                     ?>
                    
                        <div class="form-group"><input class="form-control" type="text" name="categoria" placeholder="Categoria" value="<?php echo $row['nom_categoria']  ?>" required autofocus maxlength="30"></div>
                    <?php } ?>
                    <input type="hidden" name="accion" value="insertUdateCategoria">
                    <div class="form-group"><input class="form-control btn btn-primary" type="submit" name="submit" value="Actualizar categoria"></div>
                </form>
            </div><!-- fin card body -->
        </div><!-- fin de card -->
    </div>
<?php
 }
 rutFinFooterFrom();
 rutFromFin();
?>