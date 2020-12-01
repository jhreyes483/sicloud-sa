<?php
class ControllerFactura{
public $objMod, $session;
public function __construct(){
    
    date_default_timezone_set("America/Bogota");
    include_once 'controladorsession.php';
    include_once '../modelo/class.sql.php';
    $this->objMod      = SQL::ningunDato();
    $this->objSession  = Session::ningunDato();
    $this->session     = $this->objSession->desencriptaSesion();
}


public function facturar($a , $tipo = 1){
switch ($tipo) {
    case 1:
        $total = 0;
        foreach( $a as $i => $d){
            $SubTot = ($d['CANTIDAD'] *  $d['PRECIO']);
            $total += $SubTot;
        }

$iva = ($total * 0.19);
$fecha = date('Y-m-d');
        $aF =[
            $total,
            $fecha,
            'venta en linea',
            $iva,
            5
        ];

       $id_factura = $this->objMod->facturar($aF);

        foreach( $a as $i => $d ){
            $aP= [
                $id_factura,
                $d['ID'],
                $d['PRECIO'],
                $d['CANTIDAD'],
                $this->session['usuario']['ID_us'],
                $this->session['usuario']['FK_tipo_doc'],
            ];
         $r =   $this->objMod->insertaProductosFactura($aP);
         if($r){
             $_SESSION['message']= "Facturo el producto de manera exitosa";
             $_SESSION['color'] = "success";
         }else{
            $_SESSION['message']= "Error al facuturar";
            $_SESSION['color'] = "danger";
         }
        }
        break;
    
    default:
        # code...
        break;
}






}


}










?>