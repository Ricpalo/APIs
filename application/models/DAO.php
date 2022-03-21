<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DAO extends CI_Model {
    // function __construct() {
    //     parent::__construct();
    // }
  
    function insert_modificar_entidad($entidad, $datos = array(), $filtro = null) {
        if ($filtro) {
            $this->db->where($filtro);
            $this->db->update($entidad, $datos);
        } else {
            $this->db->insert($entidad, $datos);
        }

        if ($this->db->error()['message'] != "") {
            return array(
                "status" => "0",
                "mensaje" => $this->db->error()['message'],
                "codigo" => $this->db->error()['code']
            );
        } else {
            return array(
                "status" => "1",
                "mensaje" => "Informacion registrada correctamente"
            );

        }
    }

    function seleccionar_entidad($entidad, $filtro = array(), $unico = FALSE) {
        if ($filtro) {
            $this->db->where($filtro);
        }

        $query = $this->db->get($entidad);

        if ($unico) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    function eliminar_entidad($entidad, $filtro = null) {
        if ($filtro) {
            $this->db->where('id', $filtro);
            $this->db->delete($entidad);
        } else {
            return array(
                "status" => "0",
                "mensaje" => "No hay nada",
                "codigo" => $this->db->error()['code']
            );
        }

        if ($this->db->error()['message'] != "") {
            return array(
                "status" => "0",
                "mensaje" => $this->db->error()['message'],
                "codigo" => $this->db->error()['code']
            );
        } else {
            return array(
                "status" => "1",
                "mensaje" => "Informacion procesada correctamente"
            );
        }
    }
}
