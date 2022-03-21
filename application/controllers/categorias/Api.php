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

    function categorias_get() {
        $this->load->model('DAO');

        if ($this->get('pId')) {
            $categoria = $this->DAO->seleccionar_entidad('tb_categorias', array('id_cat' => $this->get('pId')), TRUE);

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $categoria,
                "errores" => array()
            );
        } else {
            $categorias = $this->DAO->seleccionar_entidad('tb_categorias');

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $categorias,
                "errores" => array()
            );
        }

        $this->response($respuesta, 200);
    }

    function categorias_post() {
        $this->load->model('DAO');

        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('descripcion', 'Descripción', 'required|max_length[50]|min_length[5]');
        $this->form_validation->set_rules('icono', 'Icono', 'callback_validar_archivo[icono]');

        if ( $this->form_validation->run() ) {
            $configuracion = array(
                'upload_path' => "./categorias",
                'allowed_types' => '*',
                'max_size' => 2048,
                'file_ext_tolower' => TRUE,
                'encrypt_name' => TRUE
            );

            $this->load->library('upload', $configuracion);

            if($this->upload->do_upload('icono')) {
                $nombre = $this->upload->data()['file_name'];

                $datos = array(
                    "desc_cat" => $this->post('descripcion'),
                    "uri_icon_cat" => base_url()."/categorias/".$nombre
                );

                $respuesta = $this->DAO->insert_modificar_entidad('tb_categorias', $datos);

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