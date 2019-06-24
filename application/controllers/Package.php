<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Package extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->model('Package_model','package_model');
	}

	// READ
	function index(){
		$data['product'] = $this->package_model->get_products();
		$data['package'] = $this->package_model->get_packages();
		$this->load->view('package_view',$data);
	}

	//CREATE
	function create(){
		$package = $this->input->post('package',TRUE);
		$product = $this->input->post('product',TRUE);
		$this->package_model->create_package($package,$product);
		redirect('package');
	}

	function get_product_by_package(){
		$package_id=$this->input->post('package_id');
    	$data=$this->package_model->get_product_by_package($package_id)->result();
    	foreach ($data as $result) {
    		$value[] = (float) $result->product_id;
    	}
    	echo json_encode($value);
	}

	//UPDATE
	function update(){
		$id = $this->input->post('edit_id',TRUE);
		$package = $this->input->post('package_edit',TRUE);
		$product = $this->input->post('product_edit',TRUE);
		$this->package_model->update_package($id,$package,$product);
		redirect('package');
	}

	// DELETE
	function delete(){
		$id = $this->input->post('delete_id',TRUE);
		$this->package_model->delete_package($id);
		redirect('package');
	}
}