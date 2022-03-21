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

    function usuarios_get() {
        $this->load->model('DAO');

        if ($this->get('pId')) {
            $usuario = $this->DAO->seleccionar_entidad('tb_usuarios', array('id' => $this->get('pId')), TRUE);

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $usuario,
                "errores" => array()
            );
        } else {
            $usuarios = $this->DAO->seleccionar_entidad('tb_usuarios');

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $usuarios,
                "errores" => array()
            );
        }

        $this->response($respuesta, 200);
    }

    function usuarios_post() {
        $this->load->model('DAO');

        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('correo', 'Correo', 'required');
        $this->form_validation->set_rules('nombre', 'Nombre', 'required');
        $this->form_validation->set_rules('clave', 'Clave', 'required');
        $this->form_validation->set_rules('foto', 'Foto', 'callback_validar_archivo[foto]');

        if ( $this->form_validation->run() ) {
            $configuracion = array(
                'upload_path' => "./usuarios",
                'allowed_types' => '*',
                'max_size' => 2048,
                'file_ext_tolower' => TRUE,
                'encrypt_name' => TRUE
            );

            $this->load->library('upload', $configuracion);

            if($this->upload->do_upload('foto')) {
                $nombre = $this->upload->data()['file_name'];

                $datos = array(
                    "correo" => $this->post('correo'),
                    "nombre" => $this->post('nombre'),
                    "clave" => $this->post('clave'),
                    "foto" => base_url()."/usuarios/".$nombre
                );

                $respuesta = $this->DAO->insert_modificar_entidad('tb_usuarios', $datos);

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