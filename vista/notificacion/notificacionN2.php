<?php
if( !isset( $_SESSION['usuario'] )  ){
  @session_start();
}

?>
<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Notificaciones</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-striped bg-bordered bg-white table-sm col-md-10 col-sm-4 col-xs-12 mx-auto">
          <tbody>
            <tr>
              <?php
              if (isset($_SESSION['usuario'])) {
                $datos = $_SESSION['notic'];
                foreach ($datos as $row) {
                 
              ?>
            <tr>
              <td>
              <?php if(   $row['FK_not'] == 1 && $row['descript'] !=0    ){ $m = " Id usuario: ";}else{ $m ="" ;}  ?>
              <a class="btn btn-success btn-circle" href=""><i class="fas fa-bell fa-fw"></i><a href="CU009-controlUsuarios.php?documento=<?php echo $row['descript'] ?>&accion=bId"><?php echo "  " .$row['nom_tipo'].$m.$row['descript'] ?></a></a>
              </td>
            </tr>
        <?php  }
              } else echo "Error No ha inciado sesion"; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php



?>