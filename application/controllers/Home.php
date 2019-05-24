<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	
	public function __construct(){
		parent::__construct();
		$this->load->model('device_model');
	}
	private function head(){
		$this->load->view('header');
	}
	private function navbar(){
		$this->load->view('navbar');
	}
	private function foot()
	{
		$this->load->view('footer');
	}	
	public function index()
	{
		$this->head();
		$this->navbar();
		$this->load->view('main_page');
		$this->foot();
	}

	public function charges(){
		$this->head();
		$this->navbar();

		$this->load->view('charges');
		$this->foot();
	}

	public function switches(){
		$this->head();
		$this->navbar();

		echo $this->session->userdata('userID');
		$device_list = $this->device_model->getDeviceList($this->session->userdata('userID'));

		foreach($device_list as $a){
			print_r($a);
		}		
		$this->load->view('switches', array('list' => $device_list));
		$this->foot();
		
	}
}
