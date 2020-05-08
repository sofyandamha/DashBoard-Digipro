<?php
require_once('main.php');

class Dashboard extends Main
{
	function __construct()
	{
		parent::__construct();
	}	
	
	function index($shop_id = 0)
	{
		if($this->session->userdata('is_owner') == 1) {
			if ($shop_id) {
				$this->session->set_userdata('shop_id', $shop_id);
				$this->session->set_userdata('action', 'shop_list');
			}
			
			if (!$this->session->userdata('shop_id')) {
				redirect(site_url('shops'));
			}
			$content['content'] = $this->load->view('dashboard', array(), true);
			$this->load_template($content);
			
		} else {
			
			if($this->session->userdata('role_id') == 2) {
				$this->session->set_userdata('shop_id', $shop_id);
				$this->session->set_userdata('action', 'shop_list');
				
				$content['content'] = $this->load->view('dashboard', array(), true);
				$this->load_template($content);

			} else if($this->session->userdata('role_id') == 3) {
				$this->session->set_userdata('shop_id', $shop_id);
				$this->session->set_userdata('action', 'shop_list');
				$content['content'] = $this->load->view('dashboard', array(), true);
				$this->load_template($content);
			} else {
			
				if($this->session->userdata('allow_shop_id') == $shop_id){
					if ($shop_id) {
						$this->session->set_userdata('shop_id', $shop_id);
						$this->session->set_userdata('action', 'shop_list');
					}
					
					if (!$this->session->userdata('shop_id')) {
						redirect(site_url('shops'));
					}

					
					
					$content['content'] = $this->load->view('dashboard', array(), true);
					
					$this->load_template($content);
				} else {
					$this->session->set_flashdata('error','Sorry, You don`t have permission to access that shop.');
					redirect(site_url() . "/dashboard/index/" . $this->session->userdata('allow_shop_id'));
				}
			
			}
			
		}
		
	}
	
	function notification()
	{
		//$dataToken = $this->gcm_token->get_all_data();
		//print_r($dataToken);
		$data['appusers'] = $this->gcm_token->get_all_data();
		//print_r($data['appusers']);
		$content['content'] = $this->load->view('appusers/notification',$data,true);		
		
		$this->load_template($content);
	}

	function listnotification()
	{
		//$dataToken = $this->gcm_token->get_all_data();
		//print_r($dataToken);
		$data['appusers'] = $this->mk_notification_contents->get_all();
		//print_r($data['appusers']);
		$content['content'] = $this->load->view('appusers/listnotification',$data,true);		
		
		$this->load_template($content);
	}

	function acceptNotification()
	{
		$datas = array(
			'appuser_id' => $this->input->post('appuser_id'),
			'notification_id' => $this->input->post('notification_id'),
			'access_date' => date("Y-m-d H:m:s"),
			'is_read' => 1
		);
		$this->mk_notification_historys->save($datas);
		$this->mk_notification_contents->updateReachUser($this->input->post('notification_id'));
		return true;
	}

	function sendNotification()
	{
		//header('Content-Type: application/json');
		$tokens = array();
		$createTopic = 'btn_ar_' . uniqid();
		$url = 'https://iid.googleapis.com/iid/v1:batchAdd';
		if($this->input->post('to') == 0){
			$tokenr = $this->gcm_token->get_all_regid();
			foreach($tokenr as $key => $value){
				array_push($tokens,$value['reg_id']);
			}
			$token = $tokens;
		}else{
			$token = explode (",", $this->input->post('to'));	
		}
		// print_r($token);
		// die();
		$serverKey = "AIzaSyAyMGSwTqce8k-o3g1Q1LYGbgoVYBZI8Pw";
		$arrayToSend = array('to' => "/topics/".$createTopic."", 'registration_tokens' => $token);
		$json = json_encode($arrayToSend);

		$headers = array();
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Authorization: key='. $serverKey;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
		//Send the request
		$response = curl_exec($ch);
		//Close request		
		if ($response === FALSE) {
			die('FCM Send Error: ' . curl_error($ch));
		}else{

			$url2 = 'https://fcm.googleapis.com/fcm/send';

			$dataBody = array(
			'body' => $this->input->post('body'), 
			'title' => $this->input->post('title'),
			'notification_id' => $createTopic,
			'link' => $this->input->post('link'),
			'click_action' => "notificationAction"
			);

			$datas = array(
				'body' => $this->input->post('body'), 
				'title' => $this->input->post('title'),
				'notification_id' => $createTopic,
				'link' => $this->input->post('link'),
				'total_target_user' => count($token),
				'reach_user' => 0,
				'created_date' => date("Y-m-d")
			);

			foreach($token as $reg_id){
				$appuserByRegId = $this->gcm_token->getbyRegId($reg_id);
				$datas = array(
					'appuser_id' => $appuserByRegId[0]['appuser_id'],
					'notification_id' => $createTopic,
					'access_date' => null,
					'is_read' => 0
				);
				$this->mk_notification_historys->save($datas);
			}
			
			$dat = array(
				'notification_id' => $createTopic,
				'title' => $this->input->post('title'),
				'body' => $this->input->post('body'),
				'link' => $this->input->post('link'),
				'created_date' => date("Y-m-d H:m:s"),
				'total_target_user' => count($token),
				'reach_user' => 0
			);
			//$this->mk_notification_contents->save($dat);
			$jsonData = json_encode($dataBody);
			$arrayToSend2 = array('to' => "/topics/".$createTopic."", 
			'content_available' => true,
			'priority' => 'high','data' => $dataBody,'notification' => $dataBody);
			$json2 = json_encode($arrayToSend2);
			$ch2 = curl_init();
			curl_setopt($ch2, CURLOPT_URL, $url2);
			curl_setopt($ch2, CURLOPT_CUSTOMREQUEST,"POST");
			curl_setopt($ch2, CURLOPT_POSTFIELDS, $json2);
			curl_setopt($ch2, CURLOPT_HTTPHEADER,$headers);
		//Send the request
			$response2 = curl_exec($ch2);
			if ($response2 === FALSE) {
				die('FCM Send Error: ' . curl_error($ch));
			}else{
				$url3 = 'https://iid.googleapis.com/iid/v1:batchRemove';
				$arrayToSend3 = array('to' => "/topics/".$createTopic."", 'registration_tokens' => $token);
				$json3 = json_encode($arrayToSend3);
				$ch3 = curl_init();
				curl_setopt($ch3, CURLOPT_URL, $url3);
				curl_setopt($ch3, CURLOPT_CUSTOMREQUEST,"POST");
				curl_setopt($ch3, CURLOPT_POSTFIELDS, $json3);
				curl_setopt($ch3, CURLOPT_HTTPHEADER,$headers);
				//Send the request
				$response = curl_exec($ch3);
				curl_close($ch3);
				if (isset($_SERVER["HTTP_REFERER"])) {
					header("Location: " . $_SERVER["HTTP_REFERER"]);
				}
			}			
		}
	}

	function profile()
	{
		$user_id = $this->user->get_logged_in_user_info()->user_id;
		$status = "";
		$message = "";
		
		if ($this->input->server('REQUEST_METHOD')=='POST') {
			$user_data = array(
				'user_name' => htmlentities($this->input->post('user_name'))
			);
							
			//If new user password exists,change password
			if ($this->input->post('user_password')!='') {
				$user_data['user_pass'] = md5($this->input->post('user_password'));
				$user_data['user_name'] = htmlentities( $this->input->post('user_name' ));
				$user_data['user_email'] = $this->input->post('user_email');
			}
			
			if ($this->user->update_profile($user_data,$user_id)) {
				$status = 'success';
				$message = 'User is successfully updated.';
			} else {
				$status = 'error';
				$message = 'Database error occured.Please contact your system administrator.';
			}
		}
		
		$data['user'] = $this->user->get_info($user_id);
		$data['status'] = $status;
		$data['message'] = $message;
		
		$content['content'] = $this->load->view('users/profile',$data,true);		
		
		$this->load_template($content);
	}
	
	//is exist
	function exists($user_id=null)
	{
		$user_name = $_REQUEST['user_name'];
		
		if (strtolower($this->user->get_info($user_id)->user_name) == strtolower($user_name)) {
			echo "true";
		} else if($this->user->exists(array('user_name'=>$_REQUEST['user_name']))) {
			echo "false";
		} else {
			echo "true";
		}
	}
	
	function backup()
	{
		// Load the DB utility class
		$this->load->dbutil();
		
		// Backup your entire database and assign it to a variable
		$backup =& $this->dbutil->backup();
		
		// Load the download helper and send the file to your desktop
		$this->load->helper('download');
		force_download('mokets.zip', $backup);
	}


}
?>