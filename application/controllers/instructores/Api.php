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

    function instructores_get() {
        $this->load->model('DAO');

        if ($this->get('id')) {
            $instructor = $this->DAO->seleccionar_entidad('tb_instructores', array('id' => $this->get('id')), TRUE);

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $instructor,
                "errores" => array()
            );
        } else {
            $instructores = $this->DAO->seleccionar_entidad('tb_instructores');

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $instructores,
                "errores" => array()
            );
        }

        $this->response($respuesta, 200);
    }

    function instructores_post() {
        $this->load->model('DAO');

        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('nombre', 'Nombre', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');

        if ( $this->form_validation->run() ) {
            $datos = array(
                "nombre" => $this->post('nombre'),
                "email" => $this->post('email')
            );

            $respuesta = $this->DAO->insert_modificar_entidad('tb_instructores', $datos);

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

    function instructores_put() {
        $this->load->model('DAO');

        $alumno = $this->DAO->seleccionar_entidad('tb_instructores', array('id' => $this->put('id')), TRUE);

        $this->form_validation->set_data($this->put());
        $this->form_validation->set_rules('nombre', 'Nombre', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');

        if ( $this->form_validation->run() ) {
            $datos = array(
                "nombre" => $this->put('nombre'),
                "email" => $this->put('email')
            );

            $respuesta = $this->DAO->insert_modificar_entidad('tb_instructores', $datos, array('id' => $this->put('id')));

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

    function instructores_delete() {
        $this->load->model('DAO');

        if ($this->input->get('id')) {
            $instructor = $this->DAO->eliminar_entidad('tb_instructores', $this->input->get('id'));

            $respuesta = array(
                "status" => "1",
                "mensaje" => "Eliminado Correcto",
                "datos" => $instructor,
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