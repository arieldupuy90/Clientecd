<?php
define("PATH_ROOT", "../");
include_once PATH_ROOT."lib/cliente_cobrodigital.php";
$idComercio='FL662997';
$sid='ABZ0ya68K791phuu76gQ5L662J6F2Y4j7zqE2Jxa3Mvd22TWNn4iip6L9yq';
$desde='20161201';
$hasta='20161210';
$filtros=array('tipo'=>'egresos');
try {
    $cobro_digital=new cliente_cobrodigital($idComercio,$sid);
    $cobro_digital->set_method("NUSOAP");
    //    $cobro_digital->set_method("POST");
    //    $cobro_digital->set_method("GET"  );
    $cobro_digital->consultar_transacciones($desde, $hasta, $filtros);
        if($cobro_digital->obtener_log()!="")
            print_r($cobro_digital->obtener_log());
    else{
            print_r($cobro_digital->obtener_datos());
    }
} catch (Exception $ex) {
    print_r($ex->getMessage());
}
