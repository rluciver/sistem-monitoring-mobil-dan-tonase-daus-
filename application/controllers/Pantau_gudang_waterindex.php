<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pantau_gudang_waterindex extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_pantau_gudang_waterindex','pantau_gudang_waterindex');
		$this->load->model('m_pricipal');
	}

	public function index()
	{
		if($this->session->userdata('akses')!='10')
			{
		      	$url=base_url();
				redirect($url);
    		}

		$this->load->helper('url');
		$this->load->view('menu');

        $data['pricipal'] = $this->m_pricipal->getpricipal(); 
        $data['projek'] = $this->m_pricipal->getprojek(); 
		$this->load->view('v_pantau_gudang_waterindex',$data);

		
	}

	public function ajax_list()
	{
		$list = $this->pantau_gudang_waterindex->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pantau_gudang_waterindex) {			
            $row[] = $no++;
			$row = array();
            $row[] = $pantau_gudang_waterindex->nourut;
            $row[] = $pantau_gudang_waterindex->nobongkar;
            $row[] = $pantau_gudang_waterindex->nosuratjalan;
            $row[] = $pantau_gudang_waterindex->nopolisi;
            $row[] = $pantau_gudang_waterindex->namasupir;
            $row[] = $pantau_gudang_waterindex->tujuangudang;
            $row[] = $pantau_gudang_waterindex->pricipal;
            $row[] = number_format($pantau_gudang_waterindex->party,0,',','.');
            $row[] = $pantau_gudang_waterindex->jenispupuk;
            $row[] = number_format($pantau_gudang_waterindex->timbangisi,0,',','.');
            $row[] = number_format($pantau_gudang_waterindex->timbangkosong,0,',','.');
            $row[] = number_format($pantau_gudang_waterindex->beratbersih,0,',','.');
            $row[] = "KG";
            $row[] = $pantau_gudang_waterindex->tanggal;
            $row[] = $pantau_gudang_waterindex->brangkat;
            $row[] = $pantau_gudang_waterindex->tiba;
            $row[] = $pantau_gudang_waterindex->subgudang;
            $row[] = $pantau_gudang_waterindex->keterangan;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_pantau_gudang_waterindex('."'".$pantau_gudang_waterindex->nosuratjalan."'".')"><i class="glyphicon glyphicon-pencil"></i> KONFIRMASI</a><a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_pantau_gudang_waterindex('."'".$pantau_gudang_waterindex->nosuratjalan."'".')">
				<i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->pantau_gudang_waterindex->count_all(),
						"recordsFiltered" => $this->pantau_gudang_waterindex->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->pantau_gudang_waterindex->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add()
	{

				$a = $this->input->post('timbangisi');
				$b = $this->input->post('timbangkosong');
				$c = $a - $b;
		$data = array(
				'nourut' => $this->input->post('nourut'),
				'nobongkar' => $this->input->post('nobongkar'),
				'nosuratjalan' => $this->input->post('nosuratjalan'),
				'nopolisi' => $this->input->post('nopolisi'),
				'namasupir' => $this->input->post('namasupir'),
				'tujuangudang' => $this->input->post('tujuangudang'),
				'pricipal' => $this->input->post('pricipal'),
				'party' => $this->input->post('party'),
				'jenispupuk' => $this->input->post('jenispupuk'),
				'timbangisi' => $this->input->post('timbangisi'),
				'timbangkosong' => $this->input->post('timbangkosong'),
				'beratbersih' => $c,
				'tanggal' => $this->input->post('tanggal'),
				'brangkat' => $this->input->post('brangkat'),
				'tiba' => $this->input->post('tiba'),
				'subgudang' => $this->input->post('subgudang'),
				'keterangan' => $this->input->post('keterangan'),
			);
		$insert = $this->pantau_gudang_waterindex->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		date_default_timezone_set("Asia/Jakarta");/*
		date("Y-m-d H:i:s"),*/

		$a = $this->input->post('timbangisi');
		$b = $this->input->post('timbangkosong');
		$c = $a - $b;		
		$data = array(
				'tiba' => date("H:i:s"),
				'subgudang' => $this->input->post('subgudang'),
				'keterangan' => $this->input->post('keterangan'),
			);
		$this->pantau_gudang_waterindex->update(array('nourut' => $this->input->post('nourut')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->pantau_gudang_waterindex->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

}
