<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use RestServer\RestController;
require APPPATH . '/libraries/RestController.php';
require APPPATH . '/libraries/Format.php';

class Api extends RestController {
    
    // function __construct() {
    //     parent::__construct();
    //     $this->load->model('DAO');
    // }

    function materiales_get() {
        $this->load->model('DAO');

        if ($this->get('pId')) {
            $material = $this->DAO->seleccionar_entidad('tb_materiales', array('id' => $this->get('pId')), TRUE);

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $material,
                "errores" => array()
            );
        } else {
            $materiales = $this->DAO->seleccionar_entidad('tb_materiales');

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $materiales,
                "errores" => array()
            );
        }

        $this->response($respuesta, 200);
    }

    function materiales_post() {
        $this->load->model('DAO');

        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('descripcion', 'Descripción', 'required|max_length[50]|min_length[5]');
        $this->form_validation->set_rules('tipo', 'Tipo', 'required');

        if ( $this->form_validation->run() ) {
            $datos = array(
                "descripcion" => $this->post('descripcion'),
                "tipo" => $this->post('tipo')
            );

            $respuesta = $this->DAO->insert_modificar_entidad('tb_materiales', $datos);

            if ($respuesta['status'] == '1') {
                $respuesta = array(
                    "status" => "1",
                    "mensaje" => "Registro Correcto",
                    "datos" => array(),
                    "errores" => array()
                );
            } else {
                $respuesta = array(
                    "status" => "0",
                    "errores" => array(),
                    "mensaje" => "Error al registrar",
                    "datos" => array()
                );
            }

        } else {
            $respuesta = array(
                "status" => "0",
                "errores" => $this->form_validation->error_array(),
                "mensaje" => "Error al procesar la información",
                "datos" => array()
            );
        }

        $this->response($respuesta, 200);
    }

    function validar_archivo($valor, $param) {
        
        if(isset($_FILES[$param]) && $_FILES[$param] && $_FILES[$param]['name']) {
            return TRUE;
        } else {
            $this->form_validation->set_message('validar_archivo', 'El archivo es obligatorio');
            return FALSE;
        }
    }
}