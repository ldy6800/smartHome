<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

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
	public function login() {
		$this->load->view('head');
		$this->load->view('login');			
		$this->foot();	
	}

	public function authentication() {
		$user = $this->auth_model->getUserById($this->input->post('id'));	
			
		if ($user && password_verify($this->input->post('pw'), $user->pw)) {
			$this->auth_model->destroySession($user->id, $this->input->ip_address());
			$this->session->set_userdata('isLogin', true);
			$this->session->set_userdata('userID', $user->id);
			$this->session->set_userdata('ip', $this->input->ip_address());
			redirect('/home');
		} else {
			echo 'Login Fail';
		}
	}
}	
