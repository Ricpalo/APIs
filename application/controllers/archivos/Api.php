<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use RestServer\RestController;
require APPPATH . '/libraries/RestController.php';
require APPPATH . '/libraries/Format.php';


class Api extends RestController {
    
    function archivos_post() {
        

        $this->form_validation->set_rules('pArchivo', 'Archivo', 'callback_validar_archivo[pArchivo]');
        $this->form_validation->set_rules('pFoto', 'Foto', 'callback_validar_archivo[pFoto]');

        if($this->form_validation->run()) {
            $configuracion = array(
                'upload_path' => "./archivos",
                'allowed_types' => 'jpg|png',
                'max_size' => 2048,
                'file_ext_tolower' => TRUE,
                'encrypt_name' => TRUE
            );

            $this->load->library('upload', $configuracion);

            if($this->upload->do_upload('pArchivo')) {
                echo "Ya se subio";
            } else {
                echo var_dump($this->upload->display_errors());
            }

            // if($this->upload->do_upload('pFoto')) {
            //     echo "Ya se subio";
            // } else {
            //     echo var_dump($this->upload->display_errors());
            // }

        } else {
            echo "Cagaste";
            echo var_dump($this->form_validation->error_array());
        }
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
