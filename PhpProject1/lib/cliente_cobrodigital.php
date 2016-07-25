<?php

class Cliente_cobrodigital {
    protected $resultado =array();
    protected $metodo_web_service=false;
    protected $method = "POST";
    protected $array_a_enviar;
    protected $idComercio;
    protected $sid;
    public function __construct($idComercio=false, $sid=false) {
        if(!$idComercio){
            throw new Exception("No definio idComercio");
        }
        if(!$sid){
            throw new Exception("No definio sid");
        }
        $this->idComercio=$idComercio;
        $this->sid=$sid;
        
        return $this;
    }
    //Funciones que dan una interfaz al usuario///
    public function crear_pagador($nuevo_pagador /* nuevo_pagador debe ser un array asociativo*/){
        $this->metodo_web_service="crear_pagador";
        $this->array_a_enviar['pagador']=$nuevo_pagador;
        $this->ejecutar();
        return $this->obtener_resultado();
    }
    public function editar_pagador($identificador, $campo_a_buscar,$nuevo_pagador /* nuevo_pagador debe ser un array asociativo*/){
        $this->metodo_web_service="editar_pagador";
        $this->array_a_enviar['identificador']=$identificador;
        $this->array_a_enviar['buscar']=$campo_a_buscar;
        $this->array_a_enviar['pagador']=$nuevo_pagador;
        $this->ejecutar();
        return $this->obtener_resultado();
    }
    public function existe_pagador($identificador,$dato_a_buscar){
        $this->metodo_web_service="existe_pagador";
        $this->array_a_enviar["identificador"]=$identificador;
        $this->array_a_enviar["buscar"]=$dato_a_buscar;
        $this->ejecutar();
        return $this->obtener_resultado();
    }
    public function generar_boleta($identificador,$campo_a_buscar, $concepto,$fecha_1, $importe_1,$modelo=false,$fecha_2=false,$importe_2=false,$fecha_3=false,$importe_3=false){
        
        $this->metodo_web_service="generar_boleta";
        $this->array_a_enviar['identificador']=$identificador;
        $this->array_a_enviar['buscar']=$campo_a_buscar;
        if($modelo!=false)
            $this->array_a_enviar['modelo']=$modelo;
        $this->array_a_enviar['fechas_vencimiento'][]=$fecha_1;
        if($fecha_2!=false)
            $this->array_a_enviar['fechas_vencimiento'][]=$fecha_2;
        if($fecha_3!=false)
            $this->array_a_enviar['fechas_vencimiento'][]=$fecha_3;
        $this->array_a_enviar['importes'][]=$importe_1;
        if($importe_2!=false)
            $this->array_a_enviar['importes'][]=$importe_2;
        if($importe_3!=false)
            $this->array_a_enviar['importes'][]=$importe_3;
        $this->array_a_enviar['concepto']=$concepto;
        $this->ejecutar();
        if ($this->obtener_resultado()){
            $nro_boletas=  $this->obtener_datos();
            return $nro_boletas[0];
        }
            
    }
    public function consultar_transacciones($fecha_desde,$fecha_hasta,$filtros/*Filtros debe ser un array asociativo*/){
        $this->metodo_web_service="consultar_transacciones";
        $this->array_a_enviar['desde']=$fecha_desde;
        $this->array_a_enviar['hasta']=$fecha_hasta;
        $this->array_a_enviar['filtros']=$filtros;
        $this->ejecutar();
        return $this->obtener_resultado();
    }
    public function cancelar_boleta($nro_boleta){
        $this->metodo_web_service="cancelar_boleta";
        $this->array_a_enviar['nro_boleta']=$nro_boleta;
        $this->ejecutar();
        return $this->obtener_log();
    }
    public function obtener_codigo_de_barras($nro_boleta){
        $this->metodo_web_service="obtener_codigo_de_barras";
        $this->array_a_enviar['nro_boleta']=$nro_boleta;
        $this->ejecutar();
        return $this->obtener_resultado();
    }
    //Fin de funciones/////
    public function ejecutar($metodo_webservice=false, $array=false) {
        $this->array_a_enviar['idComercio']=$this->idComercio;
        $this->array_a_enviar['sid']=$this->sid;
        if($metodo_webservice===false)
            $metodo_webservice=$this->metodo_web_service;
        if($array===false){
            $array=$this->array_a_enviar;
        }
        $array['metodo_webservice']=  $metodo_webservice;
        $url = "https://172.20.10.133:356/externo/script_landing_webservice_2.php";
        $postdata = http_build_query($array);
        $opts = array('http' =>
                    array(
                        'method' => $this->method,
                        'header' => 'Content-type: application/x-www-form-urlencoded',
                        'content' => $postdata
                        )
                );
        $context = stream_context_create($opts);
        $datos = file_get_contents($url, false, $context);
        $this->resultado = json_decode($datos,true);
        foreach ($this->resultado['log'] as $mensaje){
            error_log($mensaje);
        }
        $this->array_a_enviar=array(); //funcion reinicializar
    }
    public function obtener_datos() {
        return $this->resultado['datos'];
    }
    public function obtener_resultado() {
        if($this->resultado['ejecucion_correcta']==1)
            return true;
        return false;
    }
    public function obtener_log() {
        return $this->resultado['log'];
    }
}
