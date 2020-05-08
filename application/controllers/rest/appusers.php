<?php 
require_once(APPPATH.'/libraries/REST_Controller.php');

class Appusers extends REST_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('email',array(
       	'mailtype'  => 'html',
        	'newline'   => '\r\n'
		));
        $this->load->library('uploader');
	}
	
	function login_post()
	{
		$data = $this->post();
		
		if ($data == null) {
			$this->response(array(
				'status'=>'error',
				'data'	=>'Invalid JSON')
			);
		}
			
		if (!array_key_exists('email', $data)) {
			$this->response(array(
				'status'=>'error',
				'data'	=>'Required Email')
			);
		}
		
		if (!array_key_exists('password', $data)) {
			$this->response(array(
				'status'=>'error',
				'data'	=>'Required Password')
			);
		}
		
		if ($user = $this->appuser->login($data['email'],$data['password'])) {
			$this->response(array(
				'status'=>'success',
				'data'	=>$user)
			);
		} else {
			$this->response(array(
				'status'=>'error',
				'data'	=>'Your login credential is wrong.')
			);
		}
	}
	
	function reset_post()
	{
		$email = $this->post('email');
		if (!$email) {
			$this->response(array(
				'status'=>'error',
				'data'	=>'Required Email')
			);
		}
		
		$appuser = $this->appuser->get_info_by_email($email);
		if ($appuser->id == "") {
			$this->response(array(
				'status'=>'error',
				'data'	=>'Your email is not exist in the system.')
			);
		}
		
		$code = md5(time().'teamps');
		
		$data = array(
			'user_id'=>$appuser->id,
			'code'=> $code
		);
		// print_r($data);
		// die();
		
		if ($this->code->save($data,$appuser->id)) {
			$sender_email = $this->config->item('sender_email');
			$sender_name = $this->config->item('sender_name');
            $user_password=$this->config->item('sender_password');

            $config = Array(
                'protocol' => 'smtp',
                'smtp_host' => 'tls://smtp.gmail.com',
                'smtp_port' => 587,
                'smtp_user' => $sender_name,
                'smtp_pass' => $user_password,
                'mailtype'  => 'html',
                'charset'   => 'iso-8859-1',
                'smtp_crypto'=>'tls'
            );
            $this->load->library('email', $config);
            $this->email->set_newline("\r\n");
            

			$to = $appuser->email;
		    $subj= 'Password Reset';
			$html = "<p>Hi,".$appuser->username."</p>".
						"<p>Please click the following link to reset your password<br/>".
						"<a href='".site_url('reset/'.$code)."'>Reset Password</a></p>".
						"<p>Best Regards,<br/>".$sender_name."</p>";
						
			$this->email->from($sender_email,$sender_name);
			$this->email->to($to); 
			$this->email->subject($subj);
			$this->email->message($html);	
			$this->email->send();

            if ($this->email->send()) {
               $data['message_display'] = 'Email Successfully Send ! '.$sender_email.' pass '.$user_password." to ".$to;
            } else {
                $data['message_display'] = 'error = '.$this->email->send()
                    ." - ".$sender_email.' pass '.$user_password." to ".$to;
            }
			
			$this->response(array(
				'status'=>'success',
				'data'	=>'Password reset email already sent! ')
			);
		} else {
			$this->response(array(
				'status'=>'error',
				'data'	=>'Oops! System could not manage for your request. Please try again later.')
			);
		}
	}
    function addnew_post()
	{
		$data = $this->post();
				
				if ($data == null) {
					$this->response(array(
						'status'=>'error',
						'data'	=> 'Invalid JSON')
					);
					
				}
        //todo nanti diseusiakan jika bermasalah dengan web
                if ($this->appuser->exists($data)) {
                    $this->response(array(
                            'status'=>'error',
                            'data'	=> 'id card sudah terdaftar')
                    );
                }

				
				if (!array_key_exists('username', $data)) {
					$this->response(array(
						'status'=>'error',
						'data'	=> 'Required Username')
					);
				}
					
				if (!array_key_exists('email', $data)) {
					$this->response(array(
						'status'=>'error',
						'data'	=> 'Required Email')
					);
				}
				
				if (!array_key_exists('password', $data)) {
					$this->response(array(
						'status'=>'error',
						'data'	=> 'Required Password')
					);
				}

                if (!array_key_exists('delivery_address', $data)) {
                    $data['delivery_address']="";
                }
                if (!array_key_exists('billing_address', $data)) {
                    $data['billing_address']="";
                }


                if (!array_key_exists('id_card', $data)) {
                    $data['id_card']="";
                }



                if (!array_key_exists('phone', $data)) {
                    $data['phone']="";
                }
        /*$user_data['phone']    = $data['phone'];
        $user_data['delivery_address'] = $data['delivery_address'];
        $user_data['billing_address']  = $data['billing_address'];*/
        if (!array_key_exists('profile_photo', $data)) {
            $data['profile_photo']='default_user_profile.png';
        }

        if (!array_key_exists('dob', $data)) {
            $data['dob']='none';
        }
        //if($data['profile_photo'])
				
				$user_data = array(
					'username'      => $data['username'],
					'password'      => md5($data['password']),
					'email'         => $data['email'],
					// 'about_me'      => $data['about_me'],
					// 'profile_photo' => $data['profile_photo'],
                    // 'delivery_address'=>$data['delivery_address'],
                    // 'billing_address'=>$data['billing_address'],
                    // 'dob'=>$data['dob'],
                    'id_card'=>$data['id_card'],
                    'phone' => $data['phone']
                    // ,
                    // 'gender' => $data['gender']
				);
		
				if ($this->appuser->exists($user_data)) {
					$this->response(array(
						'status'=>'error',
						'data'	=> 'Email already exist or id card exist')
					);
				} else {
					$this->appuser->save($user_data);
					//langsung di liginkan untuk dpat data user
                    if ($user = $this->appuser->login($data['email'],$data['password'])) {
                        $this->response(array(
                                'status'=>'success',
                                'data'	=>$user_data['id'],
                                'all_data'=>$user
                                )
                        );
                    }else{
                        $this->response(array(
                                'status'=>'success',
                                'data'	=> $user_data['id'] )
                        );
                    }

				}
    }
    
	function add_post()
	{
		$data = $this->post();
				
				if ($data == null) {
					$this->response(array(
						'status'=>'error',
						'data'	=> 'Invalid JSON')
					);
					
				}
        //todo nanti diseusiakan jika bermasalah dengan web
                if ($this->appuser->exists($data)) {
                    $this->response(array(
                            'status'=>'error',
                            'data'	=> 'id card sudah terdaftar')
                    );
                }

				
				if (!array_key_exists('username', $data)) {
					$this->response(array(
						'status'=>'error',
						'data'	=> 'Required Username')
					);
				}
					
				if (!array_key_exists('email', $data)) {
					$this->response(array(
						'status'=>'error',
						'data'	=> 'Required Email')
					);
				}
				
				if (!array_key_exists('password', $data)) {
					$this->response(array(
						'status'=>'error',
						'data'	=> 'Required Password')
					);
				}

                if (!array_key_exists('delivery_address', $data)) {
                    $data['delivery_address']="";
                }
                if (!array_key_exists('billing_address', $data)) {
                    $data['billing_address']="";
                }


                if (!array_key_exists('id_card', $data)) {
                    $data['id_card']="";
                }



                if (!array_key_exists('phone', $data)) {
                    $data['phone']="";
                }
        /*$user_data['phone']    = $data['phone'];
        $user_data['delivery_address'] = $data['delivery_address'];
        $user_data['billing_address']  = $data['billing_address'];*/
        if (!array_key_exists('profile_photo', $data)) {
            $data['profile_photo']='default_user_profile.png';
        }

        if (!array_key_exists('dob', $data)) {
            $data['dob']='none';
        }
        //if($data['profile_photo'])
				
				$user_data = array(
					'username'      => $data['username'],
					'password'      => md5($data['password']),
					'email'         => $data['email'],
					// 'about_me'      => $data['about_me'],
					// 'profile_photo' => $data['profile_photo'],
                    // 'delivery_address'=>$data['delivery_address'],
                    // 'billing_address'=>$data['billing_address'],
                    // 'dob'=>$data['dob'],
                    'id_card'=>$data['id_card'],
                    'phone' => $data['phone']
                    // ,
                    // 'gender' => $data['gender']
				);
		
				if ($this->appuser->exists($user_data)) {
					$this->response(array(
						'status'=>'error',
						'data'	=> 'Email already exist or id card exist')
					);
				} else {
					$this->appuser->save($user_data);
					//langsung di liginkan untuk dpat data user
                    if ($user = $this->appuser->login($data['email'],$data['password'])) {
                        $this->response(array(
                                'status'=>'success',
                                'data'	=>$user_data['id'],
                                'all_data'=>$user
                                )
                        );
                    }else{
                        $this->response(array(
                                'status'=>'success',
                                'data'	=> $user_data['id'] )
                        );
                    }

				}
	}

    function upload_post($item_id=0)
    {


        $upload_data = $this->uploader->upload($_FILES);

        if (!isset($upload_data['error'])) {
            $this->response(array(
                    'status'=>'success',
                    'data'	=>'$upload_data',
                    'upload_data'=>$upload_data
                )
            );
            /*
            foreach ($upload_data as $upload) {
                $image = array(
                    'item_id'=>$item_id,
                    'path' => $upload['file_name'],
                    'width'=>$upload['image_width'],
                    'height'=>$upload['image_height']
                );
                $this->image->_reset_write();
                if($this->image->save($image)){
                    $this->response(array(
                            'status'=>'success',
                            'data'	=>$image,
                            'upload_data'=>$upload_data
                        )
                    );
                }
            }
            */
        } else {
            $data['error'] = $upload_data['error'];
            $this->response(array(
                    'status'=>'error',
                    'data'	=>$data['error'])
            );
        }

        //$data['item'] = $this->item->get_info($item_id);
    }
	
	function update_put()
	{
		
		$data = $this->put();
		//print_r($data);


		
		if ( !$data['platformName'] ) {
			$this->response(array(
				'status'=>'error',
				'data'	=> 'Required Platform ='.$data['platformName'])
			);
		};
		
		if ($data['platformName'] == "android" || $data['platformName'] == "web" || $data['platformName'] == "ios" ) {
		
			$id = $this->get('id');
			if (!$id) {
				$this->response(array(
					'status'=>'error',
					'data'	=> 'Required ID')
				);
			}
			
			$data = $this->put();
			if ($data == null) {
				$this->response(array(
					'status'=>'error',
					'data'	=> 'Invalid JSON')
				);
			}
			
			$user_data = $data;
			$user_data['id'] = $id;
			if (array_key_exists('password',$data)) {
				$user_data['password'] = md5($data['password']);
			}
			
			if (array_key_exists('email',$data)) {
				if (strtolower($this->appuser->get_info($id)->email) != strtolower($user_data['email'])) {
					$cond = array('email'=>strtolower($user_data['email']));
					
					if ($this->appuser->exists($cond)) {
						$this->response(array(
							'status'=>'error',
							'data'	=> 'Email already exist')
						);
					}
				}
			}


            if(array_key_exists('co',$data)){
                $update_userdata['co']   = $user_data['co'];
                $update_userdata['online']   = 0;
            }else  if(array_key_exists('ci',$data)){
                $update_userdata['ci']   = $user_data['ci'];
                $update_userdata['online']   = 1;
            }
			else if(array_key_exists('poin',$data)){
                $update_userdata['poin']   = $user_data['poin'];
            }
            else if(array_key_exists('qr_code',$data)){
                $update_userdata['qr_code']   = $user_data['qr_code'];
            }else{
                if($user_data['password'] == "") {
                    $update_userdata['username']    = $user_data['username'];
                    $update_userdata['email']       = $user_data['email'];
                    $update_userdata['about_me']    = $user_data['about_me'];
                    $update_userdata['phone']             = $user_data['phone'];
                    $update_userdata['delivery_address']  = $user_data['delivery_address'];
                    $update_userdata['billing_address']   = $user_data['billing_address'];
                    //tambahan qrcode
                    $update_userdata['id_card']   = $user_data['id_card'];
                    $update_userdata['profile_photo']=$user_data['profile_photo'];
                    // $update_userdata['gender']=$user_data['gender'];


                } else {
                    $update_userdata['password']    = $user_data['password'];
                //				$update_userdata['username']    = $user_data['username'];
                //				$update_userdata['email']       = $user_data['email'];
                //				$update_userdata['about_me']    = $user_data['about_me'];

                }
            }

			
			//var_dump($user_data); die;
			$this->appuser->save($update_userdata,$id);
			$this->response(array(
				'status'=>'success',
				'data'	=> 'User profile is successfully updated')
			);
			
		
		} else {
		
			$id = $this->get('id');
			if (!$id) {
				$this->response(array(
					'status'=>'error',
					'data'	=> 'Required ID')
				);
			}
			
			$data = $this->put();
			if ($data == null) {
				$this->response(array(
					'status'=>'error',
					'data'	=> 'Invalid JSON')
				);
			}
			
			//$user_data = $data;
			$user_data['id'] = $id;
			if (array_key_exists('password',$data)) {
				$user_data['password'] = md5($data['password']);
			}
			
			$user_data['username'] = $data['username'];
			$user_data['about_me'] = $data['about_me'];
			$user_data['email']    = $data['email'];
			$user_data['phone']    = $data['phone'];
			$user_data['delivery_address'] = $data['delivery_address'];
			$user_data['billing_address']  = $data['billing_address'];
			
			
			$this->appuser->save($user_data,$id);
			$this->response(array(
				'status'=>'success',
				'data'	=> 'User profile is successfully updated.')
			);
		
		}
		
	}

	function get_post(){

        $data = $this->post();
        $id = $this->post('id');

        if (!array_key_exists('id', $data)) {
            $this->response(array(
                    'status'=>'error',
                    'data'	=> 'Required id')
            );
        }
        //get_multiple_info

        $data_res=$this->appuser->get_info($id);
        /*echo "id ".$id;
        $data_res=json_encode($data_res);
        print_r( $data_res);
        exit;*/
			$this->response(array(
                    'status'=>'success',
                    'data'	=>$data_res )
            );
    }

    function get_email_post(){

        $data = $this->post();
        $email = $this->post('email');

        if (!array_key_exists('email', $data)) {
            $this->response(array(
                    'status'=>'error',
                    'data'	=> 'Required id')
            );
        }
        //get_multiple_info

        $data_res=$this->appuser->get_info_by_email($email);
        /*echo "id ".$id;
        $data_res=json_encode($data_res);
        print_r( $data_res);
        exit;*/
        $this->response(array(
                'status'=>'success',
                'data'	=>$data_res )
        );
    }

    function get_all_get(){

        $data = $this->get();
        $id = $this->get('shop_id');

        if (!array_key_exists('shop_id', $data)) {
            $this->response(array(
                    'status'=>'error',
                    'data'	=> 'Required id')
            );
        }
        //get_multiple_info

        $data_res=$this->appuser->get_all_shop();
//        print_r($data_res);
//        exit();
        /*echo "id ".$id;
        $data_res=json_encode($data_res);
        print_r( $data_res);
        exit;*/
        $this->response(array(
                'status'=>'success',
                'data'	=>$data_res )
        );
    }

    function get_all_recent(){

        $data = $this->get();
        $id = $this->get('shop_id');

        if (!array_key_exists('shop_id', $data)) {
            $this->response(array(
                    'status'=>'error',
                    'data'	=> 'Required id')
            );
        }
        //get_multiple_info

        $data_res=$this->appuser->get_all_shop();
//        print_r($data_res);
//        exit();
        /*echo "id ".$id;
        $data_res=json_encode($data_res);
        print_r( $data_res);
        exit;*/
        $this->response(array(
                'status'=>'success',
                'data'	=>$data_res )
        );
    }

    function poin_get(){

        $id = $this->get('id');
        // $id = $this->get();
        // print_r($id);
        // die();
        // if (!array_key_exists('id', $data)) {
        //     $this->response(array(
        //             'status'=>'error',
        //             'data'	=> 'Required id')
        //     );
        // }
        $this->load->model('rating');
        $data=$this->rating->get_poin_by_id($id);
        // print_r($data);
        // die();
        $this->response(array(
                'status'=>'success',
                'data'	=>  $data->total_poin !== null ? $data->total_poin : 0 )
        );
    }

    function notification_get(){

        $id = $this->get('id');
        $this->load->model('mk_notification_historys');
        $data=$this->mk_notification_historys->appuserid($id);
        $this->response(array(
                'status'=>count($data) > 0 ? true : false,
                'data'	=> count($data) > 0 ? $data : 0 )
        );
    }

    public function logout_post(){
      $id = $this->post('id');
	  $data = $this->appuser->logout($id);
	  $this->response(array(
			'status'=>'Succes',
			'data'	=> $data
		));
	}
}
?>