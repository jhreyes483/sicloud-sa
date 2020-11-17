<?php

require_once '../controlador/controlador.php';
require_once '../controlador/controladorsession.php';
//rutApi();
$db = new ControllerDoc();
//session_destroy();
// se pasan los parametros requeridos a esta funcion
function isTheseParametersAvailable($params){
   $avaible = true;
   $missingparams = '';

   foreach($params as $param){
      if(!isset($_POST[$param]) || strlen($_POST[$param]) <=0 ){
         $avaible = false;
         $missingparams = $missingparams.','.$param;
      }
   }
   // Si faltan parametros
   if(!$avaible){
      $response =[];
      $response['error'] = true;
      $response['message'] = 'Parametros:'.substr($missingparams, 1, strlen($missingparams)).'vacion'; 
      // Error de visualizacion
      echo json_encode($response);
      // detener la ejecucion adicional
      die();
   }
}
   //Una matriz que muestra la respuesta de la api
   $response = [];
   /*
   Si se trata de una llamada api
   que significa que un parameetro get llamado se establece una URL
   y con estos parametros estamos concluyendo que es una llamada api
   */
if(isset($_GET['apicall'])){
   // Aqui van todos los llamados de la api
   switch ($_GET['apicall']) {
      // Opcion crear usuarios
      case 'createusuario':
      // Primero haremos la verificacion de parametros.
      isTheseParametersAvailable(  [ 'ID_us','nom1','nom2', 'ape1', 'ape2','fecha', 'pass', 'correo','FK_tipo_doc' ]  );
     // $db = new ControllerDoc();
      $result = $db->createUsuariosController(
         $_POST['ID_us'], 
         $_POST['nom1'],
         $_POST['nom2'],
         $_POST['ape1'],
         $_POST['ape2'],
         $_POST['fecha'],    
         $_POST['pass'],
        // $_POST['foto'],
         $_FILES['foto']['name'],
         $_POST['correo'],
         $_POST['FK_tipo_doc'],
         $_POST['FK_rol'],
         date('Y-m-d'),
         0,
         $_FILES['foto']['tmp_name'],
         $_POST['tel']
      );  
      if($result){
         //esto significa que no hay ningun error
         $response['error']    = false;
         $_SESSION['message']  = $response['message'] = 'Usuario agregado correctamente';
         $_SESSION['color']    = "success";
      }else{ 
         $response['error']    = true;
         $_SESSION['message']  = $response['message'] = 'ocurrio un error, intenta nuevamente';
         $_SESSION['color']    = "danger";
      }
      header( 'location:  ../index.php');
      break;
      case 'readusuario';
         $response['error']     = false;
         $response['message']   = 'Solicitud completada correctamente';
         $response['contenido'] = $db->readUsuariosController();
      break;
      case 'elimianarUsuario';
         $bool =   $db->eliminarUsuario( $_GET['id'] );
         if( $bool ){
            $response['error']   =  false;
            $response['message'] =  $_SESSION['message'] = 'Elimino usuario de manera exitosa';
            $_SESSION['color']   = 'success';
         }else{
            $response['error']    =  true;
            $response['message']  = $_SESSION['message'] ='No elimino usuario';
            $_SESSION['color']    = 'danger';

         }
       echo "<script>window.location.replace('../vista/TablaUsuario.php')</script>"; 
      break;        
      case 'actualizarUsuario';
         $array =
         [  
         $_POST['ID_us'], 
         $_POST['nom1'],
         $_POST['nom2'],
         $_POST['ape1'],
         $_POST['ape2'],
         $_POST['fecha'],    
         '',
         $_POST['foto' ],    
         $_POST['correo'],
         $_POST['FK_tipo_doc'],
         $_POST['FK_rol']
         ];
         $bool1 =   $db->actualizarDatosUsuario($_GET['id'], $array );
         if( $bool ){
           $response['error']    = false;
           $response['message']  = $_SESSION['message']  = "Actualizo usuario";
           $_SESSION['color']    = "success";
         }else{
           $response['error']    = true;
           $response['message']  = $_SESSION['message']  = "Error no actualizo usuario"; 
           $_SESSION['color']    = "danger";
         }

         header( 'location:  ../vista/CU009-controlUsuarios.php?documento='.$_GET['id'].'&accion=bId');
      break;
      case 'loginusuario':
         isTheseParametersAvailable( ['nDoc', 'pass', 'tDoc'] );
         $result = $db->loginUsuarioController(
            $_POST['nDoc'],
            $_POST['pass'],
            $_POST['tDoc']);
         if(!$result){
            $response['error']     = true;
            $response['menssage']  = $_SESSION['message'] = 'Credenciales no validas';
            $_SESSION['color']     = "danger";
         }else{
            $response['error']      = false;
            $_SESSION['color']      = "success";
            $response['contenido']  = $result;
         }
      break;
      case 'activarCuenta':
         $result = $db->activarCuenta($_GET['id'] );
         if(!$result){
            $response['error']      = true;
            $response['menssage']   = $_SESSION['message']    = 'No activo cuenta';
            $_SESSION['color']      = 'danger';
         }else{
            $response['error']      = false;
            $response['message']    = $_SESSION['message']    = 'Activo cuenta';
            $response['contenido']  = $result;

            $_SESSION['color']      = 'success';
         }
         header( 'location:  ../vista/CU009-controlUsuarios.php');
      break;
      case 'desactivarUsuario':
         $result = $db->desactivarCuenta($_GET['id'] );
         if(!$result){
            $response['error']      = true;
            $response['menssage']   = $_SESSION['message'] = 'No desactivo cuenta';
            $response['contenido']  = $result;
            $_SESSION['color']      =  'danger';
            
         }else{
            $response['error']      = false;
            $response['message']    = $_SESSION['message'] = 'Desactivo cuenta';
            $response['contenido']  = $result;
            $_SESSION['color']      =  'success';
            header( 'location:  ../vista/CU009-controlUsuarios.php');
         }
      break;
      // insertar producto modulo - CU004-crearproductos.php
      case 'insertProducto':
         $a =[
            $_POST['ID_prod'],
            $_POST['nom_prod'],
            $_POST['val_prod'],
            $_POST['stok_prod'],
            $_POST['estado_prod'],
            $_POST['CF_categoria'],
            $_POST['CF_tipo_medida'],
            $_FILES['foto']['name'],
            $_FILES['foto']['tmp_name']

         ];
         $result = $db->insertarProducto( $a );
         if(!$result){
            $response['error']      = true;
            $response['menssage']   = $_SESSION['message'] = 'No inserto producto';
            $response['contenido']  = $result;
            $_SESSION['color']      = 'Danger';
            
         }else{
            $response['error']      = false;
            $response['message']    = $_SESSION['message'] = 'Inserto producto'; 
            $response['contenido']  = $result;
            $_SESSION['color']      = 'success';
         }
         header( 'location:  ../vista/CU004-crearProductos.php');
      break;

      case 'IngresarCantidad':
          //$cant, $stock, $id
         $a =[
            $_POST['cantidad'],
            $_POST['stok'],
            $_GET['id'],
         ];
         $result = $db->inserCatidadProducto( $a );
         if($result){
            $response['error']      = true;
            $response['menssage']   = $_SESSION['message'] = 'Registro entrega';
            $response['contenido']  = $result;
            $_SESSION['color']      = 'success';
            
         }else{
            $response['error']      = false;
            $response['message']    = $_SESSION['message'] = 'Error, no registro entrega'; 
            $response['contenido']  = $result;
            $_SESSION['color']      = 'danger';
         }
         header( 'location:  ../vista/CU003-ingresoProducto.php');
      break;





      
      
      case 'eliminarTelefono':
         $r= $db->eliminarTelefono($_GET['id']);
         if($r){
            $response['error']      = true;
            $response['menssage']   = $_SESSION['message'] = 'No elimino telefono';
            $response['contenido']  = $r;
            $_SESSION['color']      = 'danger';
            
         }else{
            $response['error']      = false;
            $response['message']    = $_SESSION['message'] ='Elimino telefono'; 
            $response['contenido']  = $r;
            $_SESSION['color']      = 'success';
         }
      break;
      case 'EliminarProducto':
         $r= $db->EliminarProducto($_GET['id']);
         if(!$r){
            $response['error']      = true;
            $response['menssage']   = $_SESSION['message'] = 'No elimino producto';
            $response['contenido']  = $r;
            $_SESSION['color']      = 'danger';
         }else{
            $response['error']      = false;
            $response['message']    = $_SESSION['message'] = 'Elimino producto'; 
            $response['contenido']  = $r;
            $_SESSION['color']      = 'success';
         }
      break;
      case 'insertcategoria':
         $a = [
            $_POST['nom_categoria']
         ];
          $r = $db->insertCategoria($a);
          if(!$r){
            $response['error']      = true;
            $response['menssage']   =  $_SESSION['message'] = "Error no creo categoria";
            $response['contenido']  = $r;    
         }else{
            $response['error']      = false;
            $response['message']    = $_SESSION['message'] = 'Inserto categoria'; 
            $response['contenido']  = $r;
            $_SESSION['message']    = "Inserto categoria";
            $_SESSION['color']      = "success";
         }
      header( 'location:  ../vista/formCategoria.php');
      break;
      case 'eliminarCategoria':
         $a = [
            $_GET['id']
         ];
          $r = $db->eliminarCategoria($a);
          if(!$r){
            $response['error']      = true;
            $response['menssage']   = $_SESSION['message']  = "Error no creo categoria";
            $response['contenido']  = $r;
            $_SESSION['color']      = "danger";
            
         }else{
            $response['error']      = false;
            $response['message']    = $_SESSION['message']    = "Elimino categoria"; 
            $response['contenido']  = $r;
            $_SESSION['color']      = "success";
         }
      header( 'location:  ../vista/formCategoria.php');
      break;

      case 'eliminarEmpresa':
         $a = [
            $_GET['id']
         ];
          $r = $db->eliminarEmpresa($a);
          if($r){
            $response['error']      = true;
            $response['menssage']   = $_SESSION['message'] = "Elimino empresa";
            $response['contenido']  = $r;
            $_SESSION['color']      = "success";
         }else{
            $response['error']      = false;
            $response['message']    = $_SESSION['message'] = "Error no elimino empresa";
            $response['contenido']  = $r;
            $_SESSION['color']      = "danger";
         }
      header( 'location:  ../vista/formEmpresa.php');
      break;
      case 'insertUdateCategoria':
         $a = [
            $_GET['id'], 
            $_POST['categoria']
         ];
          $r = $db->actualizarDatosCategoria($a);
          if($r){
            $response['error']      = true;
            $response['menssage']   = $_SESSION['message'] = "Error, no Actualizo empresa";
            $response['contenido']  = $r;
            $_SESSION['color']      = "danger";
         }else{
            $response['error']      = false;
            $response['message']    = $_SESSION['message'] = "Actualizo empresa";
            $response['contenido']  = $r;
            $_SESSION['color']      = "success";
         }
      header( 'location:  ../vista/formCategoria.php');
      break;
      case 'insertUdateEmpresa':
         $a = [
            $_POST['ID_rut'],
            $_POST['nom_empresa'],
         ];
          $r = $db->actualizarDatosEmpresa($a);
          if($r){
            $response['error']      = true;
            $response['menssage']   = $_SESSION['message'] = "Error, no actualizo empresa";
            $response['contenido']  = $r;
            $_SESSION['color']      = "danger";
         }else{
            $response['error']      = false;
            $response['message']    = $_SESSION['message']    = "Actualizo empresa";
            $response['contenido']  = $r;
            $_SESSION['color']      = "success";
         }
      header( 'location:  ../vista/formEmpresa.php');
      break;
      case 'insertEmpresa':
         $a = [
            $_POST['ID_rut'],
            $_POST['nom_empresa']
         ];
          $r = $db->insertEmpresa($a);
          if($r == false){
            $response['error']      = true;
            $response['menssage']   = $_SESSION['message'] = 'Error, no creo empresa';
            $response['contenido']  = $r;
            $_SESSION['color']      = "danger";
         }else{
            $response['error']      = false;
            $response['message']    = $_SESSION['message']  = "Creo empresa";
            $response['contenido']  = $r;

            $_SESSION['color']      = "success";
         }
      header( 'location:  ../vista/formEmpresa.php');
      break;
      case 'insertMedida':
         $a = [
            $_POST['nom_medida'],
            $_POST['acron_medida']
         ];
          $r = $db->insertMedia($a);
          if($r){
            $response['error']      = false;
            $response['menssage']   = $_SESSION['message']  = 'Creo unidad medida';
            $response['contenido']  = $r;
            $_SESSION['color']      = 'success';
         }else{
            $response['error']      =  true;
            $response['message']    = $_SESSION['message']  = "Error, no creo unidad de medida";
            $response['contenido']  = $r;
            
            $_SESSION['color']      = 'danger';
         }
      header( 'location:  ../vista/formMedida.php');
      break;
      case 'eliminarMedida':
         $a = [
            $_GET['id'],
         ];
  
          $r = $db->eliminarDatosMedia($a);
          if($r){
            $response['error']      = false;
            $response['menssage']   = $_SESSION['message'] = 'Elimino medida';
            $response['contenido']  = $r;
            $_SESSION['color']      = 'success';
         }else{
            $response['error']      =  true;
            $response['message']    = $_SESSION['message'] = "Error, no creo unidad de medida";
            $response['contenido']  = $r;
            $_SESSION['color']      = 'danger';
         }
      header( 'location:  ../vista/formMedida.php');
      break;
      case 'insertUdateMedia':
         $a = [
            $_GET['id'],
            $_POST['nom'],
            $_POST['acron']
         ];
  
          $r = $db->actualizarDatosMedida($a);
          if($r){
            $response['error']      = false;
            $response['menssage']   = $_SESSION['message'] = 'Actualizar medida';
            $response['contenido']  = $r;
            $_SESSION['color']      = 'success';
         }else{
            $response['error']      =  true;
            $response['message']    = $_SESSION['message'] = 'Error, Al actulizar medida';
            $response['contenido']  = $r;
            $_SESSION['color']      = 'danger';
         }
      header( 'location:  ../vista/formMedida.php');
      break;
      case 'actualizarDatosPers':
         $a = [
            $_GET['id'],
            $_POST['nom1'],
            $_POST['nom2'],
            $_POST['ape1'],
            $_POST['ape2'],
            $_POST['fecha'],
            $_POST['correo']
         ];
  
          $r = $db->insertUpdateUsuarioCliente($a);
          if($r){
            $response['error']      = false;
            $response['menssage']   = $_SESSION['message'] = 'Actualizo datos';
            $response['contenido']  = $r;
            $_SESSION['color']      = 'success';
         }else{
            $response['error']      =  true;
            $response['message']    = $_SESSION['message']  = 'Error, Al actulizar datos';
            $response['contenido']  = $r;
            $_SESSION['color']      = 'danger';
         }
      header( 'location:  ../vista/misdatos.php');
      break;
      case 'cambioContrasena':
         $a = [
            $_POST['id'],
            $_POST['passA'],
            $_POST['passN'],
            $_POST['passN2']
         ];
          $r = $db->validaContraseña($a);
          if($r){
            $response['error']      = false;
            $response['menssage']   = $_SESSION['message'] ='Cambio contraseña de manera exitosa';
            $response['contenido']  = $r;
            $_SESSION['color']      = 'success';
         }else{
            $response['error']      =  true;
            $response['message']    = $_SESSION['message'] = 'Error, al cambio contraseña'; 
            $_SESSION['color']      = 'danger';
         }
      header( 'location:  ../vista/cambioContraseña.php');
      break;
      case 'cambioContrasenaCorreo':
         $a = [
            $_POST['tipo_doc'],
            $_POST['documento'],
            $_POST['email'],
            $_POST['fActual'],
            $_POST['fCaduc'],
            $_POST['token']
         ];
          $r = $db->validarCredecilesCorrreo($a);
          if($r){
            $response['error']      = false;
            $response['menssage']   = $_SESSION['message'] = 'Cambio contraseña de manera exitosa';
            $response['contenido']  = $r;
            $_SESSION['color']      = 'success';

         }else{
            $response['error']      =  true;
            $response['message']    = $_SESSION['message'] = 'Error, al cambio contraseña'; 
            header( 'location:  ../vista/forgot_password/dist/index.php');
            $_SESSION['color']      = 'danger';
         }
      break;
      case 'inicionRol':
          include_once '../controlador/controladorsession.php';
          $objSession = new Session();
          $objSession->verificarAcceso();
      break;
      case 'notificLeida':
         $r = $db->notificacionLeida($_GET['id']);
         if($r){
           $response['error']      = false;
           $response['menssage']   = $_SESSION['message'] = 'Update exitoso exitosa';
           $_SESSION['color']      = 'success';
        }else{
           $response['error']      =  true;
           $response['message']    = $_SESSION['message'] = 'Error, aupdate'; 
           $_SESSION['color']      = 'danger';
        }
     break;
     case 'deleteNotific':
      //die($_GET['id']);
            
            $r = $db->deleteNotific( $_GET['id'] );
            if($r){
              $response['error']      = false;
              $response['menssage']   = $_SESSION['message'] = 'Elimino log';
              $_SESSION['color']      = 'success';
           }else{
              $response['error']      =  true;
              $response['message']    = $_SESSION['message'] = 'Error al eliminar log'; 
              $_SESSION['color']      = 'danger';
           }
           header( 'location:  ../vista/formNotificacion.php');
         break;
     case 'deleteLog':
//die($_GET['id']);
      
      $r = $db->deleteLog( $_GET['id'] );
      if($r){
        $response['error']      = false;
        $response['menssage']   = $_SESSION['message'] = 'Elimino log';
        $_SESSION['color']      = 'success';
     }else{
        $response['error']      =  true;
        $response['message']    = $_SESSION['message'] = 'Error al eliminar log'; 
        $_SESSION['color']      = 'danger';
        
     }
     header( 'location:  ../vista/formControl.php');
  break;

      default:
      $response['error']      = true;
      $response['message']    = 'ingreso a api "no esta en ningun metodo"'; 
      break;
   }
}else{
   // Si no es un api el que se estaq invocando
   // Empujar los valores apropiados en la consulta json
   $response['message'] = 'Llamado invalido del api';
}

echo json_encode($response);
