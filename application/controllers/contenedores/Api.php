<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use RestServer\RestController;
require APPPATH . '/libraries/RestController.php';
require APPPATH . '/libraries/Format.php';

header('Access-Control-Allow-Origin: *');

class Api extends RestController {
    
    // function __construct() {
    //     parent::__construct();
    //     $this->load->model('DAO');
    // }

    function contenedores_get() {
        $this->load->model('DAO');

        if ($this->get('id')) {
            $contenedor = $this->DAO->seleccionar_entidad('tb_contenedores', array('id_material' => $this->get('id')));

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $contenedor,
                "errores" => array()
            );
        } else {
            $contenedores = $this->DAO->seleccionar_entidad('tb_contenedores');

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $contenedores,
                "errores" => array()
            );
        }

        $this->response($respuesta, 200);
    }

    function contenedores_post() {
        $this->load->model('DAO');

        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('codigo', 'Codigo', 'required');
        $this->form_validation->set_rules('ubicacion', 'Ubicacion', 'required');
        // $this->form_validation->set_rules('foto', 'Foto', 'callback_validar_archivo[foto]');
        $this->form_validation->set_rules('responsable', 'Responsable', 'required');
        $this->form_validation->set_rules('id_material', 'Material', 'required');

        if ( $this->form_validation->run() ) {
            $configuracion = array(
                'upload_path' => "./contenedores",
                'allowed_types' => '*',
                'max_size' => 2048,
                'file_ext_tolower' => TRUE,
                'encrypt_name' => TRUE
            );

            $this->load->library('upload', $configuracion);

            if($this->upload->do_upload('foto')) {
                $nombre = $this->upload->data()['file_name'];

                $datos = array(
                    "codigo" => $this->post('codigo'),
                    "ubicacion" => $this->post('ubicacion'),
                    "foto" => base_url()."/contenedores/".$nombre,
                    "responsable" => $this->post('responsable'),
                    "id_material" => $this->post('id_material')
                );

                $respuesta = $this->DAO->insert_modificar_entidad('tb_contenedores', $datos);

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
                    "errores" => $this->upload->display_errors(),
                    "mensaje" => "Error al procesar la información",
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