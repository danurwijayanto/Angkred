<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class KumEControl extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('KumEModel');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper(array('form'));
        $this->load->database();
    }

    public function index() {
        if (!$this->session->userdata('is_login')) {
            redirect('/LoginControl/');
        }
        $data['kum_e'] = $this->KumEModel->getTitleArray();
        $this->load->view('KumEView', $data);
    }

    public function showKomponenKegiatan() {
        $id = $this->input->post('id');
        $data['kum_e'] = $this->KumEModel->get_komponenKegiatan($id);
        $this->load->view('KumE_Komponen_Kegiatan', $data);
    }

    public function inputKumE() {
        //Upload File
        $dir = $this->session->userdata('nip');
        $config['upload_path'] = "./assets/files/$dir";
        $config['allowed_types'] = "pdf";
        $config['max_size'] = 0;
        $config['file_name'] = time().$_FILES['file_bukti']['name'];
        if(!is_dir($config['upload_path'])){
            echo "Folder Dibuat";
            mkdir($config['upload_path'],0777);
        }
        $this->load->library('upload',$config);
        $this->upload->do_upload('file_bukti');
        $query = array(
            'id_kum' => $this->input->post('id_kum'),
            'nip' => $this->session->userdata('nip'),
            'uraian_kegiatan' => $this->input->post('uraian_kegiatan'),
            'tanggal' => $this->input->post('tanggal'),
            'satuan_hasil' => $this->input->post('satuan_hasil'),
            'volume_kegiatan' => $this->input->post('volume_kegiatan'),
            'file_bukti' => $config['file_name']
        );
        $this->db->insert('pengambilan_kume', $query);
        $data['kum_e'] = $this->KumEModel->getTitleArray();
        $data['notify'] = "Data Berhasil Dimasukan";
        $this->load->view('KumEView',$data);
    }
}
