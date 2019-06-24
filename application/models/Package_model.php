<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Package_model extends CI_Model{
	
	// get all product
	function get_products(){
		$query = $this->db->get('product');
		return $query;
	}

	//GET PRODUCT BY PACKAGE ID
	function get_product_by_package($package_id){
		$this->db->select('*');
		$this->db->from('product');
		$this->db->join('detail', 'detail_product_id=product_id');
		$this->db->join('package', 'package_id=detail_package_id');
		$this->db->where('package_id',$package_id);
		$query = $this->db->get();
		return $query;
	}

	//READ
	function get_packages(){
		$this->db->select('package.*,COUNT(product_id) AS item_product');
		$this->db->from('package');
		$this->db->join('detail', 'package_id=detail_package_id');
		$this->db->join('product', 'detail_product_id=product_id');
		$this->db->group_by('package_id');
		$query = $this->db->get();
		return $query;
	}

	// CREATE
	function create_package($package,$product){
		$this->db->trans_start();
			//INSERT TO PACKAGE
			date_default_timezone_set("Asia/Bangkok");
			$data  = array(
				'package_name' => $package,
				'package_created_at' => date('Y-m-d H:i:s') 
			);
			$this->db->insert('package', $data);
			//GET ID PACKAGE
			$package_id = $this->db->insert_id();
			$result = array();
			    foreach($product AS $key => $val){
				     $result[] = array(
				      'detail_package_id'  	=> $package_id,
				      'detail_product_id'  	=> $_POST['product'][$key]
				     );
			    }      
			//MULTIPLE INSERT TO DETAIL TABLE
			$this->db->insert_batch('detail', $result);
		$this->db->trans_complete();
	}

	
	// UPDATE
	function update_package($id,$package,$product){
		$this->db->trans_start();
			//UPDATE TO PACKAGE
			$data  = array(
				'package_name' => $package
			);
			$this->db->where('package_id',$id);
			$this->db->update('package', $data);
			
			//DELETE DETAIL PACKAGE
			$this->db->delete('detail', array('detail_package_id' => $id));

			$result = array();
			    foreach($product AS $key => $val){
				     $result[] = array(
				      'detail_package_id'  	=> $id,
				      'detail_product_id'  	=> $_POST['product_edit'][$key]
				     );
			    }      
			//MULTIPLE INSERT TO DETAIL TABLE
			$this->db->insert_batch('detail', $result);
		$this->db->trans_complete();
	}

	// DELETE
	function delete_package($id){
		$this->db->trans_start();
			$this->db->delete('detail', array('detail_package_id' => $id));
			$this->db->delete('package', array('package_id' => $id));
		$this->db->trans_complete();
	}
	
}