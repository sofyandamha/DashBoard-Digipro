<?php
class Moket extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('email',array(
										       	'mailtype'  => 'html',
										        	'newline'   => '\r\n'
												));
	}

	//Function for set up LDAP btn.co.id
	 function login_user()
    {  
		if(isset($_POST['username']) && isset($_POST['password'])){
			$adServer = "ldap://domainname.com";
			
			$ldap = ldap_connect($adServer);
			$username = $_POST['username'];
			$password = $_POST['password'];

			$ldaprdn = 'domainname' . "\\" . $username;

			ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

			$bind = @ldap_bind($ldap, $ldaprdn, $password);

			if ($bind) {
				$filter="(sAMAccountName=$username)";
				$result = ldap_search($ldap,"dc=MYDOMAIN,dc=COM",$filter);
				ldap_sort($ldap,$result,"sn");
				$info = ldap_get_entries($ldap, $result);
				for ($i=0; $i<$info["count"]; $i++)
				{
					if($info['count'] > 1)
						break;
					echo "<p>You are accessing <strong> ". $info[$i]["sn"][0] .", " . $info[$i]["givenname"][0] ."</strong><br /> (" . $info[$i]["samaccountname"][0] .")</p>\n";
					echo '<pre>';
					var_dump($info);
					echo '</pre>';
					$userDn = $info[$i]["distinguishedname"][0]; 
				}
				@ldap_close($ldap);
				
				echo 'Authentication Succed';
			} 
			else {
				
				echo 'Authentication Failed';
			}

		}
    }
    function success()
	{
		$this->load->view('reset/success');
	}
	
	function login()
	{
		if ($this->user->is_logged_in()) {
			redirect(site_url());
		} else {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$user_name = htmlentities($this->input->post('user_name'));
				$user_password = htmlentities($this->input->post('user_pass'));
				if ($this->user->login($user_name,$user_password)) {
					if($this->session->userdata('is_shop_admin')) {
						redirect(site_url() . "/dashboard/index/" . $this->session->userdata('allow_shop_id'));
						//redirect(site_url() . "/dashboard/index/" . $this->session->userdata('allow_shop_id'));
					} else {
						redirect(site_url());
					}
					
				} else {
					$this->session->set_flashdata('error','Username and password do not match.');
					redirect(site_url('login'));
				}
			} else {
				$this->load->view('login');	
			}
		}
	}

	function logout()
	{
		$this->user->logout();
	}
	
	function reset($code = false)
	{
		if (!$code || !$this->code->exists(array('code'=>$code))) {
			redirect(site_url('errors/error_404'));
		}
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$code = $this->code->get_by_code($code);
			if ($code->is_systemuser == 1) {
				$data = array(
								'user_pass' => md5($this->input->post('password'))
							);
				if ($this->user->update_profile($data,$code->user_id)) {
					$this->code->delete($code->user_id);
					$this->session->set_flashdata('success','Password is successfully reset.');
					redirect(site_url('moket/success'));
				}
			} else {
				$data = array(
								'password' => md5($this->input->post('password'))
							);
				if ($this->appuser->save($data,$code->user_id)) {
					$this->code->delete($code->user_id);
					$this->session->set_flashdata('success','Password is successfully reset.');
					redirect(site_url('moket/success'));
				}
			}
		}
		
		$data['code'] = $code;
		$this->load->view('reset/reset',$data);
	}
	
	function forgot()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$email = htmlentities($this->input->post('user_email'));
			$user = $this->user->get_info_by_email($email);
			
			if ($user->user_id == "") {
				$this->session->set_flashdata('error','Email does not exist in the system.');
			} else {
				$code = md5(time().'teamps');
				$data = array(
								'user_id'=>$user->user_id,
								'code'=> $code,
								'is_systemuser'=>1
								);
				if ($this->code->save($data,$user->user_id)) {
					$sender_email = $this->config->item('sender_email');
					$sender_name = $this->config->item('sender_name');
					$to = $user->user_email;
				   $subject = 'Password Reset';
					$html = "<p>Hi,".$user->user_name."</p>".
								"<p>Please click the following link to reset your password<br/>".
								"<a href='".site_url('reset/'.$code)."'>Reset Password</a></p>".
								"<p>Best Regards,<br/>".$sender_name."</p>";
								
					$this->email->from($sender_email,$sender_name);
					$this->email->to($to); 
					$this->email->subject($subject);
					$this->email->message($html);	
					$this->email->send();
					
					$this->session->set_flashdata('success','Password reset email already sent!');
					redirect(site_url('login'));
				} else {
					$this->session->set_flashdata('error','System error occured. Please contact your system administrator.');
				}
			}
		}
		
		$this->load->view('reset/forgot');
	}
}
?>