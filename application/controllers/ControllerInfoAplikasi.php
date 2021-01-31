<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class ControllerInfoAplikasi extends RestController {

    public function index_get()
    {
        $validasitoken = $this->authorizationtoken->validateToken();

        if(!empty($validasitoken) && $validasitoken['status'] === TRUE){
            $infoaplikasi = array(
                'namaaplikasi' => 'Template Codeigniter JWT RestAPI',
                'deskripsiaplikasi' => 'Aplikasi ini merupakan template dari implementasi RestAPI menggunakan framework Codeigniter dan JWT'
            );
    
            $this->set_response(
                $infoaplikasi, 200
            );
        }else {
            $keterangan = array(
                'berhasil' => false,
                'pesan' => 'Token Tidak Valid'
            );

            $this->set_response(
                $keterangan, 401
            );
        }
    }

}

/* End of file Controllername.php */
