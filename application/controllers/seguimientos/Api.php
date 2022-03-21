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

    function seguimientos_get() {
        $this->load->model('DAO');

        if ($this->get('id')) {
            $seguimiento = $this->DAO->seleccionar_entidad('tb_seguimiento_recoleccion', array('id_solicitud' => $this->get('id')));

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $seguimiento,
                "errores" => array()
            );
        } else {
            $seguimientos = $this->DAO->seleccionar_entidad('tb_seguimiento_recoleccion');

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $seguimientos,
                "errores" => array()
            );
        }

        $this->response($respuesta, 200);
    }

    function seguimientos_post() {
        $this->load->model('DAO');

        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('status', 'Status', 'required');
        $this->form_validation->set_rules('foto', 'Foto', 'callback_validar_archivo[foto]');
        $this->form_validation->set_rules('id_usuario', 'Usuario', 'required');
        $this->form_validation->set_rules('id_solicitud', 'Solicitud', 'required');
        $this->form_validation->set_rules('coordenadas', 'Coordenadas', 'required');

        if ( $this->form_validation->run() ) {
            $configuracion = array(
                'upload_path' => "./seguimientos",
                'allowed_types' => '*',
                'max_size' => 2048,
                'file_ext_tolower' => TRUE,
                'encrypt_name' => TRUE
            );

            $this->load->library('upload', $configuracion);

            if($this->upload->do_upload('foto')) {
                $nombre = $this->upload->data()['file_name'];

                $datos = array(
                    "status" => $this->post('status'),
                    "foto" => base_url()."/seguimientos/".$nombre,
                    "id_usuario" => $this->post('id_usuario'),
                    "id_solicitud" => $this->post('id_solicitud'),
                    "coordenadas" => $this->post('coordenadas')
                );

                $respuesta = $this->DAO->insert_modificar_entidad('tb_seguimiento_recoleccion', $datos);

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