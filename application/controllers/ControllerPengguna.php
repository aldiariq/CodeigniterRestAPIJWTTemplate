<?php

defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class ControllerPengguna extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelPengguna');
    }

    public function daftarpengguna_post()
    {
        $emailpengguna = $this->input->post('email');
        $namapengguna = $this->input->post('nama');
        $passwordpengguna = md5($this->input->post('password'));

        $datapengguna = array(
            'email_pengguna' => $emailpengguna,
            'nama_pengguna' => $namapengguna,
            'password_pengguna' => $passwordpengguna
        );

        if ($this->ModelPengguna->daftarpengguna($datapengguna)) {
            $keterangan = array(
                'berhasil' => true,
                'pesan' => 'Berhasil Mendaftarkan Pengguna'
            );

            $this->set_response(
                $keterangan,
                200
            );
        } else {
            $keterangan = array(
                'berhasil' => false,
                'pesan' => 'Gagal Mendaftarkan Pengguna'
            );

            $this->set_response(
                $keterangan,
                401
            );
        }
    }

    public function masukpengguna_post()
    {
        $emailpengguna = $this->input->post('email');
        $passwordpengguna = $this->input->post('password');

        $datapengguna = array(
            'email_pengguna' => $emailpengguna,
            'password_pengguna' => md5($passwordpengguna)
        );

        if ($this->ModelPengguna->masukpengguna($datapengguna)) {
            foreach ($this->ModelPengguna->getpengguna($datapengguna) as $data) {
                $datatoken = array(
                    'id' => $data->id_pengguna,
                    'email_pengguna' => $data->email_pengguna,
                    'time' => time()
                );
            }

            $tokenpengguna = $this->authorizationtoken->generateToken($datatoken);

            $keterangan = array(
                'berhasil' => true,
                'pesan' => 'Berhasil Masuk',
                'token' => $tokenpengguna,
                'pengguna' => $this->ModelPengguna->getpengguna($datapengguna)
            );

            $this->set_response(
                $keterangan,
                200
            );
        } else {
            $keterangan = array(
                'berhasil' => false,
                'pesan' => 'Gagal Masuk'
            );

            $this->set_response(
                $keterangan,
                401
            );
        }
    }

    public function lihatdatapengguna_get()
    {
        $validasitoken = $this->authorizationtoken->validateToken();

        if (!empty($validasitoken) && $validasitoken['status'] === TRUE) {
            $id_pengguna = $this->uri->segment(3);

            $wherepengguna = array('id_pengguna' => $id_pengguna);

            $datapengguna = $this->ModelPengguna->getpengguna($wherepengguna);

            $keterangan = array(
                'berhasil' => false,
                'pesan' => 'Berhasil Mendapatkan Info Pengguna',
                'pengguna' => $datapengguna
            );

            $this->set_response(
                $keterangan,
                200
            );
        } else {
            $keterangan = array(
                'berhasil' => false,
                'pesan' => 'Token Tidak Valid',
                'pengguna' => null
            );

            $this->set_response(
                $keterangan,
                401
            );
        }
    }

    public function gantipasswordpengguna_post()
    {
        $validasitoken = $this->authorizationtoken->validateToken();

        if (!empty($validasitoken) && $validasitoken['status'] === TRUE) {
            $id_pengguna = $this->input->post("idpengguna");

            $datapengguna = array('id_pengguna' => $id_pengguna);

            foreach ($this->ModelPengguna->getpasswordlamapengguna($datapengguna) as $password) {
                $password_lama = md5($this->input->post("passwordlama"));
                $password_baru = md5($this->input->post("passwordbaru"));
                if ($password_lama == $password->password_pengguna) {
                    $datapassword = array('password_pengguna' => $password_baru);

                    $gantipassword = $this->ModelPengguna->gantipasswordpengguna($datapengguna, $datapassword);

                    if ($gantipassword) {
                        $keterangan = array(
                            'berhasil' => true,
                            'pesan' => 'Berhasil Mengganti Password'
                        );

                        $this->set_response(
                            $keterangan,
                            200
                        );
                    } else {
                        $keterangan = array(
                            'berhasil' => false,
                            'pesan' => 'Gagal Mengganti Password'
                        );

                        $this->set_response(
                            $keterangan,
                            401
                        );
                    }
                } else {
                    $keterangan = array(
                        'berhasil' => false,
                        'pesan' => 'Gagal Mengganti Password'
                    );

                    $this->set_response(
                        $keterangan,
                        401
                    );
                }
            }
        } else {
            $keterangan = array(
                'berhasil' => false,
                'pesan' => 'Token Tidak Valid'
            );

            $this->set_response(
                $keterangan,
                401
            );
        }
    }
}

/* End of file ControllerPengguna.php */
