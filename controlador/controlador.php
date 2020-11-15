
<?php
//session_destroy();
include_once '../modelo/class.sql.php';
include_once 'controladorsession.php';
date_default_timezone_set("America/Bogota");




class ControllerDoc
{
    private $session, $objModUs;


    public function __construct(){
        $this->objModUs    = SQL::ningunDato();
        $this->objSession  = Session::ningunDato();
    }
    public function selectDocumento(){
        return $this->objModUs->verDocumeto();
    }
    
    public function loginUsuarioController($ID_us,  $pass, $doc){
        $datosController[]        = [$ID_us, $pass, $doc];
        $USER                     = $this->objModUs->loginUsuarioModel($datosController);
    

        if( $USER ){ 
            $_SESSION['usuario']  =  $this->objSession->encriptaSesion($USER);
            $this->session         = $this->objSession->desencriptaSesion();
            $id_rol               =  openssl_decrypt( $_SESSION['usuario']['ID_rol_n'], COD, KEY);
            $_SESSION['notic']    =  $this->verNotificaciones(   $id_rol  );
            $id_rol               =  null;
            $this->objSession->verificarAcceso();
            $this->session        =  $this->objSession->desencriptaSesion();

            return  $USER;
        }else{
            header("location: ../vista/loginregistrar.php");
            $_SESSION['message'] = 'Contraseña incorreta o usuario no registrado';
            $_SESSION['color']   = 'danger';
        }
    }
        

    public function createUsuariosController(
        $ID_us,
        $nom1,
        $nom2,
        $ape1,
        $ape2,
        $fecha,
        $pass,
        $foto1,
        $correo,
        $FK_tipo_doc,
        $FK_rol,
        $fechaC,
        $estado,
        $ruta,
        $tel
    ) 
    {
        $datosController[] = [
            0         =>  $ID_us,
            1         =>  $nom1,
            2         =>  $nom2,
            3         =>  $ape1,
            4         =>  $ape2,
            5         =>  $fecha,
            6         =>  $pass,
            7         =>  $foto1,
            8         =>  $correo,
            9         =>  $FK_tipo_doc,
            10        =>  $FK_rol,
            11        =>  $fechaC,
            12        =>  $estado,
            13        =>  $ruta,
            14        =>  $tel
        ];
         $bool0 = $this->objModUs->InsertUsuario($datosController, 'usuario');
        if($bool0){
        $bool1 = $this->objModUs->insertrRolUs($datosController);
            if($bool1){
                // Insercion de foto
                //$foto = $_FILES['foto']['name'];
                // $ruta = $_FILES['foto']['tmp_name'];
                $destino = '../vista/fonts/us/'.$foto1;
                copy($ruta, $destino);
                $bool2 =  $this->objModUs->inserTfotoUs(  $foto1 ,  $ID_us );
                if($bool2){
                    $puntos = 2;
                    $fecha  = date('Y-m-d'); 
                    $aP = [
                        $puntos,
                        $fecha,
                        $ID_us,
                        $FK_tipo_doc
                    ];
                    $bool3   = $this->objModUs->insertPuntos($aP);
                }
                    if($bool3){
                        $aT= [
                            $tel,
                            $ID_us
                        ];
                       $boolF = $this->objModUs->insertTelefonoUsuario($aT);
                    }
                if( $boolF ){
                    $est = 0;
                    $descrip = $datosController[0][0];
                    $FK_rol = 1;
                    $FK_not = 1;
                    $aN =[ 
                        $est,
                        $descrip,
                        $FK_not,
                        $FK_rol
                    ];
                    $bool3 = $this->objModUs->notInsertUsuarioAdmin($aN);
                    if($bool3){
                        return true;
                    }else{
                        return false;
                    }



                  // return true;
                }else{
                $_SESSION['message'] = "Error el nombre de la foto ya existe";
                $_SESSION['color']   = "danger";
                }  
            }
        }
    }    
        /*
           // Insercion de foto
           $foto = $_FILES['foto']['name'];
           $ruta = $_FILES['foto']['tmp_name'];
           $destino = '../global/fonts/us/'.$foto;
           copy($ruta, $destino);
           $us = Usuario::ningunDato();
          $i = $us->inserTfoto($destino, $ID_us);

          */
    
    public function readUsuariosController(){
        return $this->objModUs->readUsuarioModel();
    }
    public function readUsuarioModel(){
        return $this->objModUs->readUsuarioModel();
    }
    public function eliminarUsuario($id_get){
        $r1 = $this->objModUs->eliminarUsuario($id_get);
        if($r1){
            $hora            = date("h:i:sa");
            $hora            = substr( $hora , 0, 8 );
            $fecha           = date('Y-m-d');
            $descrip         = "Usario eliminado ID " .$id_get;
            $FK_modific      = "2";
            $tDoc_us_session = $this->session['usuario']['ID_acronimo'] ;
            $ID_us_session   = $this->session['usuario']['ID_us'];
            $arm=[
                $descrip,
                $fecha,
                $hora, 
                $ID_us_session,
                $tDoc_us_session,
                $FK_modific
            ];
            $r4 = $this->objModUs->insertModificacion($arm);
            if($r4){
                return true;
            }else{
                return false;    
            }
        }
    }
    public function actualizarDatosUsuario($id, $array){
        $r1 = $this->objModUs->actualizarDatosUsuario($id, $array);
        if ($r1) {
            //METODO DE INSERSION ROL_USUARIO UPDATE
            $r2 = $this->objModUs->insertUpdateRol( $array);
            if($r2){
                $_SESSION['message'] = 'Actualizo rol';
                $_SESSION['danger']  = 'Error al actualizar rol';
                $hora                = date("h:i:sa");
                $hora                = substr( $hora , 0, 8 );
                $fecha               = date('Y-m-d');
                $descrip             = "Usario modificado ID " . $array[0];
                $FK_modific          = "4";
                $this->session       = $this->objSession->desencriptaSesion();
                $tDoc_us_session     = $this->session['usuario'] ['ID_acronimo'] ;
                $ID_us_session       = $this->session['usuario']['ID_us'];
               
                $arm=[
                    $descrip,
                    $fecha,
                    $hora, 
                    $ID_us_session,
                    $tDoc_us_session,
                    $FK_modific
                ];


                $r4 = $this->objModUs->insertModificacion($arm);
                if($r4){
                    return true;
                }else{
                    return false;    
                }
            }
        }else{
            $_SESSION['message'] = 'Error al actualizar usuario';
            $_SESSION['danger']  = 'Error al actualizar usuario';
            header( 'location:  ../vista/CU009-controlUsuarios.php?documento='.$_GET['id'].'&accion=bId');
            die();
        }
    }

    //Metodos de entidad usuario form CU009-controlusuarios.php
    public function selectUsuarioRol($id_rol)
    {
        return $this->objModUs->selectUsuarioRol($id_rol);
    }
    public function conteoUsuariosActivos()
    {
        return $this->objModUs->conteoUsuariosActivos();
    }
    public function conteoUsuariosInactivos()
    {
        return $this->objModUs->conteoUsuariosInactivos();
    }
    public function selectIdUsuario($id)
    {
        return $this->objModUs->selectIdUsuario($id);
    }
    public function selectUsuariosPendientes($est)
    {
        return $this->objModUs->selectUsuariosPendientes($est);
    }
    public function activarCuenta($id)
    {
        return $this->objModUs->activarCuenta($id);
    }

    public function desactivarCuenta($id)
    {
        return $this->objModUs->desactivarCuenta($id);
    }
    // Metodos de categoria 
    //"CU004-crearProductos.php"
    public function verCategorias()
    {
        return $this->objModUs->verCategorias();
    }
    //formCategoria.php
    public function verCategoria()
    {
        return $this->objModUs->verCategoria();
    }
    public function verCategoriaId($id)
    {
       return $this->objModUs->verCategoriaId($id);
    }
    public function verPromociones()
    {
        return $this->objModUs->verPromociones();
    }
    //Medida     
    public function verMedida()
    {
        return $this->objModUs->verMedida();
    }
    public function verDatoPorIdMedida($id)
    {
      //  return $this->objModMed->verDatoPorId($id);
    }
    public function verProveedor()
    {
       return $this->objModUs->verProveedor();
    }
    public function verProductos()
    { // CU003-ingresoproducto.php
        return $this->objModUs->verProductos();
    }
    public function verProductosIdCarrito($ID)
    {
        return $this->objModUs->verProductosIdCarrito($ID);
    }
    public function tablaProducto($id)
    {
        return $this->objModUs->verJoin($id);
    }
    public function verProductosId($id_p)
    {
        return $this->objModUs->verProductosId($id_p);
    }
    // U004-crearproductos.php
    public function insertarProducto($a)
{
       $bool = $this->objModUs->insertarProducto($a);  
    if($bool ) {
       //foreach($a as $i => $d){
            $foto    = $a[7];
            $ruta    = $a[8];
            $id_prod = $a[0];
        }
        $destino = '../vista/fonts/img/'.$foto;
        copy($ruta, $destino);
        $bool2 = $this->objModUs->inserTfotoProd( $foto, $id_prod);
        if($bool2){
            $_SESSION['message'] = "Inserto producto";
            $_SESSION['color']   = "success";
            return true;
        }else{
            $_SESSION['message'] = "Error no inserto foto";
            $_SESSION['color']   = "danger";
            return false;      
        }
    }
    

    public function verProductosGrafica()
    {
       return $this->objModUs->verProductosGrafica();
    }
    public function ConteoProductosT(){
       return $this->objModUs->ConteoProductosT();
    }
    public function EliminarProducto($id){
      return $this->objModUs->EliminarProducto($id);
    }
    // Catalogo
    public function buscarPorNombreProducto($id)
    {
        return $this->objModUs->buscarPorNombreProducto($id);
    }
    public function verPorCategoria($id)
    {
        return $this->objModUs->verPorCategoria($id);
    }

    //CU006-acomulaciondepuntos.php
    public function verPuntosUs()
    {
        return $this->objModUs->verPuntosUs();
    }

    //metodos de factura
    public function usuariosComprasRealizadas()
    {
        return $this->objModUs->usuariosComprasRealizadas();
    }
    public function verUsuarioFactura($id)
    {
        return $this->objModUs->verUsuarioFactura($id);
    }
    public function verjoinFactura()
    {
        return $this->objModUs->verjoinFactura();
    }
    public function verIntervaloFecha($f1, $f2)
    {
        return $this->objModUs->verIntervaloFecha($f1, $f2);
    }
    public function verDia(){
        return $this->objModus->verDia();
    }
    public function verSemana(){
        return $this->verSemana();
    }
    public function verMes(){
        return $this->verMes();
    }

    //modificaion db
    public function verJoinModificacionesDB()
    {
        return $this->objModUs->verJoinModificacionesDB();
    }

    //Metodos de Rol
    public function verRolId($id)
    {
        return $this->objModUs->verRolId($id);
    }
    public function verRol()
    {
        return $this->objModUs->verRol();
    }

    //metodos de ciudad
    public function verCiudad()
    {
        return $this->objModUs->verCiudad();
    }

    //metodos de empresa
    public function verDatoPorId($id){
        return $this->objModUs->verDatoPorId($id);
    }
    public function verEmpresa()
    {
       return $this->objModUs->verEmpresa();
    }

    public function verTelefonosUsuario(){
        return $this->objModUs->verTelefonosUsuario();
    }
    public function verTelefonosUsuarioPorID($id){
        return $this->objModUs->verTelefonosUsuarioPorID($id);
    }
    public function verTelefonosEmpresa(){
        return $this->objModUs->verTelefonosEmpresa();
    }
    public function verTelefonosUsuarioRol($rol){
        return $this->objModUs->verTelefonosUsuarioRol($rol);
    }

    public function eliminarTelefono($id){
        return $this->objModUs->eliminarTelefono($id);
    }
    //metodos de error
    public function verError()
    {
        return $this->objModUs->verError();
    }
    // Metodo inicio de session usuario
    public function verPuntosYusuario($id_us){
        return $this->objModUs->verPuntosYusuario($id_us);
    }
    // formCategoria.php
    public function insertCategoria($a){
        return $this->objModUs->insertCategoria($a);
    }
    public function actualizarDatosCategoria($a){
        return $this->objModUs->actualizarDatosCategoria($a);
    }
    public function eliminarCategoria($a){
        return $this->objModUs->eliminarCategoria($a);
    }
    // formEmpresa.php
    public function eliminarEmpresa($a){
        return $this->objModUs->eliminarEmpresa($a);
    }
    //formEmpresa.php
    public function insertEmpresa($a){
        return $this->objModUs->insertEmpresa($a);
    }
    public function verDatoEmpresaPorId($id){
        return $this->objModUs->verDatoEmpresaPorId($id);
    }
    public function actualizarDatosEmpresa($a){
        return  $this->objModUs->actualizarDatosEmpresa($a);
    }
    // formMedida.php
    public function insertMedia($a){
        return $this->objModUs->insertMedia($a);
    }
    public function eliminarDatosMedia($a){
        return $this->objModUs->eliminarDatosMedia($a);
    }
    public function verMedidaPorId($id){
        return $this->objModUs->verMedidaPorId($id);
    }
    public function actualizarDatosMedida($a){
        return $this->objModUs->actualizarDatosMedida($a);
    }

    public function selectUsuarios($id){
        return $this->objModUs->selectUsuarios($id);
    }
    public function insertUpdateUsuarioCliente($a){
        return $this->objModUs->insertUpdateUsuarioCliente($a);
    }
    public function validaContraseña($a){
        $passAterior =  $this->objModUs->validarPass( $a[0], $a[1] );
         // validacion de contraseña en base de datos
            if($passAterior){
                if( $a[2] ==  $a[3] ){
                    $r1 = $this->objModUs->cambioPass( $a[0], $a[3] );
                    if($r1){
                        $_SESSION['message'] = "Cambio contraseña de manera exitosa";
                        $_SESSION['color']   = "success";
                    }
                }else{
                    $_SESSION['message']     = "Campos de contraseña nueva no son iguales";
                    $_SESSION['color']       = "danger";
                }
            }else{
                $_SESSION['message'] = "Contraseña incorrecta";
                $_SESSION['color'] = "danger";
            }
    }
    public function validarCredecilesCorrreo($a){
        
         $r = $this->objModUs->validarCredecilesCorrreo($a);
         if($a[5] == 'sicloud'){
            if($r){
               $contraseña = 'jav';
               $contenido ='Restablecer contraseña<br>    usuario ;'.$r[0][0].'<br>'.'contraseña '.$contraseña.'<br>'.'Link: http://localhost/sicloud-sa/vista/forgot_password/dist/index.php';
               // datos correo
               $correo = $r[0][8];
               $asunto = 'Restablecer contraseña';
                mail($correo, $asunto, $contenido );
                $_SESSION['message']     = "Se envio mensaje al correo";
                $_SESSION['color']       = "success";  
              
                 $id =   $r[0][0];
                $pass =  $r[0][6];
                $t_doc = $r[0][9];
                $ar[] =   [
                    $id,
                    $pass,
                    $t_doc
                ];
                die();
              
                $USER = $this->objModUs->loginUsuarioModel($ar);
              //  $_SESSION['usuario'] = $USER;

                header( 'location:  ../vista/cambioContraseña.php');
                 die();
            }else{
                $_SESSION['message']     = "Datos incorrectos o usuario no registrado";
                $_SESSION['color']       = "danger";
              // echo 'no encontro datos'; die();
            }
        }else{
            $_SESSION['message']     = "No ingreso correctamente";
            $_SESSION['color']       = "success";
            //echo 'no ingreso correctamente'; die();
        }

    }

    // notificaciones nav
    public function verNotificaciones($id_rol){
        return  $this->objModUs->verNotificaciones($id_rol);
    } 
    // notificacion leida
    public function notificacionLeida($a){
        return $this->objModUs->notificacionLeida($a);
    }
    static function ver($dato, $sale=0, $float= false, $email=''){
        echo '<div style="background-color:#fbb; border:1px solid maroon;  margin:auto 5px; text-align:left;'. ($float? ' float:left;':'').' padding:7px; border-radius:7px; margin-top:10px">';
        if(is_array($dato) || is_object($dato) ){
            echo '<pre><br><b>&raquo;&raquo;&raquo; DEBUG</b><br>'; print_r($dato); echo '</pre>'; 
        }else{
            if(isset($dato)){
                echo '<b>&raquo;&raquo;&raquo; DEBUG &laquo;&laquo;&laquo;</b><br><br>'.nl2br($dato); 	
            }else{
                echo 'LA VARIABLE NO EXISTE';
            }
        }
        echo '</div>';
        if($sale==1) die();
    }


    //metodos telefonos
    
}







/*
if(isset($_GET['action']) && $_GET['action'] == 'login'){
    $instanciacontrolador = Usuario::ningunDato();
    $instanciacontrolador->LoginVista();
}
if(isset($_GET['accion']) && $_GET['accion'] == 'insert'){
    $instanciacontrolador = Usuario::ningunDato();
    $instanciacontrolador->InsertVista();
}
//include_once '../clases/class.empresa.php';
//include_once '../clases/class.conexion.php';
//include_once '../clases/class.categoria.php';
//include_once '../clases/class.producto.php';
//include_once '../clases/class.usuario.php';
//include_once '../clases/class.rol.php';
//include_once '../clases/class.medida.php';
//include_once '../clases/class.error.php';
//$us = Usuario::ningunDato();
//---------------------------------------------------------------
if ((isset($_GET['id'])) && (isset($_GET['accion']))) {
    //-------------------------------------------------------------------- 
    //USUARIO
    if ($_GET['accion'] == 'eliminarUsuario') {
        echo 'estoy en accion eliminar usuario';
        $id =  $_GET['id'];
        $objCon = Usuario::ningunDato();
        $objCon->eliminarUsuario($id);
    }
}
    //--------------------------------------------------------------------
/*
    //-------------------------------------------------------------------- 
    //MEDIDA   
    if ($_GET['accion'] == 'eliminarMedida') {
        include_once '../session/sessiones.php';
        // echo "estoy en eliminar medida";
        $id = $_GET['id'];
        Medida::eliminarDatosMedia($id);
    }
    //--------------------------------------------------------------------
    //EMPRESA
    //Eliminar
    if ($_GET['accion'] == 'eliminarEmpresa') {
        include_once '../session/sessiones.php';
        echo "estoy en accion eliminar empresa";
        $id = $_GET['id'];
        $objCon = Empresa::ningunDatoP();
        $objCon->eliminarEmpresa($id);
    }
    //--------------------------------------------------------------------
    //CATEGORIA
    //Eliminar
    if ($_GET['accion'] == 'eliminarCategoria') {
        include_once '../session/sessiones.php';
        echo 'estoy en accion eliminar categoria';
        $id =  $_GET['id'];
        $objCon = Categoria::ningunDatoC();
        $objCon->eliminarCategoria($id);
    }
    //----------------------------------------------------------------
    //PRODUCTO
    //Eliminar
    if ($_GET['accion'] == 'EliminarProducto') {
        include_once '../session/sessiones.php';
        $id = $_GET['id'];
        $producto =  Producto::eliminarProducto($id);
        Producto::eliminarProducto($id);
    }
    //-------------------------------------------------------------
    // USUARIO 
    //Aprobar Cuenta
    if ($_GET['accion'] == 'aprobarUsuario') {
        include_once '../session/sessiones.php';
        $id = $_GET['id'];
        $us->activarCuenta($id);
    }
    //-------------------------------------------------------------
    // USUARIO 
    //descatvar Cuenta
    if ($_GET['accion'] == 'desactivarUsuario') {
        include_once '../session/sessiones.php';
        $id = $_GET['id'];

     //   $us= Usuario::ningunDato();
        $us->desactivarCuenta($id);
    }
    //-----------------------------------------------------------
    //ROL
    if ($_GET['accion'] == 'eliminarRol') {
        include_once '../session/sessiones.php';
        $id = $_GET['id'];
        Rol::eliminarRol($id);
    }
    //-------------------------------------------------------------------------
    //ERROR
    //Eliminar
    if ($_GET['accion'] == 'eliminarError') {
        $id = $_GET['id'];
        ErrorLog::eliminarErrorLog($id);
    }
//-------------------------------------------------------------
// Ver factura = vista = ComprasUsuarios.php
//../metodos/get.php?accion=verFactura

if( $_REQUEST['accion'] == 'verFactura'){
    echo "Estoy en ver factura";
     $id =  $_REQUEST['id'];
    // header("Location: ../ajax/includes/factura.php?u=$id");
    header("Location: ../ajax/showFactura.php?u=$id");
}
//-------------------------------------------------------------

} // fin de isset accion y id

//----------------------------------------------
//SESSION
//cerrar sesion
//-----------------------------------------------------------------
if (isset($_GET['cerrarSesion'])) {

    //include_once 'cerrar.php';
    //include_once 'sessiones.php';

    if (isset($_SESSION['usuario'])) {
        session_unset();
        session_destroy();
    }
    session_start();
    $_SESSION['message'] = "Cerro sesion";
    $_SESSION['color'] = "primary";
    header("location: ../index.php");
    $_SESSION['message'] = "Cerro sesion";
    echo "cerro sesion";
}
// Lista de productos en pantalla
//-----------------------------------------------------------
//include_once '../session/sessiones.php';
//include_once './metodosDAO.php';
 //echo print_r ($_SESSION['usuario']);
 if(isset($_GET['ops'])){
$op = $_REQUEST['ops'];
switch ($op){
    case 1:
        unset($_SESSION['lista']);
        $lista=    $objMetodo =Producto::listaProductosImg();
       
        $_SESSION['lista']=$lista;
        header("Location: ../Catalogo.php");
        break;
    case 2;
        break; 

    }
}
//$objMod = Rol::ningunDato();
//-------------------------------------------------------------
// $objCon = new ControllerDoc();
// // $d = $objCon->verRol();
// echo '<pre>';  print_r($d); echo '</pre>'; die();   

*/

?>