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

    function eventos_get() {
        $this->load->model('DAO');

        if ($this->get('id')) {
            $evento = $this->DAO->seleccionar_entidad('tb_eventos_cursos', array('id' => $this->get('id')), TRUE);

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $evento,
                "errores" => array()
            );
        } else {
            $eventos = $this->DAO->seleccionar_entidad('tb_eventos_cursos');

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $eventos,
                "errores" => array()
            );
        }

        $this->response($respuesta, 200);
    }

    function eventos_post() {
        $this->load->model('DAO');

        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('fecha_inicio', 'Fecha de Inicio', 'required');
        $this->form_validation->set_rules('fecha_fin', 'Fecha de Fin', 'required');
        $this->form_validation->set_rules('id_instructor', 'Instructor', 'required');
        $this->form_validation->set_rules('id_curso', 'Curso', 'required');
        $this->form_validation->set_rules('id_ubicacion', 'Ubicacion', 'required');

        if ( $this->form_validation->run() ) {
            $datos = array(
                "fecha_inicio" => $this->post('fecha_inicio'),
                "fecha_fin" => $this->post('fecha_fin'),
                "id_instructor" => $this->post('id_instructor'),
                "id_curso" => $this->post('id_curso'),
                "id_ubicacion" => $this->post('id_ubicacion')
            );

            $respuesta = $this->DAO->insert_modificar_entidad('tb_eventos_cursos', $datos);

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

    function eventos_put() {
        $this->load->model('DAO');

        $alumno = $this->DAO->seleccionar_entidad('tb_eventos_cursos', array('id' => $this->put('id')), TRUE);

        $this->form_validation->set_data($this->put());
        $this->form_validation->set_rules('fecha_inicio', 'Fecha de Inicio', 'required');
        $this->form_validation->set_rules('fecha_fin', 'Fecha de Fin', 'required');
        $this->form_validation->set_rules('id_instructor', 'Instructor', 'required');
        $this->form_validation->set_rules('id_curso', 'Curso', 'required');
        $this->form_validation->set_rules('id_ubicacion', 'Ubicacion', 'required');

        if ( $this->form_validation->run() ) {
            $datos = array(
                "fecha_inicio" => $this->put('fecha_inicio'),
                "fecha_fin" => $this->put('fecha_fin'),
                "id_instructor" => $this->put('id_instructor'),
                "id_curso" => $this->put('id_curso'),
                "id_ubicacion" => $this->put('id_ubicacion')
            );

            $respuesta = $this->DAO->insert_modificar_entidad('tb_eventos_cursos', $datos, array('id' => $this->put('id')));

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

    function eventos_delete() {
        $this->load->model('DAO');

        if ($this->input->get('id')) {
            $evento = $this->DAO->eliminar_entidad('tb_eventos_cursos', $this->input->get('id'));

            $respuesta = array(
                "status" => "1",
                "mensaje" => "Eliminado Correcto",
                "datos" => $evento,
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