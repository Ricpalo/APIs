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

    function productos_get() {
        $this->load->model('DAO');

        if ($this->get('id_cat')) {
            $producto = $this->DAO->seleccionar_entidad('tb_productos', array('fk_categoria' => $this->get('id_cat')));

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $producto,
                "errores" => array()
            );
        } else {
            $productos = $this->DAO->seleccionar_entidad('tb_productos');

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $productos,
                "errores" => array()
            );
        }

        $this->response($respuesta, 200);
    }

    function productos_post() {
        $this->load->model('DAO');

        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('codigo_barras', 'Codigo de Barras', 'required|max_length[50]|min_length[5]');
        $this->form_validation->set_rules('nombre_producto', 'Nombre', 'required|max_length[50]|min_length[5]');
        $this->form_validation->set_rules('desc_producto', 'Descripción', 'required|max_length[50]|min_length[5]');
        $this->form_validation->set_rules('existencia_producto', 'Existencia', 'required');
        $this->form_validation->set_rules('precio_producto', 'Precio', 'required');
        $this->form_validation->set_rules('icono', 'Icono', 'callback_validar_archivo[icono]');
        $this->form_validation->set_rules('fk_categoria', 'Categoria', 'callback_validar_archivo[icono]');

        if ( $this->form_validation->run() ) {
            $configuracion = array(
                'upload_path' => "./productos",
                'allowed_types' => '*',
                'max_size' => 2048,
                'file_ext_tolower' => TRUE,
                'encrypt_name' => TRUE
            );

            $this->load->library('upload', $configuracion);

            if($this->upload->do_upload('icono')) {
                $nombre = $this->upload->data()['file_name'];

                $datos = array(
                    "codigo_barras" => $this->post('codigo_barras'),
                    "nombre_producto" => $this->post('nombre_producto'),
                    "desc_producto" => $this->post('desc_producto'),
                    "existencia_producto" => $this->post('existencia_producto'),
                    "precio_producto" => $this->post('precio_producto'),
                    "uri_icon_producto" => base_url()."/productos/".$nombre,
                    "fk_categoria" => $this->post('fk_categoria')
                );

                $respuesta = $this->DAO->insert_modificar_entidad('tb_productos', $datos);

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