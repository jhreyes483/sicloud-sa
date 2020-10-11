<?php
include_once 'class.conexion.php';
include_once '../controlador/controladorsession.php';


class SQL extends Conexion{
   public $db;
   public function __construct() {
      $this->db = Conexion::conexionPDO();
   } 
  
   static function ningunDato(){
      return new self ();
   }

   //============================================================================
   //CUSUARIO

  public function verPuntosYusuario($id){
    $sql = " SELECT U.ID_us  , U.nom1 , U.nom2 , U.ape1 , U.ape2 , U.fecha , U.pass , U.foto , U.correo , 
    TD.nom_doc , 
    RU.estado , 
    R.ID_rol_n , R.nom_rol , 
    P.puntos
       FROM tipo_doc TD 
       JOIN usuario U ON TD.ID_acronimo = U.FK_tipo_doc 
       JOIN rol_usuario RU ON U.ID_us = RU.FK_us 
       JOIN rol R ON FK_rol = R.ID_rol_n 
       JOIN puntos P ON U.ID_us = P.FK_us
       WHERE U.ID_us = :id"; 
    $c = $this->db->prepare($sql);
    $c->bindValue(':id', $id);
    $c->execute();
    $r = $c->fetch(PDO::FETCH_ASSOC);
    return $r;
 }


   // METODO INSERT USUARIO PDO MVC ---------------------------------------------------------------------
   public function InsertUsuario($a){ 
      $sql = "INSERT INTO usuario (ID_us, nom1, nom2, ape1, ape2, fecha, pass, foto, correo, FK_tipo_doc)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $insertar = $this->db->prepare($sql);
      foreach( $a as $i => $d   ){
      $insertar->bindValue(1, $d[0] );
      $insertar->bindValue(2, $d[1] );
      $insertar->bindValue(3, $d[2] );
      $insertar->bindValue(4, $d[3] );
      $insertar->bindValue(5, $d[4] );
      $insertar->bindValue(6, $d[5] );
      $insertar->bindValue(7, $d[6] );
      $insertar->bindValue(8, $d[7] );
      $insertar->bindValue(9, $d[8] );
      $insertar->bindValue(10, $d[9] );
      }
      $bool =    $insertar->execute();
      if($bool){
         return true;
      }else{
         return $a;
      }
   }
   //-------------------------------------------------------------------------------------------------------
   //METODO SELECT USUARIO PDO MVC----------------(FALTA METODO API)-------------------------------------
   public function readUsuarioModel(){
      $sql = 'SELECT * FROM usuario';
      $consulta = $this->db->prepare($sql);
      $consulta->execute();
      $result = $consulta->fetchAll();
      return $result;  
   }
   //-------------------------------------------------------------------------------------------------------
   //METODO LOGIN USUARIO PDO MVC--------------------------------------------------------------------------
   public function loginUsuarioModel($datosModel){
   $sql ="SELECT U.* , TD.ID_acronimo , 
   RU.estado , 
   R.ID_rol_n , R.nom_rol 
   FROM tipo_doc TD 
   JOIN usuario U ON TD.ID_acronimo = U.FK_tipo_doc 
   JOIN rol_usuario RU ON U.ID_us = RU.FK_us 
   JOIN rol R ON FK_rol = R.ID_rol_n  
   WHERE U.ID_us       =  :ID_us  
   AND U.pass          = :pass
   AND TD.ID_acronimo  = :ID_acronimo";
   //AND identificacion = :identificacion";
   $consulta = $this->db->prepare($sql);

 
   foreach($datosModel as $i =>  $d ){
      $consulta->bindValue( ':ID_us',       $d[0] , PDO::PARAM_STR);
      $consulta->bindValue( ':pass',        $d[1] , PDO::PARAM_STR );
      $consulta->bindValue( ':ID_acronimo', $d[2], PDO::PARAM_STR );
   }
   $consulta->execute();


  // $array = $consulta->fetchAll();
      if( $consulta->rowCount() > 0 ){ 
         $USER = $consulta->fetch(PDO::FETCH_ASSOC);
         if( !isset( $_SESSION['usuario']) ){
            session_start(); 
            $_SESSION['usuario']  = $USER;
         }   
         echo '<pre>'; print_r( $_SESSION['usuario'] );  echo '</pre>';
      //   echo '<br> hola'.$_SESSION['usuario']['nom1'];
      $_SESSION['message'] = "Login extoso";
      $_SESSION['color'] = "success";
      $objConSes= new Session(); 
      $objConSes->verificarAcceso();

         return  $USER ;
      }else{
        // return false;
      }
   }
   //-------------------------------------------------------------------------------------------------------
   //METODO DELETE USUARIO PDO MVC-------------------------(FALTA METODO API)-------------------------------
   public function eliminarUsuario($id_get){       
      $sql1 = "SET FOREIGN_KEY_CHECKS = 0";
      $consulta2 =$this->db->prepare($sql1);
      $rest1 = $consulta2->execute();
      if ($rest1) {
         $sql2 = "DELETE FROM usuario WHERE  ID_us = :id ";
         $consulta3=$this->db->prepare($sql2); 
         $consulta3->bindValue(":id", $id_get);
         $rest2=$consulta3->execute();
      }
      if ($rest2) {
         $sql3 = "SET FOREIGN_KEY_CHECKS = 1";
         $consulta4 = $this->db->prepare($sql3);
         $rest3=$consulta4->execute();
      if ($rest3) {
         //header("location: ../vista/TablaUsuario.php");
         echo "<script>alert('Elimino el registro')</script>"; echo "<script>window.location.replace('../vista/TablaUsuario.php')</script>"; }
      else{ echo "<script>alert('Error al eliminar el registro categoria')</script>"; echo "<script>window.location.replace('../vista/TablaUsuario.php')</script>";   } 

        
      }
   } // fin de metodo eliminar categoria

   //-------------------------------------------------------------------------------------------------------
   //METODO UPDATE USUARIO PDO MVC-------------------------(FALTA METODO API)-------------------------------
   public function actualizarDatosUsuario($id, $a){ 
      ///echo '<pre>'; print_r($a); echo '</pre>';  echo '<pre>'; print_r($id); echo '</pre>';    die();
      $sql = "UPDATE usuario SET ID_us = ?, nom1 = ?, nom2 = ?, ape1 = ?, ape2 = ?, fecha = ?, pass = ?, foto = ?, correo = ?, FK_tipo_doc = ?
      WHERE ID_us = ?";
      $insertar = $this->db->prepare($sql);
      $bool = $insertar->execute([$a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $id]);       
      $bool =  $insertar->execute();
      if($bool){
         return true;
      }else{
      return false;
         }
      }

/*
      public function  selectUsuarioRol($r){
        $sql = "SELECT U.FK_tipo_doc, U.ID_us, U.nom1, U.nom2, U.ape1, U.ape2, U.pass, U.foto, U.correo,
           R.nom_rol,  
           R_U.estado
           FROM sicloud.usuario U 
           JOIN  rol_usuario R_U ON R_U.FK_us = U.ID_us
           JOIN sicloud.rol  R ON R_U.FK_rol = R.ID_rol_n
           WHERE R.ID_rol_n  = :id
           ORDER BY u.nom1 asc";
        $consulta= $this->db->prepare($sql);
        $consulta->bindValue(":id", $r);
        $result = $consulta->execute();
        $result = $consulta->fetchAll();
        return $result;
      }

 */

public function  selectUsuarioRol($id){
    $sql = "SELECT distinct U.FK_tipo_doc, U.ID_us, U.nom1, U.nom2, U.ape1, U.ape2, U.pass, U.foto, U.correo, 
    R.nom_rol,  R.nom_rol,
    R_U.estado
    FROM usuario U 
    JOIN  rol_usuario R_U ON R_U.FK_us = U.ID_us
    JOIN rol  R ON R_U.FK_rol = R.ID_rol_n 
    WHERE R.ID_rol_n  = :id
     ";
    $c = $this->db->prepare($sql);
    $c->bindValue(":id", $id);
    $c->execute();
    $r = $c->fetchAll();
return $r;
}

      public function conteoUsuariosActivos(){
         $sql = "SELECT count(*) AS usuariosActivos 
            FROM usuario  U JOIN rol_usuario RU ON RU.FK_us = U.ID_us
            WHERE RU.estado = 1";
         $c= $this->db->prepare($sql);
          $c->execute();
         $r = $c->fetchAll();
         foreach( $r as $d ){
            $con =   $d[0];
         }
         return $con;
      }
      public function conteoUsuariosInactivos(){
         $sql = "SELECT count(*) AS usuariosActivos 
            FROM usuario  U 
            JOIN rol_usuario RU ON RU.FK_us = U.ID_us
            WHERE RU.estado = 0";
         $c= $this->db->prepare($sql);
         $c->execute();
         $r = $c->fetchAll();
        foreach($r as  $d){
           $con= $d[0];
        }
         return $con;
      }

        //busqueda por ID
  public function selectIdUsuario($id){
   $sql = "SELECT distinct U.FK_tipo_doc, U.ID_us, U.nom1, U.nom2, U.ape1, U.ape2, U.pass, U.foto, U.correo, 
      R.nom_rol,  R.nom_rol,
      R_U.estado
      FROM usuario U 
      JOIN  rol_usuario R_U ON R_U.FK_us = U.ID_us
      JOIN rol  R ON R_U.FK_rol = R.ID_rol_n 
      WHERE ID_us = :id
       ";
   $c = $this->db->prepare($sql);
   $c->bindValue(":id", $id);
   $c->execute();
   $r = $c->fetchAll();

   return $r;
 } // fin de busqueda por ID

 public function selectUsuariosPendientes($est){
   $sql = "SELECT U.FK_tipo_doc, U.ID_us, U.nom1, U.nom2, U.ape1, U.ape2, U.pass, U.foto, U.correo,  
      R.nom_rol, 
      R_U.estado
      FROM usuario U 
      JOIN  rol_usuario R_U ON R_U.FK_us = U.ID_us
      JOIN rol  R ON R_U.FK_rol = R.ID_rol_n 
      WHERE R_U.estado =:id ";
   $c = $this->db->prepare($sql);
   $c->bindValue(":id",$est);
   $c->execute();
   $r = $c->fetchAll();
   return $r;
 } //Busqueda por estado pendiente
   //aprobar solicitud
   public function activarCuenta($id){
      $sql = "UPDATE rol_usuario 
         SET rol_usuario.estado = 1 
         WHERE rol_usuario.FK_us = ?";
            $c = $this->db->prepare($sql);
            $bool = $c->execute( [$id]  );       
           
    //  $c->bindParam(":id",$id);
      if ($bool) {
         $_SESSION['message'] = "Desactivo cuenta de usuario";
         $_SESSION['color'] = "danger";
         return true;
      } else {
         $_SESSION['message'] = "Error al descativar cuenta";
         $_SESSION['color'] = "danger";
         return false;
      }
      //header("location: ../CU009-controlUsuarios.php ");
   } // fin de desactibar cuenta
 
      ///header("location: ../CU009-controlUsuarios.php ");
     // fin de aprbar solicitud

    public function desactivarCuenta($id){
      $sql = "UPDATE rol_usuario 
         SET rol_usuario.estado = 0 
         WHERE rol_usuario.FK_us = ?";
            $c = $this->db->prepare($sql);
            $bool = $c->execute( [$id]  );       
           
    //  $c->bindParam(":id",$id);
      if ($bool) {
         $_SESSION['message'] = "Desactivo cuenta de usuario";
         $_SESSION['color'] = "danger";
         return true;
      } else {
         $_SESSION['message'] = "Error al descativar cuenta";
         $_SESSION['color'] = "danger";
         return false;
      }
      //header("location: ../CU009-controlUsuarios.php ");
   } // fin de desactibar cuenta

   public function verPuntosUs(){
      $sql = "SELECT P.id_puntos, P.puntos, P.fecha , 
         U.nom1 , U.nom2 , U.ape1
         FROM puntos P 
         JOIN usuario U ON  P.FK_us =  U.ID_us
         ORDER BY U.nom1 asc";
   $c = $this->db->prepare($sql);
   $c->execute();
   $r = $c->fetchAll();
   return $r;
   }
   

   // Actualzacion de datos por rol usuario---------------------------------------------------------
  public function insertUpdateUsuarioCliente($idg){
   
   $sql1 = "SET FOREIGN_KEY_CHECKS = 0 ";
   $consulta1 = $this->db->prepare($sql1);
        $res =  $consulta1->execute();   
   if ($res) {
      $sql2 = "UPDATE usuario SET  nom1 = :nom1 ,  nom2 = :nom2 ,ape1 = :ape1 , ape2 = :ape2 , fecha = :fecha   , correo = :correo  WHERE  ID_us = :ID_us ";
      $res1 = $this->db->prepare($sql2);
   }   
   if ($res1) {
      $sql3 = "SET FOREIGN_KEY_CHECKS = 1";
      $res2 = $this->db->prepare($sql3);
   }
   if ($res2) {
      $_SESSION['message'] = $_SESSION['usuario']['nom1'] . ' Actualizo datos';
      $_SESSION['color'] = 'success';
   } else {
      $_SESSION['message'] = 'Error al actualizar datos';
      $_SESSION['color'] = 'danger';
   }
}

//===========================================================================0   

//===========================================================================
//CCATEGORIA

    // Insertar datos           C
    public function insertCategoria($a){
        $sql = "INSERT INTO categoria (nom_categoria ) VALUES (:nom_categoria )";
        $consultar = $this->db->prepare($sql);
        $consultar->bindValue(":nom_categoria", $a[0], PDO::PARAM_STR );
        $bool = $consultar->execute();
         if($bool){
            return true;
         }else{
            return $a;
        }
   }


    // Ver categorias           R
    public function verCategoria(){
        $sql = "SELECT * FROM categoria";
        $consulta = $this->db->prepare($sql);
        $consulta->execute();
        $result = $consulta->fetchAll(); 
        return $result;
    }


        // Eliminar categoria           D
   public function eliminarCategoria($a){       
      $sql1 = "SET FOREIGN_KEY_CHECKS = 0";
      $consulta2 =$this->db->prepare($sql1);
      $rest1 = $consulta2->execute();
      if ($rest1) {
         $sql2 = "DELETE FROM categoria WHERE  ID_categoria = :id ";
         $consulta3=$this->db->prepare($sql2); 
         $consulta3->bindValue(":id", $a[0], PDO::PARAM_INT);
         $rest2=$consulta3->execute();
      }
      if ($rest2) {
         $sql3 = "SET FOREIGN_KEY_CHECKS = 1";
         $consulta4 = $this->db->prepare($sql3);
         $rest3=$consulta4->execute();
         if ($rest3) {
            return true;
         } else{ 
            return false;
         } 
      }
   } // fin de metodo eliminar categoria



   // Actualizar datos             U
   public function actualizarDatosCategoria($a){
      $sql = 
      "UPDATE categoria 
         SET nom_categoria = :nom_categoria  
         WHERE ID_categoria = :ID_categoria ";
      $consulta = $this->db->prepare($sql);
      $consulta->bindValue(":ID_categoria", $a[0], PDO::PARAM_INT );
      $consulta->bindValue(":nom_categoria", $a[1], PDO::PARAM_STR);
      $result = $consulta->execute();
      if($result){ 
         return true;
      }else{
         return false;
      }
   }
    
     public function verCategoriaId($id){
         $sql = "SELECT * 
         FROM categoria
         WHERE ID_categoria = :ID_categoria";
         $consulta = $this->db->prepare($sql);
         $consulta->bindValue(":ID_categoria", $id);
         $consulta->execute();
         $result = $consulta->fetchAll();
         return $result;
     }
    // Ver categorias por id    R
    //static function verCategoriaId($id)

    //Capturar id
    public function capturarId(){
        //include_once 'class.conexon.php';
        //$con = new Conexion;
        $sql = "SELECT * FROM categoria ORDER BY ID_categoria DESC LIMIT 1 ";
        $stm = $this->db->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll(); 
        return $result;
        //$datos = $con->query($sql);
        //while ($row = $datos->fetch_array());
        //$id = $row['ID_categoria'];
        //return $id;
    } // fin de metodo capturar ID

    // Ver categiria sin conexion
    public function verCategorias(){
        //include_once 'class.conexon.php';
        //$d = new Conexion;
        $sql = "SELECT * FROM categoria
        order by nom_categoria asc";
        $stm = $this->db->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll(); 
        return $result;
        //$result = $d->query($sql);
        //return $result;
    } // fin de ver categoria

//============================================================================
   
//==============================================================
//CCIUADAD
//-----------------------------------------------------------------
public function verCiudad(){
   $sql = "SELECT id_ciudad, nom_ciudad FROM ciudad";
   $stm=$this->db->prepare($sql);
   $stm->execute();
   $result=$stm->fetchAll();
   return $result;
 }
 
 //-----------------------------------------------------------------
 // fin de metodo ver datos ciudad
 //----------------------------------------------------------------
 
 //-----------------------------------------------------------------
 
 public function insertCiudad(){
   $sql = "INSERT INTO ciudad (id_ciudad, nom_ciudad) VALUES (:id_ciudad, :nom_ciudad)";
   $stm = $this->db->prepare($sql);
   $stm->bindValue(":id_ciudad", $this->id_ciudad);
   $stm->bindValue(":nom_ciudad", $this->nom_ciudad);
   $insert = $stm->execute();
   if($insert){
       include_once '../controlador/controlodarsession.php';
       $_SESSION['message'] = "Registro Ciudad";
       $_SESSION['color'] = "success";
     }else{ 
       $_SESSION['message'] = "No registro Ciudad";
       $_SESSION['color'] = "success";
   }
 }
 
 //-----------------------------------------------------------------
 // fin de metodo registrar datos ciudad
 //----------------------------------------------------------------
 
 //-----------------------------------------------------------------
 public function verCiudadId($ID_ciudad){
   //include_once 'class.conexion.php';
   //$c = new Conexion;
   $sql = "SELECT id_ciudad , nom_ciudad FROM ciudad WHERE ID_ciudad = '$ID_ciudad' ORDER BY nom_ciudad ASC ";
   $stm = $this->db->prepare($sql);
   $stm->execute();
   $result=$stm->fetchAll();
   return $result;
   //$result = $c->query($sql);
   //return $result;
 }// fin de metodo ver datos ciudad

//===========================================================
 //============================================================
 //CDOCUMENTO
 public function verDocumeto(){
   $sql = "SELECT * FROM tipo_doc";
   $consulta = $this->db->prepare($sql);
   $consulta->execute();
   $result =  $consulta->fetchAll();
   return $result;
}
//==============================================================
//==============================================================
//CEMPRESA
    //Metodo insertar 
   public function insertEmpresa($a){
      $sql = "INSERT INTO empresa_provedor (ID_rut, nom_empresa)VALUES( :ID_rut, :nom_empresa)";
      $stm = $this->db->prepare($sql);
      $stm->bindValue(":ID_rut", $a[0], PDO::PARAM_STR);
      $stm->bindValue(":nom_empresa", $a[1], PDO::PARAM_STR);
      $insert = $stm->execute();
      if($insert){
         return true;
      }else{ 
         return false;
      }
   }

   public function verDatoEmpresaPorId($id){
      $sql = "SELECT * FROM empresa_provedor WHERE ID_rut = :ID ";
      $consulta= $this->db->prepare($sql);
      $consulta->bindValue(":ID", $id, PDO::PARAM_STR);
      $consulta->execute();
      $result = $consulta->fetchAll();
      return $result;
  }
   //====================================




  public function ver($dato, $sale=0, $float= false, $email=''){
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

  // Eliminar 
  public function eliminarEmpresa($a){
      $sql1 = "SET FOREIGN_KEY_CHECKS = 0 ";
      $consulta1 = $this->db->prepare($sql1);
      $res =  $consulta1->execute();
      if($res){
          $sql2 = "DELETE  FROM empresa_provedor WHERE ID_rut = :id ";
          $consulta2=$this->db->prepare($sql2); 
          $consulta2->bindValue(":id", $a[0], PDO::PARAM_STR);
          $res1 = $consulta2->execute();
     }
     if($res1){
          $sql3 = "SET FOREIGN_KEY_CHECKS = 1";
          $consulta3=$this->db->prepare($sql3);
          $res2 = $consulta3->execute();
     }

      if ($res2) {
         return true;
      } else {
         return false;
      }
  }
 
  public function verDatoPorId($id){
      $sql = "SELECT * FROM usuario WHERE ID_us = :ID ";
      $consulta= $this->db->prepare($sql);
      $consulta->bindValue(":ID", $id, PDO::PARAM_STR);
      $consulta->execute();
      $result = $consulta->fetchAll();
      return $result;
  }

  public function actualizarDatosEmpresa($a){ 
    // 
      $sql = "UPDATE empresa_provedor 
      SET nom_empresa = :nom_empresa 
      WHERE ID_rut = :ID ";
      $consulta = $this->db->prepare($sql);
      $consulta->bindValue(":ID",         $a[0], PDO::PARAM_STR);
      $consulta->bindValue(":nom_empresa",$a[1], PDO::PARAM_STR);
      $result = $consulta->execute();
      if ($result) {
         return true;
      } else {
         return false;
      }
  }

  // ver empresa
public function verEmpresa(){
   // $db = new Conexion;
    $sql = "SELECT * FROM empresa_provedor";
    $stm = $this->db->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll(); 
    return $result;
    
}
//==================================================================
//==================================================================
//CERROR

public function verError()
{

    //$c = new Conexion;
    $sql = "SELECT * FROM log_error";
    $stm = $this->db->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll();
    return $result;
    //$insert =  $c->query($sql);
    //return $insert;
} // fin de ver error

public function eliminarErrorLog($id)
{
    //include_once 'class.conexion.php';
    //$c = new Conexion;
    $sql1 = "SET FOREIGN_KEY_CHECKS = 0 ";
    $consulta1 = $this->db->prepare($sql1);
    $res =  $consulta1->execute();
    if ($res) {
        $sql2 = "DELETE FROM log_error WHERE ID_error = $id";
        $consulta2 = $this->db->prepare($sql2);
        $consulta2->bindValue(":id", $id);
        $res1 = $consulta2->execute();
    }
}   
//==================================================
//==================================================
//CFACTURA


public function verFecha($f)
{
    
    //$c = new Conexion;
    $sql = "SELECT cantidad, sum(f.total) as total, day(f.fecha) as dia
    from det_factura detf
    join factura f on f.ID_factura = detf.FK_det_factura
    where f.fecha = '$f'
    group by dia";
    $stm = $this->db->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll(); 
    return $result;


    //$dat =   $c->query($sql);
    //return $dat;
} // fin  de ver fecha U

//  METODOS

// verjoinFactura

public function verjoinFactura()
{
    //include_once 'class.conexion.php';

    $sql = "SELECT  ID_us  ,nom1, ape1 ,nom_prod, f.fecha , nom_tipo_pago , total
        from det_factura df
        join usuario u on df.CF_us = u.ID_us and df.CF_tipo_doc = u.FK_tipo_doc
        join producto p on df.FK_det_prod = p.ID_prod
        join factura f on df.FK_det_factura = f.ID_factura
        join tipo_pago  tp on f.FK_c_tipo_pago = tp.ID_tipo_pago  order by nom1 asc  ";
        $stm = $this->db->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll(); 
        return $result;
    //$con = new Conexion;
    //$dat =  $con->query($sql);
    //return $dat;
} // fin de metodo ver join factura


//----------------------------------------------------------

//-------------------------------------------------------------
//Consulta de que usuarios han realizado compras = Vista = comprasUsuarios.php
public function usuariosComprasRealizadas()
{
    $sql = "SELECT U.ID_us , U.nom2 , U.nom1 , U.ape1 , U.ape2 , U.foto , U.correo , DF.FK_det_factura , F.fecha
    from factura F join det_factura DF on F.ID_factura = DF.FK_det_factura
    right join  usuario U on U.ID_us = DF.CF_us
    order by (DF.FK_det_factura) desc, (F.fecha) desc ,(U.nom1) desc";
    $stm = $this->db->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll(); 
    return $result;
    //$consulta = $this->db->query($sql);
    //return $consulta;
}
//-------------------------------------------------------------
public function verDia()
{
    //include_once 'class.conexion.php';
    //$c = new Conexion;
    $sql = "SELECT cantidad, sum(f.total) as total, day(f.fecha) as dia
        from det_factura detf
        join factura f on f.ID_factura = detf.FK_det_factura
        group by dia";
    $stm = $this->db->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll(); 
    return $result;
    //$dat = $c->query($sql);
    //return $dat;
}
public function verSemana()
{
    //include_once 'class.conexion.php';
    //$c = new Conexion;
    $sql = "SELECT count(*) as 'cantidad',sum(F.total) as Total, DATE_ADD(
        DATE (F.fecha),
        interval(7 - dayofweek(F.fecha)) day)
        dia_final_semana FROM factura F
        group by dia_final_semana
        order by fecha asc";
    $stm = $this->db->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll(); 
    return $result;
    
    //$i = $c->query($sql);
    //return $i;
}
public function verMes()
{
    //include_once 'class.conexion.php';
    //$c = new Conexion;
    $sql = "SELECT count(*) as 'cantidad',sum(F.total) as Total, DATE_ADD(
        DATE (F.fecha),
        interval(30 - dayofmonth(F.fecha)) day)
        dia_final_mes FROM factura F
        group by dia_final_mes
        order by fecha asc";
    $stm = $this->db->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll(); 
    return $result;
    //$i = $c->query($sql);
    //return $i;
}
// valida factura en un periodo de fechas
public function verIntervaloFecha($fechaIni, $fechaFin)
{
    //include_once 'class.conexion.php';
    //$c = new Conexion;
    $sql = "SELECT * FROM factura
        WHERE fecha <= '$fechaFin' AND  fecha >= '$fechaIni' 
        ORDER BY fecha ASC";
    $stm = $this->db->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll(); 
    return $result;
    //$arr = $c->query($sql);
    //return $arr;
}
// Ver datos  usuario en factura
public function verUsuarioFactura($id){
    $sql = "SELECT U.nom1, U.nom2, U.ape1, U.ape2 , T.tel , D.dir
        FROM usuario U JOIN telefono T ON T.CF_us = U.ID_us
        JOIN direccion D ON D.CF_us = U.ID_us
        WHERE U.ID_us = ?
        LIMIT 1";
    $stm = $this->db->prepare($sql);
    $stm->bindValue( 1, $id, PDO::PARAM_STR );
    $stm->execute();
    $result = $stm->fetchAll(); 
    return $result;
}
public function verFactura($id)
{
    //$c = new Conexion;
    $sql = "SELECT   U.nom2 , U.ape1 , U.ape2 , U.correo , U.nom1 , F.ID_factura, F.fecha   , D.dir , TP.nom_tipo_pago , DF.cantidad , Pr.val_prod , TD.nom_doc , U.ID_us
        from factura F join tipo_pago TP on F.FK_c_tipo_pago = TP.ID_tipo_pago
        join det_factura DF on F.ID_factura = DF.FK_det_factura
        join producto Pr on Pr.ID_prod = DF.FK_det_prod
        join usuario U  on U.ID_us =  DF.CF_us
        join direccion D on D.CF_us = U.ID_us
        join tipo_doc TD on U.FK_tipo_doc = TD.ID_acronimo
        where ID_factura = '$id'
        limit 1";
    $stm = $this->db->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll(); 
    return $result;
    //$result = $c->query($sql);
    //return $result;
}
public function verFactural($id)
{
    //$c = new Conexion;
    $sql = "SELECT  U.nom2 , U.ape1 , U.ape2 , U.correo , U.nom1 , F.ID_factura, F.fecha   , D.dir , TP.nom_tipo_pago , DF.cantidad , Pr.val_prod , Pr.nom_prod
        from factura F join tipo_pago TP on F.FK_c_tipo_pago = TP.ID_tipo_pago
        join det_factura DF on F.ID_factura = DF.FK_det_factura
        join producto Pr on Pr.ID_prod = DF.FK_det_prod
        join usuario U  on U.ID_us =  DF.CF_us
        join direccion D on D.CF_us = U.ID_us
        where ID_factura = '$id'";
    $stm = $this->db->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll(); 
    return $result;
    //$result = $c->query($sql);
    //return $result;
}
//==============================================
//==============================================
//CLOCALIDAD

public  function verLocalidadId($ID_ciudad){
   //include_once 'class.conexion.php';
   //$c = new Conexion;
   $sql = "SELECT ID_localidad , nom_localidad FROM localidad WHERE FK_ciudad = '$ID_ciudad'";
           $stm = $this->db->prepare($sql);
           $stm->execute();
           $result = $stm->fetchAll(); 
           return $result;
   //$r = $c->query($sql);
   //return $r;
}
//============================================
//============================================
//CMEDIDA
public function insertMedia($a){
   $sql = "INSERT INTO tipo_medida(nom_medida, acron_medida)VALUES( :nom_medida,  :acron_medida)";
   $stm = $this->db->prepare($sql);
   $stm->bindValue(":nom_medida", $a[0],   PDO::PARAM_STR);
   $stm->bindValue(":acron_medida", $a[1], PDO::PARAM_STR);
   $insert = $stm->execute();
   if ($insert ) {
      return true;
   } else {
      return false;
   }
} 



public function verMedida(){
$sql = "SELECT * FROM  tipo_medida";
$stm = $this->db->prepare($sql);
$stm->execute();
$result = $stm->fetchAll(); 
return $result;
}


public function verMedidaPorId($ID){
   $sql = "SELECT * FROM  tipo_medida
   WHERE ID_medida = :ID ";
   $stm = $this->db->prepare($sql);
   $stm->bindValue(":ID", $ID, PDO::PARAM_INT);
   $stm->execute();
   $result = $stm->fetchAll(); 
   return $result;
   }

//mostrar datos por ID


//Actualizar datos 
public function actualizarDatosMedida($a){
   $sql =
      "UPDATE tipo_medida 
      SET nom_medida = :nom_medida , acron_medida = :acron_medida  
      WHERE ID_medida = :id ";
   $stm = $this->db->prepare($sql);
   $stm->bindValue(":id", $a[0] , PDO::PARAM_INT );
   $stm->bindValue(":nom_medida", $a[1] , PDO::PARAM_STR );
   $stm->bindValue(":acron_medida", $a[2] , PDO::PARAM_STR );
   $result = $stm->execute();
   if ($result) {
      return true;
   } else {
      return false;
   }
   header("location: ../vista/formMedida.php");
}


//eliminar registros
public function eliminarDatosMedia($a){
  // $this->ver($a, 1);
   $sql = 
      "DELETE FROM tipo_medida 
      WHERE ID_medida = :id ";
   $stm = $this->db->prepare($sql);
   $stm->bindValue(":id",$a[0], PDO::PARAM_INT );
   $result = $stm->execute();

  //  $this->ver( $a[0], 1);
   if ($result) {
      return true;
   } else {
      return false;
   }
}
//==============================
//==============================
//CNOTIFICACION
//------------------------------------------
//Ver fecha actual
public function fechaActual()
{
  //include_once 'class.conexion.php';
  //$c = new Conexion;
  $sql = "SELECT CURDATE() as fecha";
   $stm= $this->db->prepare($sql);
        $result = $stm->execute();
        $result = $stm->fetchAll();
        return $result;
  //$dat =  $c->query($sql);
  //$datos = $dat->fetch_assoc();
  //$fecha =  $datos['fecha'];
  $fecha = $result['fecha'];
  return $fecha;
}
//------------------------------------------
//------------------------------------------
//Ver fecha hora
public function horaActual()
{
  //include_once 'class.conexion.php';
  //$c = new Conexion;
  $sql = "  SELECT DATE_FORMAT(NOW( ), '%H:%i:%S' )as hora ;";
  $stm =$this->db->prepare($sql);
  $result = $stm->execute();
  $result = $stm->fetchAll();
  //$dat =  $c->query($sql);
  //$datos = $dat->fetch_assoc();
  $hora =  $result['hora'];
  return $hora;
}
//------------------------------------------
//------------------------------------------
//insertar modificacion 
public function insertModificacion(){
    //include_once 'class.conexion.php';
    //$c =  new Conexion;
    $sql = "INSERT into modific(descrip , fecha , hora ,FK_us , FK_doc , FK_modific) value ( '$this->descrip' , '$this->fecha' , '$this->hora' , '$this->FK_us' , '$this->FK_doc' , '$this->FK_modific')";
     // insert into sicloud.modific(descrip , fecha , hora ,FK_us , FK_doc , FK_modific) value ( null , '2019-07-14' , '20:30:05' , '2' , 'CC' , '1'  ),
    $stm = $this->db->prepare($sql);
    $stm->bindValue(":descrip",$this->descrip);
    $stm->bindValue(":fecha",$this->fecha);
    $stm->bindValue(":hora",$this->hora);
    $stm->bindValue(":FK_us",$this->FK_us);
    $stm->bindValue(":FK_doc",$this->FK_doc);
    $stm->bindValue(":FK_modific",$this->FK_modific);
    $insert = $stm->execute();
     //$insert = $c->query($sql);
    //echo $sql;
    if ($insert) {
        $_SESSION['message'] = "Se inserto registro de modificacion";
        $_SESSION['color'] = "success";
      } else {
        $_SESSION['message'] = "No se inserto registro de modificacion";
        $_SESSION['color'] = "success";
      }
}// fin de insersion modificacion
//------------------------------------------
//------------------------------------------
//ver join de modificaciones
public function verJoinModificacionesDB(){
    //include_once 'class.conexion.php';
    //$c =  new Conexion;


    $sql = "SELECT M.ID_modifc , M.descrip , M.fecha , M.hora , M.FK_us , M.FK_doc  ,  U.nom1 , U.ape1 ,  T_M.nom_modific , R.nom_rol  
        from tipo_modific T_M JOIN modific M on T_M.ID_t_modific =  M.FK_modific
        JOIN usuario U ON M.FK_us = U.ID_us
        JOIN rol_usuario R_U on U.ID_us = R_U.FK_us
        JOIN rol R on R_U.FK_rol = R.ID_rol_n";
         $consulta= $this->db->prepare($sql);
         $result = $consulta->execute();
         $result = $consulta->fetchAll();
         return $result;
     //insert into sicloud.modific(descrip , fecha , hora ,FK_us , FK_doc , FK_modific) value ( null , '2019-07-14' , '20:30:05' , '2' , 'CC' , '1'  ),
     //$consulta=  $c->query($sql);
     //return $consulta;   
}// fin de join modificacion
//------------------------------------------
//METODOS
//fecha actual
 public function insertEntrada(){
     //include_once 'class.conexion.php';
     //$c = new Conexion;
     $sql = "INSERT INTO orden_entrada ( fecha_ingreso , CF_rol , CF_rol_us , CF_tipo_doc) VALUES ('$this->fecha_ingreso' , '$this->CF_rol' , '$this->CF_rol_us' , '$this->CF_tipo_doc' )";
     $stm = $this->db->prepare($sql);
     $stm->bindValue(":fecha_ingreso",$this->fecha_ingreso);
     $stm->bindValue(":CF_rol",$this->CF_rol);
     $stm->bindValue(":CF_rol_us",$this->CF_rol_us);
     $stm->bindValue(":CF_tipo_doc",$this->CF_tipo_doc);
     $insert = $stm->execute();
     //$query =   $c->query($sql);       
  if($insert){   
   $_SESSION['message'] =  'Se a insrtado entrada';
   $_SESSION['color'] = 'success'; 

}else{
$_SESSION['message'] =  'Error al insertar entrada';
$_SESSION['color'] = 'danger'; 

}// fin de insrtar datos
}
//===========================================
//===========================================
//CPAGO
public function verPago(){
   //$c = new Conexion;
           $sql = "SELECT *  FROM tipo_pago";
   //$dat = $c->query($sql);
   //return $dat;
           $stm = $this->db->prepare($sql);
           $stm->execute();
           $result = $stm->fetchAll(); 
           return $result;
   }
//===========================================
//CPRODUCTO
   //query insertar producto                                     C
   public function insertarProducto($a)
   {
       $sql = "INSERT INTO producto (ID_prod, nom_prod, val_prod, stok_prod, estado_prod, CF_categoria, CF_tipo_medida)
       VALUES(?, ? ,? , ? ,? ,? ,?)";
       //      $sql = "INSERT INTO sicloud.tipo_medida(nom_medida, acron_medida)VALUES('$this->nom_medida','$this->acron_medida')";
       //$ejecucion = $db->query($sql);
       $stm = $this->db->prepare($sql);
       $stm->bindValue(1, $a[0]);
       $stm->bindValue(2, $a[1]);
       $stm->bindValue(3, $a[2]);
       $stm->bindValue(4, $a[3]);
       $stm->bindValue(5, $a[4]);
       $stm->bindValue(6, $a[5]);
       $stm->bindValue(7, $a[6]);
       $ejecucion = $stm->execute();
       if ($ejecucion) {
           $_SESSION['message'] = "Se creo producto";
           $_SESSION['color'] = "success";
           return true;
       } else {
           $_SESSION['message'] = "No se creo producto";
           $_SESSION['color'] = "danger";
           return false;
       }
       // header("location: ../CU004-crearProductos.php");
   } // fin de javaScript



   //query ver productos                                        R
   public function verProductos()
   {
       //include_once 'class.conexion.php';
       //$db = new Conexion;
       $sql = "SELECT P.ID_prod , P.img , P.nom_prod , P.val_prod , P.stok_prod , P.estado_prod , C.nom_categoria , M.nom_medida
               from producto P join categoria C on C.ID_categoria = P.CF_categoria
               join tipo_medida M on P.CF_tipo_medida = M.ID_medida
               order by  P.stok_prod  desc , P.nom_prod asc;";
       $stm = $this->db->prepare($sql);
       $stm->execute();
       $result = $stm->fetchAll();
       return $result;
       //$result = $db->query($sql);
       //return $result;
   } // fin de ver productos


   public function verProductosGrafica()
   {
       //include_once 'class.conexion.php';
       //$db = new Conexion;
       $sql = "SELECT  nom_prod , stok_prod  FROM producto order by stok_prod";
       $stm = $this->db->prepare($sql);
       $stm->execute();
       $result = $stm->fetchAll();
       return $result;
       //$result = $db->query($sql);
       //return $result;

   }

   //query ver productos                                        R
   public function verPromociones()
   {
       //include_once 'class.conexion.php';
       //$db = new Conexion;
       $sql = "SELECT P.ID_prod , P.img , P.nom_prod , P.val_prod , P.stok_prod , P.estado_prod , C.nom_categoria , M.nom_medida
           from producto P join categoria C on C.ID_categoria = P.CF_categoria
           join tipo_medida M on P.CF_tipo_medida = M.ID_medida where estado_prod = 'Promoción'
           order by P.nom_prod asc ";
       $stm = $this->db->prepare($sql);
       $stm->execute();
       $result = $stm->fetchAll();
       return $result;
       //$result = $db->query($sql);
       //return $result;
   } // fin de ver productos

   //query ver productos                                       
   public function verProductosId($id)
   {
       //include_once 'class.conexion.php';
       //$db = new Conexion;
       $sql = "SELECT P.ID_prod , P.nom_prod , P.val_prod , P.stok_prod , P.estado_prod , C.nom_categoria, T_M.nom_medida
          from producto P 
          join categoria C on P.CF_categoria = C.ID_categoria 
          join tipo_medida T_M on P.CF_tipo_medida = T_M.ID_medida 
          WHERE ID_prod = '$id' ";
       $stm = $this->db->prepare($sql);
       $stm->execute();
       $result = $stm->fetchAll();
       return $result;
       //SELECT * FROM SICLOUD.producto  WHERE ID_prod = '9808953743';
       //$result = $db->query($sql);
       //return $result;
   } // fin de ver productos por id


   public function verJoin($id)
   {
       //include_once 'class.conexion.php';
       //$db = new Conexion;
       $sql = "SELECT C.ID_categoria , C.nom_categoria, 
          P.ID_prod , P.nom_prod , P.val_prod , P.stok_prod , P.estado_prod , P.estado_prod , 
          T.ID_medida , T.nom_medida , T.acron_medida, EP.nom_empresa
          FROM categoria C 
          JOIN producto P ON C.ID_categoria = P.CF_categoria 
          JOIN tipo_medida T ON T.ID_medida = P.CF_tipo_medida
          JOIN det_producto DP ON P.ID_prod = DP.FK_prod
          JOIN empresa_provedor EP ON DP.FK_rut = EP.ID_rut 
          WHERE  P.ID_prod = '$id' LIMIT 1";
       $stm = $this->db->prepare($sql);
       $stm->execute();
       $result = $stm->fetchAll();
       return $result;
       //$sql = "SELECT C.ID_categoria , C.nom_categoria , P.ID_prod , P.nom_prod , P.val_prod , P.stok_prod , P.estado_prod , P.estado_prod, T.ID_medida , T.nom_medida , T.acron_medida FROM SICLOUD.categoria C JOIN SICLOUD.producto P on ID_categoria = CF_categoria JOIN sicloud.tipo_medida T WHERE ID_prod = '$id'   LIMIT 1 ";
       //$a =  $db->query($sql);
       //return $a;
   }



   //EDITAR PRODUCTO                                             U
   public function editarProducto($id)
   {
       //include_once 'class.conexion.php';
       //$con = new Conexion;
       $sql = "UPDATE producto SET ID_prod = '$this->ID_prod' , nom_prod = '$this->nom_prod' , val_prod = '$this->val_prod' , stok_prod = '$this->stok_prod' , estado_prod = '$this->estado_prod', CF_categoria = '$this->CF_categoria' , CF_tipo_medida = '$this->CF_tipo_medida'  WHERE ID_prod = '$id' ";
       $stm = $this->db->prepare($sql);
       $stm = $this->db->prepare($sql);
       $stm->bindValue(":ID_prod", $this->ID_prod);
       $stm->bindValue(":nom_prod", $this->nom_prod);
       $stm->bindValue(":val_prod", $this->val_prod);
       $stm->bindValue(":stok_prod", $this->stok_prod);
       $stm->bindValue(":estado_prod", $this->estado_prod);
       $stm->bindValue(":CF_coategoria", $this->CF_categoria);
       $stm->bindValue(":CF_tipo_medida", $this->CF_tipo_medida);
       $r = $stm->execute();
       //$r = $con->query($sql);
       if ($r) {
           $_SESSION['message'] = "Actualizacion de producto exitosa";
           $_SESSION['color'] = "primary";
       } else {
           $_SESSION['message'] = "Error al actualizar producto";
           $_SESSION['color'] = "danger";
       }
       header("location: ../vista/CU004-crearProductos.php?accion=verProducto");
   } // fin de editar productos



   //ELIMINAR PRODUCTO                                    D
   public function eliminarProducto($id)
   {
       //include_once 'class.conexion.php';
       //$con = new Conexion;
       $sql1 = "SET FOREIGN_KEY_CHECKS = 0 ";
       $consulta1 = $this->db->prepare($sql1);
       $res =  $consulta1->execute();

       if ($res) {
           $sql2 = " DELETE FROM producto  WHERE ID_prod = '$id'  ";
           $consulta2 = $this->db->prepare($sql2);
           $consulta2->bindValue(":id", $id);
           $res1 = $consulta2->execute();
           //$res1 = $con->query($sql2);
       }

       if ($res1) {
           $sql3 = "SET FOREIGN_KEY_CHECKS = 1";
           $consulta3 = $this->db->prepare($sql3);
           $res2 = $consulta3->execute();
           //$res2 = $con->query($sql3);
       }

       if ($res2) {
           $_SESSION['message'] = "Elimino producto";
           $_SESSION['color'] = "danger";
           return true;
       } else {
           $_SESSION['message'] = "Error al eliminar producto";
           $_SESSION['color'] = "danger";
           return false;
       }
       header("location: ../vista/CU004-crearProductos.php?accion=verProducto");
   }


   public function inserCatidadProducto($cant, $stock, $id)
   {
       // include_once 'class.conexion.php';
       //$c = new Conexion;
       $t = $stock + $cant;
       // UPDATE sicloud.producto SET stok_prod = '8' WHERE ID_prod = '0529063441';
       $sql = "UPDATE producto SET stok_prod = '$t' WHERE ID_prod = '$id' ";
       $stm = $this->db->prepare($sql);
       $stm->bindValue(":stok_prod", $this->stok_prod);
       $stm->bindValue(":ID_prod", $this->ID_prod);
       $result = $stm->execute();
       //$c->query($sql);


       if ($result) {
           $_SESSION['message'] = "Agrego existencia";
           $_SESSION['color'] = "success";
       } else {
           $_SESSION['message'] = "Error al agregar existencia";
           $_SESSION['color'] = "danger";
       }
       header("location: ../vista/CU003-ingresoProducto.php?accion=verProducto");
   }

   public function verProductosAlfa($id)
   {
       // include_once 'class.conexion.php';
       // $c = new Conexion;
       $sql = "SELECT nom_prod , stok_prod , nom_categoria  from producto sp
           join categoria sc on sp.CF_categoria = sc.ID_categoria 
           WHERE ID_categoria = $id 
           order by nom_prod asc";
       $consulta = $this->db->prepare($sql);
       $result = $consulta->execute();
       $result = $consulta->fetchAll();
       return $result;
       //$dat = $c->query($sql);
       //return $dat;
   }

   public function ConteoProductosT()
   {
       //include_once 'class.conexion.php';
       //$c = new Conexion;
       $sql = "SELECT nom_prod , sum(stok_prod) as total FROM producto GROUP BY nom_prod
       UNION
       SELECT estado_prod, sum(stok_prod) as total
       FROM producto";
       $consulta = $this->db->prepare($sql);
       $result = $consulta->execute();
       $result = $consulta->fetchAll();
       return $result;
       // $dat = $c->query($sql);
       //return $dat;
   }

   // METODO para ver los productos de una categoria 
   public function verPorCategoria($id)
   {
       $sql = "SELECT  P.ID_prod ,  P.nom_prod ,P.img , P.val_prod , P.stok_prod , P.estado_prod  , P.CF_tipo_medida , 
           C.ID_categoria , C.nom_categoria
           FROM producto P JOIN categoria C ON  P.CF_categoria = C.ID_categoria
           where C.ID_categoria = :id
           order by P.nom_prod asc";
       $consulta = $this->db->prepare($sql);
       $consulta->bindValue(':id', $id);
       $result = $consulta->execute();
       $result = $consulta->fetchAll();
       return $result;
       //$consulta = $c->query($sql);
       //return $consulta;
   }

   // METODO
   //-----------------------------------------------------------------
   //Imagenes en pantalla
   public function listaProductosImg()
   {
       //   include_once '../../plantillas/inihtmlN3';
       include_once   '../controlador/controaldorsession.php';
       include_once   'class.conexion.php';
       // $cnx = new Conexion;
       $sql = "SELECT * from producto";
       $consulta = $this->db->prepare($sql);
       $result = $consulta->execute();
       $result = $consulta->fetchAll();
       return $result; //$result = $cnx->query($sql);
   }
   //-----------------------------------------------------------------
   //-----------------------------------------------------------------
   //busquda de producto por primera letra
   public function primeraLetra($letra)
   {
       //include_once   'clases/class.conexion.php';
       //  $cnx = new Conexion;
       $sql = "SELECT * from producto where nom_prod LIKE '$letra%'";
       $consulta = $this->db->prepare($sql);
       $result = $consulta->execute();
       $result = $consulta->fetchAll();
       return $result;
       //$array = $cnx->query($sql);
       //return $array;
   }
   //----------------------------------------------------------------
   //imput bscador
   public function buscarPorNombreProducto($buscar)
   {
       //include_once   'clases/class.conexion.php';
       //$cnx = new Conexion;
       $sql = "SELECT  P.ID_prod ,  P.nom_prod ,P.img , P.val_prod , P.stok_prod , P.estado_prod  , P.CF_tipo_medida , C.ID_categoria , C.nom_categoria
           FROM producto P JOIN categoria C ON  P.CF_categoria = C.ID_categoria
           WHERE (ID_prod LIKE '%$buscar%' or nom_prod  LIKE '%$buscar%') order by nom_prod";
       $consulta = $this->db->prepare($sql);
       $result = $consulta->execute();
       $result = $consulta->fetchAll();
       return $result;
       //$array = $cnx->query($sql);
       //return $array;
   }


   public function inserTfotoUs( $foto,  $id_us){
       $sql = "UPDATE  usuario SET foto = ? where ID_us = ? ";
       $consulta = $this->db->prepare($sql);
       $consulta->bindValue( 1 ,  $foto, PDO::PARAM_STR );
       $consulta->bindValue( 2 ,  $id_us, PDO::PARAM_STR );
       $consulta = $consulta->execute();
       return true;
   }

   public function inserTfotoProd( $foto,  $ID_prod){
      $sql = "UPDATE  producto SET img = ? where ID_prod = ? ";
      $consulta = $this->db->prepare($sql);
      $consulta->bindValue( 1 ,  $foto, PDO::PARAM_STR );
      $consulta->bindValue( 2 ,  $ID_prod, PDO::PARAM_STR );
      $consulta = $consulta->execute();
      return true;
  }




   public function verProductosIdCarrito($id)
   {
       //include_once 'class.conexion.php';
       //$db = new Conexion;
       $sql = "SELECT P.img , P.ID_prod , P.nom_prod , P.stok_prod , P.descript , P.val_prod , P.estado_prod , C.nom_categoria, T_M.nom_medida
               from producto P join categoria C on P.CF_categoria = C.ID_categoria 
               join tipo_medida T_M on P.CF_tipo_medida = T_M.ID_medida WHERE P.ID_prod = '$id' ";
       $consulta = $this->db->prepare($sql);
       $result = $consulta->execute();
       $result = $consulta->fetchAll();
       return $result;
       //            SELECT * FROM SICLOUD.producto  WHERE ID_prod = '9808953743';
       //$result = $db->query($sql);
       //return $result;
   }

   //=================================================
   //=================================================
   //CPROVEDOR
   public function verProveedor(){
      $sql = "SELECT * FROM empresa_provedor";
      $c = $this->db->prepare($sql);
      $c->execute();
      $r = $c->fetchAll();
      return $r;
  }
//===================================================
//===================================================
//CPUNTOS
public function insertPuntos( $FK_us , $FK_tipo_doc)
{
  //include_once 'class.conexion.php';
  //$c = new Conexion;
  $sql = "INSERT INTO puntos ( puntos , fecha , FK_us , FK_tipo_doc)VALUE(  '$this->puntos' , '$this->fecha' , '$FK_us' , '$FK_tipo_doc')";
  $stm = $this->db->prepare($sql);
  $stm->bindValue(":puntos",$this->puntos);
  $stm->bindValue(":fecha",$this->fecha);
  
  // $c->query($sql);


//   INSERT INTO puntos ( puntos , fecha , FK_us , FK_tipo_doc)VALUE(  '1' , '1990-08-15' , '1' , 'CC'   );

}// fin de insert punto
//=============================================
//============================================
//CROL USUARIO

  //METODOS





  public function insertrRolUs($a){
      echo 'array de rol'.'<pre>';   print_r($a);    echo '</pre>';
           $sql = "INSERT INTO rol_usuario(FK_rol,FK_us,FK_tipo_doc,fecha_asignacion,estado)
            VALUES (?,?,?,?,?)";

            // $STH->bindParam(':12', $_POST['napomena'], PDO::PARAM_STR);
            //PDO::PARAM_INT
            //PDO::PARAM_STR
            $stm = $this->db->prepare($sql);
            foreach( $a as $i => $d ){
          $stm->bindValue( 1, $d[10], PDO::PARAM_INT );
          $stm->bindValue( 2 ,$d[0] , PDO::PARAM_STR );
          $stm->bindValue( 3 ,$d[9] , PDO::PARAM_STR  );
          $stm->bindValue( 4 ,$d[11] , PDO::PARAM_STR );
          $stm->bindValue( 5 ,$d[12] ,  PDO::PARAM_STR );
         }
         $bool = $stm->execute();
         if($bool){
            return true;
         }else{
            return $a;
        }
      }
   

//========================================
//=======================================
//CROL

// metodo ver roles                            R
public function verRol(){
   $sql = "SELECT * FROM rol";
   $consulta = $this->db->prepare($sql);
   $consulta->execute();
   $result = $consulta->fetchAll();
   return $result;
}// fin de lectura rol



// metodo ver rol por id                   R
public function verRolId($id){
//include_once 'class.conexion.php';
  // $con = new Conexion;
   $sql = "SELECT * FROM rol WHERE ID_rol_n = $id";
   $consulta = $this->db->prepare($sql);
   $consulta->execute();
   $result = $consulta->fetchAll();
   return $result;
   //$r = $con->query($sql);
   //return $r;
}// fin de ver rol por ID


// Actualizar datos                       U
public function insertUpdateRol($id){ 
//include_once 'class.conexion.php';

  //  $con = new Conexion;
    $sql = "UPDATE rol SET nom_rol = '$this->nom_rol'  WHERE ID_rol_n = $id";
    $stm = $this->db->prepare($sql);
    $stm->bindValue(":nom_rol",$this->nom_rol);
    $stm->bindValue(":ID_rol_n",$this->ID_rol);
    $ri = $stm->execute();
    //$ri = $con->query($sql);
    if($ri){ echo "<script>alert('Se actualizo Rol exitioso');</script>"; echo "<script>window.location.replace('../vista/formRol.php');</script>"; }else{  echo "<script>alert('Error al actualizar rol ');</script>"; echo "<script>window.location.replace('../vista/formRol.php');</script>"; }
  
 if($ri){
     $_SESSION['message'] = "Se actualizo el rol";
     $_SESSION['color'] = "primary";

 }
 header("location: ../forms/formRol.php ");
}// fin de insert update rol       


// Borrar rol                               D
public function eliminarRol($id){ 
//include_once 'class.conexion.php';
//$con = new Conexion;
$sql1 = "SET FOREIGN_KEY_CHECKS = 0 ";
$stm = $this->db->prepare($sql1);
$res = $stm->execute();
//     $res =   $con->query($sql1);

if($res){
    $sql2 = " DELETE FROM rol WHERE ID_rol_n = $id  ";
    $stm = $this->db->prepare($sql2);
    $res1 = $stm->execute();
    //$res1 = $con->query($sql2); 
}

if($res1){
    $sql3 = "SET FOREIGN_KEY_CHECKS = 1";
    $stm = $this->db->prepare($sql3);
    $res2 = $stm->execute();
    // $res2 = $con->query($sql3);
}


if($res2){ echo "<script>alert('Se elimino Rol exitioso');</script>"; echo "<script>window.location.replace('../vista/formRol.php');</script>"; }else{  echo "<script>alert('Error al eliminar rol ');</script>"; echo "<script>window.location.replace('../vista/formRol.php');</script>"; }
if($res2){
    $_SESSION['message'] = "Se elimino el rol";
    $_SESSION['color'] = "danger";

}else{}
header("location:../vista/formRol.php ");
}
//======================================
//======================================
//CTELEFONO
public function insertTelefonoUsuario(){
   //include_once '../clases/class.conexion.php';
   //$c = new Conexion;
   $sql = "INSERT INTO telefono ( tel,CF_us)values('$this->tel', '$this->CF_us ')";
   $stm = $this->db->prepare($sql);
   $stm -> bindValue (":tel",$this->tel);
   $stm -> bindValue (":CF_us",$this->CF_us);
   $insert = $stm->execute();
   //echo $sql;
   //$insert = $c->query($sql);
      
  if($insert){   
   $_SESSION['message'] =  'A registrado telefono para su cuenta, si desea ingresa otro telefono, favor digite';
   $_SESSION['color'] = 'success'; 


   }else{
       $_SESSION['message'] =  'No registro telefono';
       $_SESSION['color'] = 'danger'; 
       }

       header("location: ../vista/formTelefono.php ");
}// fin de insert telefono usuario

// muestra los datos por id------------------------------------------------------------
public function verTelefonosUsuarioPorID($id){
//include_once '../clases/class.conexion.php';
//$c = new Conexion;
   $sql = "SELECT U.ID_us , U.nom1 , U.ape1 , R.nom_rol , T.tel
       from rol R join rol_usuario R_U on R.ID_rol_n = R_U.FK_rol
       join usuario U on R_U.FK_us = U.ID_us
       join telefono T on  U.ID_us = T.CF_us
       where CF_us = :id";
       $consulta= $this->db->prepare($sql);
       $consulta->bindValue(':id', $id);
       $result = $consulta->execute();
       $result = $consulta->fetchAll();
       return $result;
//echo $sql;
//$result= $c->query($sql);
//return $result;
}
// Fin de mostrar datos por id
//-----------------------------------------------------------------------------------------


// muestra los datos por id------------------------------------------------------------
public function verTelefonosUsuario(){
   //include_once 'class.conexion.php';
   //$c = new Conexion;
   $sql = "SELECT U.ID_us , U.nom1 , U.ape1 , R.nom_rol , T.tel
   from rol R join rol_usuario R_U on R.ID_rol_n = R_U.FK_rol
   join usuario U on R_U.FK_us = U.ID_us
   join telefono T on  U.ID_us = T.CF_us";
   $consulta= $this->db->prepare($sql);
   $result = $consulta->execute();
   $result = $consulta->fetchAll();
   return $result;
   //echo $sql;
   //$result= $c->query($sql);
   //return $result;
   }
   // Fin de mostrar datos por id
   //------------------------------------------------------------------------------------------


// muestra los datos por rol------------------------------------------------------------
public function verTelefonosUsuarioRol($rol){
   //include_once '../clases/class.conexion.php';
   //$c = new Conexion;
   $sql = "SELECT U.ID_us , U.nom1 , U.ape1 , R.nom_rol , T.tel
           from rol R join rol_usuario R_U on R.ID_rol_n = R_U.FK_rol
           join usuario U on R_U.FK_us = U.ID_us
           join telefono T on  U.ID_us = T.CF_us
           where R_U.FK_rol = :rol";

   $consulta= $this->db->prepare($sql);
   $consulta -> bindValue (":rol",$rol);
   $result = $consulta->execute();
   $result = $consulta->fetchAll();
   return $result;
   //echo $sql;
   //$result= $c->query($sql);
   //return $result;
   }
   // Fin de mostrar telefono rol
   //------------------------------------------------------------------------------------------

// muestra telefonos de empresa------------------------------------------------------------
public function verTelefonosEmpresa(){
   //include_once '../clases/class.conexion.php';
   //$c = new Conexion;
   $sql = "SELECT E_P.ID_rut , E_P.nom_empresa , T.tel
   from empresa_provedor  E_P join telefono T on E_P.ID_rut = T.CF_rut;";
   $consulta= $this->db->prepare($sql);
   $result = $consulta->execute();
   $result = $consulta->fetchAll();
   return $result;
   //echo $sql;
   //$result= $c->query($sql);
   //return $result;
   }
   // Fin de ver telefonos empresas
   //------------------------------------------------------------------------------------------
 
   public function verTelefonos(){
   $us = false; $em = false;

   //--- EVENTOS DE FORMULARIO----------------------------------------------------------------------

   // Filtro por id
   if ((isset($_GET['accion'])) &&  ($_GET['accion'] == 'bId')) {
       if ($_GET['documento'] > 0) {
           $us = True; $em = false;
           $id = $_GET['documento'];
           $objModTel = new ControllerDocForm();
           $datos = $objModTel->verTelefonosUsuarioPorID($id);
           //$datos = $telefono->selectIdUsuario($id);
           $_SESSION['message'] = "Filtro por id";
           $_SESSION['color'] = "info";
       } else {
           $_SESSION['message'] = "No ha digitado el ID del usuario";
           $_SESSION['color'] = "danger";
       } // fin de consulta por id
   } // fin de isset accion



   // Filtro por entidad
   if ((isset($_POST['accion'])) &&  ($_POST['accion'] == 'entidad')) {
       $us = true; $em = false;
       if ((isset($_POST['entidad']))) {
           if ($_POST['entidad'] == "a") {
               $entidad = 1;
               $objModTel = new ControllerDocForm();
               $datos = $objModTel->verTelefonosUsuario();
               $_SESSION['message'] = "Filtro por telefono de usuario";
               $_SESSION['color'] = "info";
           } else {
               $us = false; $em = true;
               $entidad = 0;
               $objModTel = new ControllerDocForm();
               $datos = $objModTel->verTelefonosEmpresa();
               $_SESSION['message'] = "Filtro por telefono de empresa";
               $_SESSION['color'] = "info";
           }
       } // fin consulta por entidad
   } // fin isset accion


   // Filtro por rol de usuario
   if (isset($_POST['consultaRol'])) {
       $us = True; $em = false;
       $rol = $_POST['rol'];
       $objModTel = new ControllerDocForm();
       $datos= $objModTel->verTelefonosUsuarioRol($rol);
   } // fin de isset consulta rol


   //if (isset($_SESSION['message'])) {  */ 
 }


 public function eliminarTelefono($idg){
  
   $sql1 = "SET FOREIGN_KEY_CHECKS = 0 ";
   $consulta2 = $this->db->prepare($sql1);
        $rest1 =  $consulta2->execute();   
   if ($rest1) {
      $sql2 = "DELETE FROM telefono WHERE CF_us =:CF_us ";   
      $consulta3 = $this->db->prepare($sql2);
      $consulta3->bindValue(":CF_us",$idg);
       $res2 = $consulta3->execute();  
   }   
   if ($res2) {
      $sql3 = "SET FOREIGN_KEY_CHECKS = 1";
      $consulta4 = $this->db->prepare($sql3);
       $res3 = $consulta4->execute();
   }
   if ($res3) {
       

      $_SESSION['message'] = $_SESSION['usuario']['nom1'] . 'Elimino telefono';
      $_SESSION['color'] = 'success';
      return true;
   } else {

      $_SESSION['message'] = 'No elimino telefono';
      $_SESSION['color'] = 'danger';
      return false;
   }
}

//CTIENDA
public function listaProductos(){
   include_once   '../controlador/controladorsession.php';
   //$cnx=new Conexion;
   $sql = "SELECT * from productos";
   $consulta= $this->db->prepare($sql);
       $result = $consulta->execute();
       $result = $consulta->fetchAll();
       return $result;
   //$result= $cnx->query($sql);


   // $result->fetch_array();
  //  while( $row = $result)


   foreach ($result as $row) {
       $lista[]=$row;
   }
   return $lista;
}
//===================================================




















} //Fin Clase Usuario



//$objCont = new Usuario();
//  echo '<pre>';  print_r($objCont->selectUsuarioRol(1)); echo '</pre>';
        
          # Pasar en el mismo orden de los ?


     /* if ($result = true) {
          echo '<script>alert("inserto datos");</script>';
         // $_SESSION['message'] = "Actualizacion exitosa";
         // $_SESSION['color'] = "primary";
      } else {
          echo '<script>alert("no actualizo");</script>';
         // $_SESSION['message'] = "Fallo actualizacion";
         // $_SESSION['color'] = "danger";
      }
      header("location: ../forms/FormEmpresa.php"); */












  /* public function deleteUserModel($id_get){
      $sql = "DELETE FROM sicloud.usuario WHERE ID_us = :id";
      $consulta = $this->db->prepare($sql);
      if($consulta->execute()){
         echo 'Usuario eliminado correctamente';
      } else {
         echo 'El usuario no pudo ser eliminado';
      }
   } */



 /* public function ver($dato, $sale=0, $float= false, $email=''){
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
  */

   /* public function VerifyLogin($nombre, $password){
      $this->nombre = $nombre;
      $this->pass = $password;
      $this->db = self::conexionPDO();
      $infousuario = $this->SearchUsuario();
      foreach($infousuario as $usuario){
          if(password_verify($password, $usuario->pass)){
          $_SESSION['nombre'] = $usuario->nombre;
          $_SESSION['pass'] = $usuario->pass;
          $_SESSION['rol'] = $usuario->rol;
          } else {
              echo 'Contraseña Incorrecta';
          }
      }      
   } 

   public function SearchUsuario(){
      $sql = "SELECT * FROM usuario WHERE nombre = '$this->nombre'";
      $consulta = $this->db->prepare($sql);
      $consulta->execute();
      $objconsulta = $consulta->fetchAll(PDO::FETCH_OBJ);
      return $objconsulta;
   }
} */

   
   
   /*
   public function verUsuarios(){
      $sql = "SELECT distinct U.FK_tipo_doc, U.ID_us, U.nom1, U.nom2, U.ape1, U.ape2, U.pass, U.foto, U.correo, 
         R.nom_rol,  R.nom_rol,
         R_U.estado
         FROM sicloud.usuario U 
         JOIN  rol_usuario R_U ON R_U.FK_us = U.ID_us
         JOIN sicloud.rol  R ON R_U.FK_rol = R.ID_rol_n";
      $consulta = $this->db->prepare($sql);
      $consulta->execute();
      $result = $consulta->fetchAll();
      return $result;  
    } // fin de busqueda por ID
/*
   public function insertUpdateUsuario($idg){
      $sql1 = "SET FOREIGN_KEY_CHECKS = 0 ";
      $consulta1 = $this->db->prepare($sql1);  
      $res = $consulta1->execute();
      if($res){
         $sql2 = "UPDATE sicloud.usuario SET ID_us = '$this->ID_us',nom1 = '$this->nom1' ,  nom2 = '$this->nom2' ,ape1 = '$this->ape1' , ape2 = '$this->ape2' , fecha = '$this->fecha' , pass = '$this->pass' , foto = '$this->foto' , correo = '$this->correo' , FK_tipo_doc = '$this->FK_tipo_doc' WHERE  ID_us = $idg   ";

         $res1 = $this->db->query($sql2);
      }   
      if ($res1) {
         $sql3 = "SET FOREIGN_KEY_CHECKS = 1";
         $res2 = $this->db->query($sql3);
      }
      if ($res2) {
         $_SESSION['message'] = 'Se actualizo cuenta de usuario';
         $_SESSION['color'] = 'primary';
      } else {
         $_SESSION['message'] = 'Error no se cuenta de usuario';
         $_SESSION['color'] = 'danger';
      }

      }



      //Crea la consulta y la almasena en la varible $sql

         

   
  /*
  //---------------------------------------------------------------

  //fecha actual
  public function fechaActual(){
       $sql = "SELECT CURDATE() as fecha";
       $dat =  $this->db->query($sql);
       while ($row = $dat->fetch_assoc()) {
          $fecha = $row['fecha'];
       }
   
       return $fecha;
  }


  // Actualzacion de datos por rol usuario---------------------------------------------------------
  public function insertUpdateUsuarioCliente($idg){
     include_once 'session/sessiones.php';
     $sql1 = "SET FOREIGN_KEY_CHECKS = 0 ";
     $res =   $this->db->query($sql1);   
     if ($res) {
        $sql2 = "UPDATE sicloud.usuario SET  nom1 = '$this->nom1' ,  nom2 = '$this->nom2' ,ape1 = '$this->ape1' , ape2 = '$this->ape2' , fecha = '$this->fecha'   , correo = '$this->correo'  WHERE  ID_us = '$idg' ";
        $res1 = $this->db->query($sql2);
     }   
     if ($res1) {
        $sql3 = "SET FOREIGN_KEY_CHECKS = 1";
        $res2 = $this->db->query($sql3);
     }
     if ($res2) {
        $_SESSION['message'] = $_SESSION['usuario']['nom1'] . ' Actualizo datos';
        $_SESSION['color'] = 'success';
     } else {
        $_SESSION['message'] = 'Error al actualizar datos';
        $_SESSION['color'] = 'danger';
     }
  } //-------------------------------------------------------------------------------------------------------



  // actualizar datos de usario por administrador------------------------------------------------------------
  public function insertUpdateUsuario($idg){
     $sql1 = "SET FOREIGN_KEY_CHECKS = 0 ";
     $res =   $this->db->query($sql1);   
     if ($res) {
        $sql2 = "UPDATE sicloud.usuario SET ID_us = '$this->ID_us',nom1 = '$this->nom1' ,  nom2 = '$this->nom2' ,ape1 = '$this->ape1' , ape2 = '$this->ape2' , fecha = '$this->fecha' , pass = '$this->pass' , foto = '$this->foto' , correo = '$this->correo' , FK_tipo_doc = '$this->FK_tipo_doc' WHERE  ID_us = $idg   ";
        $res1 = $this->db->query($sql2);
     }   
     if ($res1) {
        $sql3 = "SET FOREIGN_KEY_CHECKS = 1";
        $res2 = $this->db->query($sql3);
     }
     if ($res2) {
        $_SESSION['message'] = 'Se actualizo cuenta de usuario';
        $_SESSION['color'] = 'primary';
     } else {
        $_SESSION['message'] = 'Error no se cuenta de usuario';
        $_SESSION['color'] = 'danger';
     }
  } // fin de update usuario----------------------------------------------------------------------------------


  // insersion a usuario------------------------------------------------------------------------------
  public function insertUsuario(){
    //Crea la consulta y la almasena en la varible $sql
    $sql = "INSERT INTO sicloud.usuario (ID_us,nom1,nom2,ape1,ape2,fecha,pass,foto,correo,FK_tipo_doc)
        VALUES('$this->ID_us ','$this->nom1','$this->nom2','$this->ape1','$this->ape2',' $this->fecha ','$this->pass','$this->foto','$this->correo','$this->FK_tipo_doc')";
    $this->db->query($sql);
 

  } //fin de la funcion insert------------------------------------------------------------------------


  // Muestra los datos en la tabla usuario
  public function selectUsuario(){
    $sql = "SELECT U.FK_tipo_doc, U.ID_us, U.nom1, U.nom2, U.ape1, U.ape2, U.fecha, U.pass, U.foto, U.correo, R_U.estado 
        FROM sicloud.usuario U 
        JOIN  rol_usuario R_U ON R_U.FK_us = ID_us 
        ORDER BY R_U.estado desc ";
    $result = $this->db->query($sql);
    return $result;
  } // fin de muestra los datos de la tabla usuario



  // Incio de select usuario
  static function selectUsuarios($id){
    include_once 'class.conexion.php';
    $c = new Conexion();
    $sql = "SELECT U.FK_tipo_doc, U.ID_us, U.nom1, U.nom2, U.ape1, U.ape2,  U.pass, U.foto, U.correo,  U.fecha, 
       R.nom_rol, R_U.estado, R.ID_rol_n
       FROM sicloud.usuario U 
       JOIN  rol_usuario R_U ON R_U.FK_us = U.ID_us
       JOIN sicloud.rol  R ON R_U.FK_rol = R.ID_rol_n 
       WHERE ID_us = $id ";
    $result = $c->query($sql);
    return $result;
  } // Fin de select usuario

  //ver usuario por un registro id
  static function verUsarioId($idg){
    include_once 'class.conexion.php';
    $db_usuario = new Conexion();
    $sql = "SELECT U.FK_tipo_doc, U.ID_us, U.nom1, U.nom2, U.ape1, U.ape2, R.nom_rol, U.pass, U.foto, U.correo, 
       R_U.estado
       FROM sicloud.usuario U 
       JOIN  rol_usuario R_U ON R_U.FK_us = U.ID_us
       JOIN sicloud.rol  R ON R_U.FK_rol = R.ID_rol_n 
       WHERE ID_us = '$idg' ";
    $result = $db_usuario->query($sql);
    return $result;
  } // fin de busqueda por ID






  public function verUsuarios(){
    $sql = "SELECT distinct U.FK_tipo_doc, U.ID_us, U.nom1, U.nom2, U.ape1, U.ape2, U.pass, U.foto, U.correo, 
       R.nom_rol,  R.nom_rol,
       R_U.estado
       FROM sicloud.usuario U 
       JOIN  rol_usuario R_U ON R_U.FK_us = U.ID_us
       JOIN sicloud.rol  R ON R_U.FK_rol = R.ID_rol_n";
    $result = $this->db->query($sql);
    return $result;
  } // fin de busqueda por ID


  //Busqueda por estado pendiente
  public function selectUsuariosPendientes($est){
    $sql = "SELECT U.FK_tipo_doc, U.ID_us, U.nom1, U.nom2, U.ape1, U.ape2, U.pass, U.foto, U.correo,  
       R.nom_rol, 
       R_U.estado
       FROM sicloud.usuario U 
       JOIN  rol_usuario R_U ON R_U.FK_us = U.ID_us
       JOIN sicloud.rol  R ON R_U.FK_rol = R.ID_rol_n 
       WHERE R_U.estado = '$est' ";
    $result = $this->db->query($sql);
    return $result;
  } //Busqueda por estado pendiente


  //aprobar solicitud
  public function activarCuenta($id){
    $sql = " UPDATE sicloud.rol_usuario 
       SET rol_usuario.estado = 1 
       WHERE rol_usuario.FK_us = $id ";
    $insert = $this->db->query($sql);
    // if($insert){ echo "<script>alert('Se desactivo cuenta');</script>";   echo "<script>window.location.replace('../CU009-controlUsuarios.php');</script>"; }else{ echo "<script>alert(no se ha activado');</script>"; echo "<script>window.location.remplace('../CU009-controlUsuarios.php');</script>"; } 
    if ($insert) {
      $_SESSION['message'] = "Activo cuenta de usuario";
      $_SESSION['color'] = "success";
    } else {
      $_SESSION['message'] = "Error al activar cuenta";
      $_SESSION['color'] = "success";
    }
    header("location: ../CU009-controlUsuarios.php ");
  } // fin de aprbar solicitud

  //desactivar cuenta
  public function desactivarCuenta($id){
     $sql = " UPDATE sicloud.rol_usuario 
        SET rol_usuario.estado = 0 
        WHERE rol_usuario.FK_us = $id ";
     $conexion = $this->db->query($sql);
     if ($conexion) {
        $_SESSION['message'] = "Desactivo cuenta de usuario";
        $_SESSION['color'] = "danger";
     } else {
        $_SESSION['message'] = "Error al descativar cuenta";
        $_SESSION['color'] = "danger";
     }
     header("location: ../CU009-controlUsuarios.php ");
  } // fin de desactibar cuenta


  //Compara contraseña tabla usuario--------------------------------------------------------------------------
  public function DocPass($ID_us, $pass, $doc){
     $sql = " SELECT U.* , TD.ID_acronimo , 
        RU.estado , 
        R.ID_rol_n , R.nom_rol 
        FROM tipo_doc TD 
        JOIN usuario U ON TD.ID_acronimo = U.FK_tipo_doc 
        JOIN rol_usuario RU ON U.ID_us = RU.FK_us 
        JOIN rol R ON FK_rol = R.ID_rol_n  
        WHERE U.ID_us =  '$ID_us' 
        AND U.pass = '$pass' 
        AND TD.ID_acronimo = '$doc' ";
     $result = $this->db->query($sql);
     return $result;
  } // fin de comprobar contraseña----------------------------------------------------------------------------------


  //---------------------------------------------------------------------------------------------------------------
  //Cambio de contraseña
  public function cambioPass($id,  $contraseñaNueva){
     $sql = "UPDATE usuario 
        SET pass = '$contraseñaNueva' 
        WHERE ID_us = '$id'";
     // echo $sql;
     $r = $this->db->query($sql);
     if ($r) {
        $_SESSION['message'] = "Cambio contraseña";
        $_SESSION['color'] = "success";
     } else {
        $_SESSION['message'] = "Error al cambiar contraseña";
        $_SESSION['color'] = "danger";
     }
    //-------------------------------------------------------------------------
  }


  public function validarPass($id, $pass){
    $sql = "SELECT * FROM usuario 
       WHERE ID_us = '$id' 
       AND pass = '$pass'";
    $i = $this->db->query($sql);
    return $i;
  }


  // insertar foto
  public function inserTfoto($destino, $id){
     $sql = "UPDATE  usuario 
        SET foto = ('$destino')
        WHERE ID_us = '$id'";
     $e = $this->db->query($sql);
     ($e) ??  header("location: ../CU002-registrodeUsuario.php ");

  }


  // filtro por rol
  public function  selectUsuarioRol($r){
     $sql = "SELECT U.FK_tipo_doc, U.ID_us, U.nom1, U.nom2, U.ape1, U.ape2, U.pass, U.foto, U.correo,
        R.nom_rol,  
        R_U.estado
        FROM sicloud.usuario U 
        JOIN  rol_usuario R_U ON R_U.FK_us = U.ID_us
        JOIN sicloud.rol  R ON R_U.FK_rol = R.ID_rol_n
        WHERE R.ID_rol_n  = '$r'
        ORDER BY u.nom1 asc";
     $resultConsulta = $this->db->query($sql);
     // consulta para mensaje de rol 
     if ($resultConsulta) {   
        $sql2 = "SELECT nom_rol FROM rol 
           WHERE ID_rol_n = $r 
           LIMIT 1";
        $datos  = $this->db->query($sql2);
        $row = $datos->fetch_assoc();
        $rol = $row['nom_rol'];
        $_SESSION['message'] = "Filtro por rol:  " . $rol;
        $_SESSION['color'] = "info";
        return $resultConsulta;
     }
  } // fin de metodo select usaurio



  // ver puntos usuario
  static function verPuntosUs(){
     include_once 'class.conexion.php';
     $c = new Conexion;
     $sql = "SELECT P.id_puntos, P.puntos, P.fecha , 
        U.nom1 , U.nom2 , U.ape1
        FROM puntos P 
        JOIN usuario U ON  P.FK_us =  U.ID_us
        ORDER BY U.nom1 asc";
     $con = $c->query($sql);   
     return $con;
  }

  static function verPuntosYusuario($id){
     include_once 'class.conexion.php';
     $c = new Conexion;
     $sql = " SELECT U.ID_us  , U.nom1 , U.nom2 , U.ape1 , U.ape2 , U.fecha , U.pass , U.foto , U.correo , 
     TD.nom_doc , 
     RU.estado , 
     R.ID_rol_n , R.nom_rol , 
     P.puntos
        FROM tipo_doc TD 
        JOIN usuario U ON TD.ID_acronimo = U.FK_tipo_doc 
        JOIN rol_usuario RU ON U.ID_us = RU.FK_us 
        JOIN rol R ON FK_rol = R.ID_rol_n 
        JOIN puntos P ON U.ID_us = P.FK_us
        WHERE U.ID_us = '$id'";   
     $con = $c->query($sql);
     return $con;
  }



  public function conteoUsuariosActivos(){
     $sql = "SELECT count(*) AS usuariosActivos 
        FROM usuario  U JOIN rol_usuario RU ON RU.FK_us = U.ID_us
        WHERE RU.estado = 1";
     $datos = $this->db->query($sql);
     while ($row = $datos->fetch_assoc()) {
        $con = $row['usuariosActivos'];
     }
     return $con;
  }


  public function conteoUsuariosInactivos(){
     $sql = "SELECT count(*) AS usuariosActivos 
        FROM usuario  U 
        JOIN rol_usuario RU ON RU.FK_us = U.ID_us
        WHERE RU.estado = 0";
     $datos = $this->db->query($sql);
     while ($row = $datos->fetch_assoc()) {
       $con = $row['usuariosActivos'];
     }
     return $con;
  }


  public function conteoUsuariosTotal(){
     $c = new Conexion;
     $sql = "SELECT count(*) AS usuariosActivos 
        FROM usuario  U 
        JOIN rol_usuario RU ON RU.FK_us = U.ID_us
     ";
     $datos = $this->db->query($sql);
     while ($row = $datos->fetch_assoc()) {
       $con = $row['usuariosActivos'];
       }
     return $con;
  }
}// fin de clase usaurio



*/



//$objMod->insertUsuario( 1030 ,' nom1' ,'nom2','ape1','ape2','10-04-2020','pass','foto','correo','CC'  );

?> 