<?php
define("PATH_ROOT", "../");
include_once PATH_ROOT."lib/cliente_cobrodigital.php";

$idComercio="CI366779";
$sid="MeAOO0d8tpk87Ud3AG0mZO7WCIP76GuKfU48UMVCuLO66aQGa0Iw3R6cDVs";
$identificador = "NRO CLIENTE";
$campo_a_buscar = "10";
$vencimiento_1='20171230';
$vencimiento_2='20171222';
//$vencimiento_3='25/01/2018';
$importe_1="18.4";
$importe_2="20.51";
//$importe_3="31.80";
$concepto="Couta mensual";
$modelo="init";
try {
    $cobro_digital=new Cliente_cobrodigital($idComercio,$sid);
//    $cobro_digital->set_method("nusoap");
    $cobro_digital->set_method("POST");
//    $cobro_digital->set_method("GET");
    if(!($nro_boleta=$cobro_digital->generar_boleta($identificador,$campo_a_buscar,$concepto,$vencimiento_1,$importe_1,$modelo,$vencimiento_2,$importe_2,false,false)))
        print_r($cobro_digital->obtener_log());
    else
        print_r($nro_boleta);
} catch (Exception $ex) {
    print_r($ex->getMessage());
}




