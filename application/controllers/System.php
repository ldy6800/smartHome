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
		$data['topic']='house-battery-change-smarthome';
		$data['flag']= $flag;
		$data['result'] = $this->publishMQTT('house-battery-change-smarthome', $flag);
	
		echo json_encode($data);	
	}

	public function pubSwitchControl($id, $flag = 1){

		$this->publishMQTT('house-device-switch-'.$this->session->userdata('userID').'-'.$id, $flag);
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

	public function jsonChargeWithoutDiscount($kwh){
		$data = $this->readFee();
	/*	echo '<xmp>';
		var_dump($data);
		echo '</xmp>';*/
		$f = 0;
		$std = 1;
		foreach($data['std'] as $d){
			$f +=(($kwh > $d[1]) ? $d[1] : $kwh) * $data['kwh'][array_search($d, $data['std'])];
			$kwh -= (int)$d[1];
			if ($kwh < 0) {
				break;
			}
			$std++;
		}

		if ($std > 4) $std= 4;
		$f += (int)$data['base']['r'.$std];
		//echo $f;

		echo json_encode($f);
		return $f;
		
	}
	
	public function jsonGetCurrentCharge(){
		$userID = $this->session->userdata('userID');
		
		$path = "/var/www/data/sensor/".$userID."/external";

		$kw = 0;
		$handle = fopen($path, "r");
		while($data = fgetcsv($handle, 1000, ",")){
			$num = count($data);
			if($data[3] == 0 || $data[4] == 1) continue;
			$current = $data[1];
			$volt = $data[2]; 
			if ($volt < 0) continue;	
			$kw += $current * $volt;
		}
		$kw /= 1000;	

		$this->jsonChargeWithoutDiscount($kw);
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
