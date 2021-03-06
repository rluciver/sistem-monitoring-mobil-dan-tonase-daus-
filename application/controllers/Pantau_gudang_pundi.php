<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pantau_gudang_pundi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_pantau_gudang_pundi','pantau_gudang_pundi');
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
		$this->load->view('v_pantau_gudang_pundi',$data);
		
	}

	public function ajax_list()
	{
		$list = $this->pantau_gudang_pundi->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pantau_gudang_pundi) {			
            $row[] = $no++;
			$row = array();
            $row[] = $pantau_gudang_pundi->nourut;
            $row[] = $pantau_gudang_pundi->nobongkar;
            $row[] = $pantau_gudang_pundi->nosuratjalan;
            $row[] = $pantau_gudang_pundi->nopolisi;
            $row[] = $pantau_gudang_pundi->namasupir;
            $row[] = $pantau_gudang_pundi->tujuangudang;
            $row[] = $pantau_gudang_pundi->pricipal;
            $row[] = number_format($pantau_gudang_pundi->party,0,',','.');
            $row[] = $pantau_gudang_pundi->jenispupuk;
            $row[] = number_format($pantau_gudang_pundi->timbangisi,0,',','.');
            $row[] = number_format($pantau_gudang_pundi->timbangkosong,0,',','.');
            $row[] = number_format($pantau_gudang_pundi->beratbersih,0,',','.');
            $row[] = "KG";
            $row[] = $pantau_gudang_pundi->tanggal;
            $row[] = $pantau_gudang_pundi->brangkat;
            $row[] = $pantau_gudang_pundi->tiba;
            $row[] = $pantau_gudang_pundi->subgudang;
            $row[] = $pantau_gudang_pundi->keterangan;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_pantau_gudang_pundi('."'".$pantau_gudang_pundi->nosuratjalan."'".')"><i class="glyphicon glyphicon-pencil"></i> KONFIRMASI</a><a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_pantau_gudang_pundi('."'".$pantau_gudang_pundi->nosuratjalan."'".')">
				<i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->pantau_gudang_pundi->count_all(),
						"recordsFiltered" => $this->pantau_gudang_pundi->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->pantau_gudang_pundi->get_by_id($id);
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
		$insert = $this->pantau_gudang_pundi->save($data);
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
		$this->pantau_gudang_pundi->update(array('nourut' => $this->input->post('nourut')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->pantau_gudang_pundi->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

}
