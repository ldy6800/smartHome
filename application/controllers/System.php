<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class System extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}
	
	private function head(){
		$this->load->view('header');
	}
	private function navbar(){
		$this->load->view('navbar');
	}	
	private function foot(){
		$this->load->view('footer');
	}

	private function publishMQTT($topic, $msg, $host = ''){
		$prompt = 'mosquitto_pub -d';
		if ($host != '') $prompt .= ' -h '.$host;
		if ($topic == '' || $msg == '') return;
		$topic = str_replace('-', '/', $topic);	
		$prompt .= ' -t '.$topic.' -m '.$msg;
		$result =shell_exec($prompt);

		/*
		echo '<pre>TOPIC : '.$topic.'<br>MSG: '.$msg.'<br><pre>';
		echo '<hr>Result:<br>';
		echo '<pre>'.$result.'</pre>';
		*/
		return $result;
	}

	public function pubChange($flag){

		$data = array();
		$data['topic']='house-battery-change-1';
		$data['flag']= $flag;
		$data['result'] = $this->publishMQTT('house-battery-change-1', $flag);
	
		echo json_encode($data);	
	}

	public function test(){
		$this->head();
		$this->navbar();
		$this->load->view('test');
		$this->foot();
	}

	public function readFee(){
		$fee = array();
		$handle = fopen("/var/www/data/fee.csv", "r");
		while($data = fgetcsv($handle, 1000, ',')) {
			$num = count($data);
			if ($num == 1) {
				$row = $data[0];
				$fee[$row] = array();
			}
			else {
				if (count(array_slice($data, 1)) == 1){
					$fee[$row][$data[0]] = array_slice($data, 1)[0];
				}
				else{
					$fee[$row][$data[0]] = array_slice($data, 1);
				}
			}
		}

		return $fee;
	}

	public function calcFeeWithoutDiscount($kwh){
		$data = $this->readFee();

		$fee = 0;

		foreach($data['std'] as $d){
			if ($d[0] < $kwh){
				$std = key_search($kwh, $data);
			}

			
		}
		
	}	
	public function modifyFeePage(){
		$data = $this->readFee();
	
		$value = 0;	
		if ($this->input->post('kwh')){
			$value = $this->calcFeeWithoutDiscount($kwh);
		}	
		$this->load->view('modify_fee', array('data'=>$data, 'value'=>$value));
		
	}
}	
