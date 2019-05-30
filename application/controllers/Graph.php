<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Graph extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('auth_model');
	}

	private function head(){
		$this->load->view('header');
	}
	private function foot(){
		$this->load->view('footer');
	}

	public function navbar(){
		$this->load->view('navbar');
	}
	
	public function jsonExternalBattery(){
		$userID = $this->session->userdata('userID');
		
		$path = '/var/www/data/sensor/'.$userID.'/external';

		$json = array();
		$handle = fopen($path, "r");
		while($data = fgetcsv($handle, 1000, ',')){
			$date = $data[0];
			$current = $data[1];
			$volt = $data[2];
			$flag = ($data[3] == 1 && $data[4] ==  0) ? true : false;
			
			if (!$flag || $volt < 0) continue;
			$w = $volt * $current;	
			$js = array('date' => $date, 'cons' => $w);
			$json[] = $js;
		}
		echo json_encode($json);
	} 
}
