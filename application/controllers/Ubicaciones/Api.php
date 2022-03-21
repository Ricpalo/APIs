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

    function ubicaciones_get() {
        $this->load->model('DAO');

        if ($this->get('id')) {
            $ubicacion = $this->DAO->seleccionar_entidad('tb_ubicaciones', array('id' => $this->get('id')), TRUE);

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $ubicacion,
                "errores" => array()
            );
        } else {
            $ubicaciones = $this->DAO->seleccionar_entidad('tb_ubicaciones');

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $ubicaciones,
                "errores" => array()
            );
        }

        $this->response($respuesta, 200);
    }

    function ubicaciones_post() {
        $this->load->model('DAO');

        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('nombre', 'Nombre', 'required');
        $this->form_validation->set_rules('direccion', 'Direccion', 'required');

        if ( $this->form_validation->run() ) {
            $datos = array(
                "nombre" => $this->post('nombre'),
                "direccion" => $this->post('direccion')
            );

            $respuesta = $this->DAO->insert_modificar_entidad('tb_ubicaciones', $datos);

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

    function ubicaciones_put() {
        $this->load->model('DAO');

        $this->form_validation->set_data($this->put());
        $this->form_validation->set_rules('nombre', 'Nombre', 'required');
        $this->form_validation->set_rules('direccion', 'Direccion', 'required');

        if ( $this->form_validation->run() ) {
            $datos = array(
                "nombre" => $this->put('nombre'),
                "direccion" => $this->put('direccion')
            );

            $respuesta = $this->DAO->insert_modificar_entidad('tb_ubicaciones', $datos, array('id' => $this->put('id')));

            if ($respuesta['status'] == '1') {
                $respuesta = array(
                    "status" => "1",
                    "mensaje" => "Actualizado Correcto",
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

    function ubicaciones_delete() {
        $this->load->model('DAO');

        if ($this->input->get('id')) {
            $ubicacion = $this->DAO->eliminar_entidad('tb_ubicaciones', $this->input->get('id'));

            $respuesta = array(
                "status" => "1",
                "mensaje" => "Eliminado Correcto",
                "datos" => $ubicacion,
                "errores" => array(),
                "id" => $this->get('id')
            );
        } else {
            $respuesta = array(
                "status" => "0",
                "mensaje" => "No llega el id",
                "datos" => array(),
                "errores" => array(),
                "id" => $this->input->get('id')
            );
        }
        
        $this->response($respuesta, 200);
    }
}