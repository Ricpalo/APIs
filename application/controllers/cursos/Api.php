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

    function cursos_get() {
        $this->load->model('DAO');

        if ($this->get('id')) {
            $curso = $this->DAO->seleccionar_entidad('tb_cursos', array('id' => $this->get('id')), TRUE);

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $curso,
                "errores" => array()
            );
        } else {
            $cursos = $this->DAO->seleccionar_entidad('tb_cursos');

            $respuesta = array(
                "status" => '1',
                "mensaje" => "Informacion cargada correctamente",
                "datos" => $cursos,
                "errores" => array()
            );
        }

        $this->response($respuesta, 200);
    }

    function cursos_post() {
        $this->load->model('DAO');

        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('titulo', 'Titulo', 'required');
        $this->form_validation->set_rules('duracion', 'Duracion', 'required');
        $this->form_validation->set_rules('precio', 'Precio', 'required');
        $this->form_validation->set_rules('descripcion', 'Descripcion', 'required');

        if ( $this->form_validation->run() ) {
            $datos = array(
                "titulo" => $this->post('titulo'),
                "duracion" => $this->post('duracion'),
                "precio" => $this->post('precio'),
                "descripcion" => $this->post('descripcion')
            );

            if ($this->post('id')) {
                $respuesta = $this->DAO->insert_modificar_entidad('tb_cursos', $datos, $this->post('id'));
            } else {
                $respuesta = $this->DAO->insert_modificar_entidad('tb_cursos', $datos);
            } 

            if ($respuesta['status'] == '1') {
                $respuesta = array(
                    "status" => "1",
                    "mensaje" => "Registro Correcto",
                    "datos" => array(),
                    "errores" => array()
                );
            } else if ($respuesta['status'] == '2') {
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

    function cursos_delete() {
        $this->load->model('DAO');

        if ($this->input->get('id')) {
            $curso = $this->DAO->eliminar_entidad('tb_cursos', $this->input->get('id'));

            $respuesta = array(
                "status" => "1",
                "mensaje" => "Eliminado Correcto",
                "datos" => $curso,
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