<?php 
require_once(APPPATH.'/libraries/REST_Controller.php');

class Transactions extends REST_Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->load->library('email',array(
       	'mailtype'  => 'html',
        	'newline'   => '\r\n'
		));
		
	}

	function add_post()
	{
		
		$send_user = false;
		$send_shop = false;
		
        $data = $this->post('orders');

        try{
            $user_id=$this->post('user_id');
        }catch(Exception $e){
            $this->response(array(
                
                'status'=> 'error : '.$e,
                'data'	=> 'invalid_data user id not found')
            );
        }

        //login dan logut dengan params ststus masuk ke data payment_trans_id
        try{
            $item_id="655";
            $item_name="keluar";
            $status=$this->post('status');
            //654 msuk 655 id keluar
            date_default_timezone_set('Asia/Jakarta'); # add your city to set local time zone
            $now = date('Y-m-d H:i:s');
            if ($status == "1" ){
                //masuk
                $item_id="654";
                $item_name="masuk";
                $update_userdata['ci']   = $now;
                $update_userdata['online']   = 1;
                
            }else{
                //keluar
                $item_id="655";
                $item_name="keluar";
                $update_userdata['co']   = $now;
                $update_userdata['online']   = 0;
            }
        }catch(Exception $e){
            $this->response(array(
                
                'status'=> 'error : '.$e,
                'data'	=> 'invalid_data user id not found')
            );
        }

        

		$decode_data_array = json_decode($data);
		
		if(count($decode_data_array)>0) {
            if (!array_key_exists('shipping_method', $decode_data_array[0])) {
                $shipping_method="flat.flat";
            }else{
                $shipping_method=$decode_data_array[0]->shipping_method;
            }
		
			if($decode_data_array[0]->payment_method == "stripe") {
				
				//For Transaction Header
				$header_data = array(
					'shop_id'           => $decode_data_array[0]->shop_id,
					'user_id'           => $decode_data_array[0]->user_id,
					'payment_trans_id'  => $decode_data_array[0]->payment_trans_id,
					'delivery_address'  => $decode_data_array[0]->delivery_address,
					'billing_address'   => $decode_data_array[0]->billing_address,
					'total_amount'      => $decode_data_array[0]->total_amount,
					'transaction_status'=> 2,
					'email'             => $decode_data_array[0]->email,
					'phone'             => $decode_data_array[0]->phone,
					'payment_method'    => $decode_data_array[0]->payment_method,
                    'shipping_method'    => $shipping_method
				);
				$this->transaction_header->save($header_data);
				$transaction_header_id = $header_data['id'];
				
				//For Transaction Detail
				for($i=0;$i<count($decode_data_array);$i++) 
				{
									
					$detail_data = array(
						'transaction_header_id' => $transaction_header_id,
						'shop_id'               => $decode_data_array[$i]->shop_id,
						'item_id'               => $decode_data_array[$i]->item_id,
						'item_name'             => $decode_data_array[$i]->name,
						'item_attribute'        => $decode_data_array[$i]->basket_item_attribute,
						'unit_price'            => $decode_data_array[$i]->unit_price,
						'qty'                   => $decode_data_array[$i]->qty,
						'discount_percent'      => $decode_data_array[$i]->discount_percent		
					);
					$this->transaction_detail->save($detail_data);				
				}
				
				//$this->response(array('success'=>'Order is successfully inserted.'));
				$user_id          = $decode_data_array[0]->user_id;
				$payment_method   = "stripe";
				$shop_id          = $decode_data_array[0]->shop_id;
				$delivery_address = $decode_data_array[0]->delivery_address;
				$billing_address  = $decode_data_array[0]->billing_address;
				$email            = $decode_data_array[0]->email;
				$phone            = $decode_data_array[0]->phone;
				
				
				if($this->send_email_to_user($user_id,$payment_method,$shop_id,$transaction_header_id,$decode_data_array))
				{
					$send_user = true;
				}
				
				if($this->send_email_to_shop($user_id,$payment_method,$shop_id,$delivery_address,$billing_address,$email,$phone,$transaction_header_id,$decode_data_array))
				{
					$send_shop = true;
				}
				
                //				$this->response(array(
                //					'status'=>'success',
                //					'data'	=> "Order is successfully inserted.")
                //				);

				if($send_user && $send_shop) {
				
					$this->response(array(
						'status'=>'success',
						'data'	=> "Order is successfully inserted. Email successfully sent to User and Shop",
                        'transaction_header_id' => $transaction_header_id,
                            'user_id' => $user_id,
                            'payment_method'=> $payment_method,
                            'shop_id' =>$shop_id,
                            'delivery_address' => $delivery_address,
                            'billing_address' => $billing_address,
                            'email' => $email,
                            'phone' => $phone,
                            'decode_data_array'=>$decode_data_array


                        )
					);
					//Order is successfully inserted.
				} else if($send_user && !$send_shop){

					$this->response(array(
						'status'=>'success',
						'data'	=> "Order is successfully inserted. Email successfully sent to User Only",
                        'transaction_header_id' => $transaction_header_id,
                            'user_id' => $user_id,
                            'payment_method'=> $payment_method,
                            'shop_id' =>$shop_id,
                            'delivery_address' => $delivery_address,
                            'billing_address' => $billing_address,
                            'email' => $email,
                            'phone' => $phone,
                            'decode_data_array'=>$decode_data_array
                        )
					);
					//Order is successfully submitted but email cannot send to shop.
				} else if(!$send_user && $send_shop){

					$this->response(array(
						'status'=>'success',
						'data'	=> "Order is successfully inserted. Email successfully sent to Shop Only",
                            'transaction_header_id' => $transaction_header_id,
                            'user_id' => $user_id,
                            'payment_method'=> $payment_method,
                            'shop_id' =>$shop_id,
                            'delivery_address' => $delivery_address,
                            'billing_address' => $billing_address,
                            'email' => $email,
                            'phone' => $phone,
                            'decode_data_array'=>$decode_data_array

                            )
					);
					//Order is successfully submitted but email cannot send to user.
				} else if(!$send_user && !$send_shop){

					$this->response(array(
						'status'=>'success',
						'data'	=> "Order is successfully inserted.But Email cannot send to both User and Shop. Please check sender email account from Shop Setting.",
                            'transaction_header_id' => $transaction_header_id,
                            'user_id' => $user_id,
                            'payment_method'=> $payment_method,
                            'shop_id' =>$shop_id,
                            'delivery_address' => $delivery_address,
                            'billing_address' => $billing_address,
                            'email' => $email,
                            'phone' => $phone,
                            'decode_data_array'=>$decode_data_array
                            )
					);
					//Order is successfully submitted but email cannot send to both user and shop.
				} else {
					//$this->response(array('error'=>'order_submit_error'));
					$this->response(array(
						'status'=>'error',
						'data'	=> "order_submit_error")
					);
				}
				
			
			}
			else if($decode_data_array[0]->payment_method == "cod" || $decode_data_array[0]->payment_method == "bank")
			{
				
				
				
				
				$payment_method   = $decode_data_array[0]->payment_method;
				$shop_id          = $decode_data_array[0]->shop_id;
				$delivery_address = $decode_data_array[0]->delivery_address;
				$billing_address  = $decode_data_array[0]->billing_address;
				$email            = $decode_data_array[0]->email;
				$phone            = $decode_data_array[0]->phone;
				$user_id          = $decode_data_array[0]->user_id;
				//For Transaction Header
				$header_data = array(
					'shop_id'           => $decode_data_array[0]->shop_id,
					'user_id'           => $decode_data_array[0]->user_id,
					'payment_trans_id'  => $decode_data_array[0]->payment_trans_id,
					'delivery_address'  => $decode_data_array[0]->delivery_address,
					'billing_address'   => $decode_data_array[0]->billing_address,
					'total_amount'      => $decode_data_array[0]->total_amount,
					'transaction_status'=> 1,
					'email'             => $decode_data_array[0]->email,
					'phone'             => $decode_data_array[0]->phone,
					'payment_method'    => $decode_data_array[0]->payment_method,
                    'shipping_method'    => $shipping_method
                );
				$this->transaction_header->save($header_data);
				$transaction_header_id = $header_data['id'];
				
				//For Transaction Detail
				for($i=0,$size=count($decode_data_array);$i<$size;$i++)
				{
									
					$detail_data = array(
						'transaction_header_id' => $transaction_header_id,
						'shop_id'               => $decode_data_array[$i]->shop_id,
						'item_id'               => $decode_data_array[$i]->item_id,
						'item_name'             => $decode_data_array[$i]->name,
						'item_attribute'        => $decode_data_array[$i]->basket_item_attribute,
						'unit_price'            => $decode_data_array[$i]->unit_price,
						'qty'                   => $decode_data_array[$i]->qty,
						'discount_percent'      => $decode_data_array[$i]->discount_percent		
					);

                    $detail_data_stock = array(

                        'stock'                   => $decode_data_array[$i]->qty,
                    );


                    //ori
					$this->transaction_detail->save($detail_data);

                    //reserve stock sementara nanti di kurangi dari admin ketika status complete
                    //harus setelah data transaction tersave di table transaction
                    //$stock_substarct=$decode_data_array[$i]->qty;
                    //$item_id=$decode_data_array[$i]->item_id;
                    //if($item_id[$i]==)
                    //$res_test[$i]=
                    if($i!=$size){
                        $this->item->substract_stock
                        ($detail_data_stock,$decode_data_array[$i]->item_id,$decode_data_array[$i]->qty,$transaction_header_id);
                    }


				}


				$count=count($decode_data_array);

				
				
				if($this->send_email_to_user($user_id,$payment_method,$shop_id,$transaction_header_id,$decode_data_array))
				{
					$send_user = true;
				}
				
				if($this->send_email_to_shop($user_id,$payment_method,$shop_id,$delivery_address,$billing_address,$email,$phone,$transaction_header_id,$decode_data_array))
				{
					$send_shop = true;
				}
                //buat test smtp
                $shop = $this->shop->get_info($shop_id);
                //end test
				
				if($send_user && $send_shop) {

					$this->response(array(
						'status'=>'success',
						'data'	=> "Order is successfully inserted. Email successfully sent to User and Shop",
                            'transaction_header_id' => $transaction_header_id,
                            'user_id' => $user_id,
                            'payment_method'=> $payment_method,
                            'shop_id' =>$shop_id,
                            'delivery_address' => $delivery_address,
                            'billing_address' => $billing_address,
                            'email' => $email,
                            'phone' => $phone,
                            'decode_data_array'=>$decode_data_array
                            )
					);
					//Order is successfully inserted.
				}
				else if($send_user && !$send_shop){

					$this->response(array(
						'status'=>'success',
						'data'	=> "Order is successfully inserted. Email successfully sent to User Only",
                            'transaction_header_id' => $transaction_header_id,
                            'user_id' => $user_id,
                            'payment_method'=> $payment_method,
                            'shop_id' =>$shop_id,
                            'delivery_address' => $delivery_address,
                            'billing_address' => $billing_address,
                            'email' => $email,
                            'phone' => $phone,
                            'decode_data_array'=>$decode_data_array
                            )
					);
					//Order is successfully submitted but email cannot send to shop.
				}
				else if(!$send_user && $send_shop){

					$this->response(array(
						'status'=>'success',
						'data'	=> "Order is successfully inserted. Email successfully sent to Shop Only",
                            'transaction_header_id' => $transaction_header_id,
                            'user_id' => $user_id,
                            'payment_method'=> $payment_method,
                            'shop_id' =>$shop_id,
                            'delivery_address' => $delivery_address,
                            'billing_address' => $billing_address,
                            'email' => $email,
                            'phone' => $phone,
                            'decode_data_array'=>$decode_data_array
                            )
					);
					//Order is successfully submitted but email cannot send to user.
				}
				else if(!$send_user && !$send_shop){

					$this->response(array(
						'status'=>'success',
						'data'	=> "Order is successfully inserted.But Email cannot send to both User and Shop. Please check sender email account from Shop Setting.".$shop->sender_email,
                            'transaction_header_id' => $transaction_header_id,
                            'user_id' => $user_id,
                            'payment_method'=> $payment_method,
                            'shop_id' =>$shop_id,
                            'delivery_address' => $delivery_address,
                            'billing_address' => $billing_address,
                            'email' => $email,
                            'phone' => $phone,
                            'decode_data_array'=>$decode_data_array
                            )

					);

					//Order is successfully submitted but email cannot send to both user and shop.
				}
				else {
					//$this->response(array('error'=>'order_submit_error'));
					$this->response(array(
						'status'=>'error',
						'data'	=> "order_submit_error")
					);
				}





				
            }
            
			
		} else {
            //$this->response(array('error' => array('message' => 'invalid_data')));
            if($data == "btn"){

                $payment_method   = "btn";//$decode_data_array[0]->payment_method;
				$shop_id          = "2";//$decode_data_array[0]->shop_id;
				$delivery_address = "museuem btn";//$decode_data_array[0]->delivery_address;
				$billing_address  = "museum btn users";//$decode_data_array[0]->billing_address;
				$email            = "none";//$decode_data_array[0]->email;
				$phone            = "none";//$decode_data_array[0]->phone;
				$user_id          = $user_id;//$decode_data_array[0]->user_id;
				//For Transaction Header
				$header_data = array(
					'shop_id'           => $shop_id,
					'user_id'           => $user_id,
					'payment_trans_id'  => "none",
					'delivery_address'  => $delivery_address,
					'billing_address'   => $billing_address,
					'total_amount'      => "0",
					'transaction_status'=> 1,
					'email'             => $email,
					'phone'             => $phone,
					'payment_method'    => $payment_method,
                    'shipping_method'    => "flat.flat"
                );
				$this->transaction_header->save($header_data);
				$transaction_header_id = $header_data['id'];
				
				//For Transaction Detail
				// for($i=0,$size=count($decode_data_array);$i<$size;$i++)
				// {
									
					$detail_data = array(
						'transaction_header_id' => $transaction_header_id,
						'shop_id'               => $shop_id,
						'item_id'               => $item_id,//$decode_data_array[$i]->item_id,
						'item_name'             => $item_name,
						'item_attribute'        => "none",
						'unit_price'            => "0",
						'qty'                   => "1",
						'discount_percent'      => "none"	
					);

                    $detail_data_stock = array(

                        'stock'                   => "1",
                    );


                    //ori
                    $this->transaction_detail->save($detail_data);
                    
                    //update ststus online offline ketika masuk dan keluar gate
                    $this->appuser->save($update_userdata,$user_id);

                    //reserve stock sementara nanti di kurangi dari admin ketika status complete
                    //harus setelah data transaction tersave di table transaction
                    //$stock_substarct=$decode_data_array[$i]->qty;
                    //$item_id=$decode_data_array[$i]->item_id;
                    //if($item_id[$i]==)
                    //$res_test[$i]=
                    if($i!=$size){
                        $this->item->substract_stock
                        ($detail_data_stock,$decode_data_array[$i]->item_id,$decode_data_array[$i]->qty,$transaction_header_id);
                    }


				//}


				$count=count($decode_data_array);

				
				
				if($this->send_email_to_user($user_id,$payment_method,$shop_id,$transaction_header_id,$decode_data_array))
				{
					$send_user = true;
				}
				
				if($this->send_email_to_shop($user_id,$payment_method,$shop_id,$delivery_address,$billing_address,$email,$phone,$transaction_header_id,$decode_data_array))
				{
					$send_shop = true;
				}
                //buat test smtp
                $shop = $this->shop->get_info($shop_id);
                //end test
				
				if($send_user && $send_shop) {

					$this->response(array(
						'status'=>'success',
						'data'	=> "Order is successfully inserted. Email successfully sent to User and Shop",
                            'transaction_header_id' => $transaction_header_id,
                            'user_id' => $user_id,
                            'payment_method'=> $payment_method,
                            'shop_id' =>$shop_id,
                            'delivery_address' => $delivery_address,
                            'billing_address' => $billing_address,
                            'email' => $email,
                            'phone' => $phone,
                            'decode_data_array'=>$decode_data_array
                            )
					);
					//Order is successfully inserted.
				}
				else if($send_user && !$send_shop){

					$this->response(array(
						'status'=>'success',
						'data'	=> "Order is successfully inserted. Email successfully sent to User Only",
                            'transaction_header_id' => $transaction_header_id,
                            'user_id' => $user_id,
                            'payment_method'=> $payment_method,
                            'shop_id' =>$shop_id,
                            'delivery_address' => $delivery_address,
                            'billing_address' => $billing_address,
                            'email' => $email,
                            'phone' => $phone,
                            'decode_data_array'=>$decode_data_array
                            )
					);
					//Order is successfully submitted but email cannot send to shop.
				}
				else if(!$send_user && $send_shop){

					$this->response(array(
						'status'=>'success',
						'data'	=> "Order is successfully inserted. Email successfully sent to Shop Only",
                            'transaction_header_id' => $transaction_header_id,
                            'user_id' => $user_id,
                            'payment_method'=> $payment_method,
                            'shop_id' =>$shop_id,
                            'delivery_address' => $delivery_address,
                            'billing_address' => $billing_address,
                            'email' => $email,
                            'phone' => $phone,
                            'decode_data_array'=>$decode_data_array
                            )
					);
					//Order is successfully submitted but email cannot send to user.
				}
				else if(!$send_user && !$send_shop){

					$this->response(array(
						'status'=>'success',
						'data'	=> "Order is successfully inserted.But Email cannot send to both User and Shop. Please check sender email account from Shop Setting.".$shop->sender_email,
                            'transaction_header_id' => $transaction_header_id,
                            'user_id' => $user_id,
                            'payment_method'=> $payment_method,
                            'shop_id' =>$shop_id,
                            'delivery_address' => $delivery_address,
                            'billing_address' => $billing_address,
                            'email' => $email,
                            'phone' => $phone,
                            'decode_data_array'=>$decode_data_array
                            )

					);

					//Order is successfully submitted but email cannot send to both user and shop.
				}
				else {
					//$this->response(array('error'=>'order_submit_error'));
					$this->response(array(
						'status'=>'error',
						'data'	=> "order_submit_error")
					);
				}

                $this->response(array(
                    'orders' => $data,
                    'status'=> 'error',
                    'data'	=> 'invalid_data')
                );
            }else{
                $this->response(array(
                    
                    'status'=> 'error',
                    'data'	=> 'invalid_data')
                );
            }
            
			
		}
//
		
		
	}


    function poin_post()
    {

        $send_user = false;
        $send_shop = false;

        $shipping_method="flat.flat";
        $payment_method = $this->post('payment_method');
        $shop_id = $this->post('shop_id');
        $item_id = $this->post('item_id');
        $name = $this->post('name');
        $delivery_address = $this->post('delivery_address');
        $billing_address = $this->post('billing_address ');
        $email = $this->post('email');
        $phone = $this->post('phone');
        $user_id = $this->post('user_id');
        $total_amount=$this->post('total_amount');
        $unit_price=$this->post('unit_price');
        $payment_trans_id="none";
        $qty=$this->post('qty');
        $basket_item_attribute="none";
        $discount_percent="none";

        $header_data = array(
            'shop_id'           => $shop_id,
            'user_id'           => $user_id,
            'payment_trans_id'  => $payment_trans_id,
            'delivery_address'  => $delivery_address,
            'billing_address'   => $billing_address,
            'total_amount'      => $total_amount,
            'transaction_status'=> 1,
            'email'             => $email,
            'phone'             => $phone,
            'payment_method'    => $payment_method,
            'shipping_method'    => $shipping_method
        );
        $this->transaction_header->save($header_data);
        $transaction_header_id = $header_data['id'];
        $detail_data_stock = array(

            'stock'                   => $qty,
        );

        $detail_data = array(
            'transaction_header_id' => $transaction_header_id,
            'shop_id'               => $shop_id,
            'item_id'               => $item_id,
            'item_name'             => $name,
            'item_attribute'        => $basket_item_attribute,
            'unit_price'            => $unit_price,
            'qty'                   => $qty,
            'discount_percent'      => $discount_percent
        );

        $this->transaction_detail->save($detail_data);
        $this->item->substract_stock
        ($detail_data_stock,$item_id,$qty,$transaction_header_id);

        if($this->send_email_to_user($user_id,$payment_method,$shop_id,$transaction_header_id,$decode_data_array))
        {
            $send_user = true;
        }

        if($this->send_email_to_shop($user_id,$payment_method,$shop_id,$delivery_address,$billing_address,$email,$phone,$transaction_header_id,$decode_data_array))
        {
            $send_shop = true;
        }
        //buat test smtp
        $shop = $this->shop->get_info($shop_id);
        //end test

        if($send_user && $send_shop) {

            $this->response(array(
                    'status'=>'success',
                    'data'	=> "Order is successfully inserted. Email successfully sent to User and Shop",
                    'transaction_header_id' => $transaction_header_id,
                    'user_id' => $user_id,
                    'payment_method'=> $payment_method,
                    'shop_id' =>$shop_id,
                    'delivery_address' => $delivery_address,
                    'billing_address' => $billing_address,
                    'email' => $email,
                    'phone' => $phone,
                    'decode_data_array'=>$decode_data_array
                )
            );
            //Order is successfully inserted.
        }
        else if($send_user && !$send_shop){

            $this->response(array(
                    'status'=>'success',
                    'data'	=> "Order is successfully inserted. Email successfully sent to User Only",
                    'transaction_header_id' => $transaction_header_id,
                    'user_id' => $user_id,
                    'payment_method'=> $payment_method,
                    'shop_id' =>$shop_id,
                    'delivery_address' => $delivery_address,
                    'billing_address' => $billing_address,
                    'email' => $email,
                    'phone' => $phone,
                    'decode_data_array'=>$decode_data_array
                )
            );
            //Order is successfully submitted but email cannot send to shop.
        }
        else if(!$send_user && $send_shop){

            $this->response(array(
                    'status'=>'success',
                    'data'	=> "Order is successfully inserted. Email successfully sent to Shop Only",
                    'transaction_header_id' => $transaction_header_id,
                    'user_id' => $user_id,
                    'payment_method'=> $payment_method,
                    'shop_id' =>$shop_id,
                    'delivery_address' => $delivery_address,
                    'billing_address' => $billing_address,
                    'email' => $email,
                    'phone' => $phone,
                    'decode_data_array'=>$decode_data_array
                )
            );
            //Order is successfully submitted but email cannot send to user.
        }
        else if(!$send_user && !$send_shop){

            $this->response(array(
                    'status'=>'success',
                    'data'	=> "Order is successfully inserted.But Email cannot send to both User and Shop. Please check sender email account from Shop Setting.".$shop->sender_email,
                    'transaction_header_id' => $transaction_header_id,
                    'user_id' => $user_id,
                    'payment_method'=> $payment_method,
                    'shop_id' =>$shop_id,
                    'delivery_address' => $delivery_address,
                    'billing_address' => $billing_address,
                    'email' => $email,
                    'phone' => $phone,
                    'decode_data_array'=>$decode_data_array
                )

            );

            //Order is successfully submitted but email cannot send to both user and shop.
        }
        else {
            //$this->response(array('error'=>'order_submit_error'));
            $this->response(array(
                    'status'=>'error',
                    'data'	=> "order_submit_error")
            );
        }


        //$this->response(array('error' => array('message' => 'invalid_data')));
        /*$this->response(array(
                'status'=> 'error',
                'data'	=> 'invalid_data')
        );*/

    }

    function transaction_all_get( $start,$conditions = array(), $limit=false, $offset=false)
    {
        $start_date=$start;
        $shop_id=$this->get('shop_id');
        $this->db->from($this->table_name);
        $this->db->where('shop_id', $shop_id);

        if ($limit) {
            $this->db->limit($limit);
        }

        if ($offset) {
            $this->db->offset($offset);
        }

        if (isset($conditions['searchterm']) && trim($conditions['searchterm']) != "") {
            $this->db->like(delivery_address, $conditions['searchterm']);
            $this->db->or_like(billing_address, $conditions['searchterm']);
        }

        if (isset($conditions['start_date']) && isset($conditions['end_date'])) {
            if($conditions['start_date'] != "" && $conditions['end_date'] != ""){
                $this->db->where('added BETWEEN "'. date('Y-m-d', strtotime($conditions['start_date'])). '" and "'. date('Y-m-d', strtotime($conditions['end_date'])).'"');
            }
        }

        $this->db->order_by('id', 'desc');

        return $this->db->get();

    }

    function search_post()
    {

        $shop_id=$this->post('shop_id');

        $conditions['start_date']=$this->post('start_date');
        $conditions['end_date']=$this->post('end_date');

        $headers = $this->transaction_header->get_all_by_date($shop_id,$conditions)->result();
//       print_r($headers);
//        exit();
        if(count($headers)>0){
            $j = 0;
            foreach ($headers as $header) {
                $tran[$j] = $this->transaction_header->get_info($header->id);
                $tran[$j]->added = $this->ago($tran[$j]->added);

                $shop = $this->shop->get_info($tran[$j]->shop_id);
                $tran[$j]->currency_symbol = html_entity_decode($shop->currency_symbol);
                $tran[$j]->currency_short_form = html_entity_decode($shop->currency_short_form);
                //
                $tran[$j]->transaction_status = $this->transaction_status->get_info($header->transaction_status)->title;
                $tran[$j]->details = $this->transaction_detail->get_all_by_header($header->id)->result();
                $j++;
            }

            $this->response(array(
                    'status'=>'success',
                    'data'	=>$tran)
            );

        } else {
            $this->response(array(
                    'status'=> 'error',
                    'data'	=> 'No Transactions')
            );
        }



        //$content['content'] = $this->load->view('transactions/search',$data,true);

        //$this->load_template($content);
    }

    function searchterm_handler($searchterm)
    {
        if ($searchterm) {
            $this->session->set_userdata('searchterm', $searchterm);
            return $searchterm;
        } elseif ($this->session->userdata('searchterm')) {
            $searchterm = $this->session->userdata('searchterm');
            return $searchterm;
        } else {
            $searchterm ="";
            return $searchterm;
        }
    }

    function transaction_get(){
	    $shop_id=$this->get('shop_id');
        $headers = $this->transaction_header->get_all_by($shop_id)->result();
        if(count($headers)>0){
            $j = 0;
            foreach ($headers as $header) {
                $tran[$j] = $this->transaction_header->get_info($header->id);
                $tran[$j]->added = $this->ago($tran[$j]->added);

                $shop = $this->shop->get_info($tran[$j]->shop_id);
                $tran[$j]->currency_symbol = html_entity_decode($shop->currency_symbol);
                $tran[$j]->currency_short_form = html_entity_decode($shop->currency_short_form);
                //
                $tran[$j]->transaction_status = $this->transaction_status->get_info($header->transaction_status)->title;
                $tran[$j]->details = $this->transaction_detail->get_all_by_header($header->id)->result();
                $j++;
            }

            $this->response(array(
                    'status'=>'success',
                    'data'	=>$tran)
            );

        } else {
            $this->response(array(
                    'status'=> 'error',
                    'data'	=> 'No Transactions')
            );
        }
    }
	
	
	function user_transactions_get()
	{
		$user_id = $this->get('user_id');
		if (!$user_id) {
			$this->response(array('error' => array('message' => 'require_user_id')));
		}
		
		$headers = $this->transaction_header->get_all_by_user($user_id)->result();
		if(count($headers)>0){
			$j = 0;
			foreach ($headers as $header) {
				$tran[$j] = $this->transaction_header->get_info($header->id);
				$tran[$j]->added = $this->ago($tran[$j]->added);
				
				$shop = $this->shop->get_info($tran[$j]->shop_id);
				$tran[$j]->currency_symbol = html_entity_decode($shop->currency_symbol);
				$tran[$j]->currency_short_form = html_entity_decode($shop->currency_short_form);
				//
				$tran[$j]->transaction_status = $this->transaction_status->get_info($header->transaction_status)->title;
				$tran[$j]->details = $this->transaction_detail->get_all_by_header($header->id)->result();
				$j++;
			}
			
			$this->response(array(
				'status'=>'success',
				'data'	=>$tran)
			);
			
		} else {
			$this->response(array(
				'status'=> 'error',
				'data'	=> 'No Transactions')
			);
		}
		
	}
	
	function ago($time)
	{
		$time = mysql_to_unix($time);
		$now = mysql_to_unix($this->category->get_now());
		
	   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	   $lengths = array("60","60","24","7","4.35","12","10");
	
	   $difference = $now - $time;
	  	$tense = "ago";
	
	   for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	       $difference /= $lengths[$j];
	   }
	
	   $difference = round($difference);
	
	   if ($difference != 1) {
	       $periods[$j].= "s";
	   }
	
	   if ($difference==0) {
	   		return "Just Now";
	   } else {
	   		return "$difference $periods[$j] ago";
	   }
	}
	
	
	function send_email_to_user($user_id,$payment_method,$shop_id,$trans_id,$order_data)
	{
		if($payment_method == "cod" || $payment_method == "stripe"){
			$appuser = $this->appuser->get_info($user_id);
			$shop = $this->shop->get_info($shop_id);
			$order_items = "";
			$total_amount = 0;
			
			for($i=0;$i<count($order_data);$i++) 
			{
				$order_items .= $i + 1 .". " . $order_data[$i]->name . 
				" (Price : " .  $order_data[$i]->unit_price . $shop->currency_symbol . 
				", QTY : " . $order_data[$i]->qty . ") <br>";
					
					$total_amount += $order_data[$i]->unit_price * $order_data[$i]->qty;
		
			}
			
			
			
			
			
			
			$trans_info = "Please take note your transaction id is " . $trans_id . " for future inquiry to the shop.";
			
			
			
			$sender_email =trim($shop->sender_email);
			$sender_name  = $shop->name;
            $user_password=$this->config->item('sender_password');
            // Configure email library
//            $config['protocol'] = 'mail';
//            $config['smtp_host'] = 'smtp.gmail.com';
//            $config['smtp_port'] = 587;
//            $config['smtp_user'] = $sender_email;
//            $config['smtp_pass'] = $user_password;
//           // $config['smtp_crypto'] = $this->config->item('smtp_crypto');
//            $config['mailtype']='html';
//            $config['charset']='iso-8859-1';
//            /*'mailtype'  => 'html',
//    'charset'   => 'iso-8859-1'*/
//            // Load email library and passing configured values to email library
//            $this->load->library('email', $config);
//			$to = $appuser->email;
//			$subject = 'Order Confirmation';
//			$html = "<p>Hi ".$appuser->username.",</p>".
//						"<p>Your order has been sent to the restaurant for the following dish at below : <br/><br/>".
//						$order_items. "<br>".
//						"Total Amount : " . $total_amount . " " . $shop->currency_symbol . "<br/><br/>".
//						$trans_info.
//						"<p>Best Regards,<br/>".$sender_name."</p>";
//
//			$this->email->from($sender_email,$sender_name);
//			$this->email->to($to);
//			$this->email->subject($subject);
//			$this->email->message($html);


            $ci = get_instance();
            $ci->load->library('email');
            $config['protocol'] = "smtp";
            $config['smtp_host'] = "ssl://smtp.gmail.com";
            $config['smtp_port'] = "465";
            $config['smtp_user'] = $sender_name;
            $config['smtp_pass'] = $user_password;
            $config['charset'] = "utf-8";
            $config['mailtype'] = "html";
            $config['newline'] = "\r\n";

            $ci->email->initialize($config);

            $to = $appuser->email;
            $subject = 'Order Confirmation';
            $html = "<p>Hi ".$appuser->username.",</p>".
                "<p>Your order has been sent to the restaurant for the following dish at below : <br/><br/>".
                $order_items. "<br>".
                "Total Amount : " . $total_amount . " " . $shop->currency_symbol . "<br/><br/>".
                $trans_info.
                "<p>Best Regards,<br/>".$sender_name."</p>";

            $ci->email->from($sender_email,$sender_name);
            //$list = array('xxx@gmail.com');
            $ci->email->to($to);
            // $this->email->reply_to('my-email@gmail.com', 'Explendid Videos');
            $ci->email->subject($subject);
            $ci->email->message($html);
            $ci->email->send();
			
			
			
			
			
			if($ci->email->send()){
				return true;
			} else {
				return false;
			}
			
			
			
			
			
		} else if($payment_method == "bank") {
			
			$appuser = $this->appuser->get_info($user_id);
			$shop = $this->shop->get_info($shop_id);
			$order_items = "";
			$total_amount = 0;
			
			for($i=0;$i<count($order_data);$i++) 
			{
				$order_items .= $i + 1 .". " . $order_data[$i]->name . " (Price : " .  $order_data[$i]->unit_price . html_entity_decode($shop->currency_symbol) . ", QTY : " . $order_data[$i]->qty . ") <br>";
				
				$total_amount += $order_data[$i]->unit_price * $order_data[$i]->qty;
			}
			
			$bank_info  = "<br><br>Please take note about bank informatino for your trnasfer. <br>";
			$bank_info .= " Bank Account : " . $shop->bank_account ."<br>" ;
			$bank_info .= " Bank Name    : " . $shop->bank_name ."<br>" ;
			$bank_info .= " Bank Code    : " . $shop->bank_code ."<br>" ;
			$bank_info .= " Branch Code  : " . $shop->branch_code ."<br>" ;
			$bank_info .= " Swift Code   : " . $shop->swift_code ."<br>" ;
			
			
			$trans_info = "Please take note your transaction id is " . $trans_id . " for future inquiry to the shop.";
			
			
			$sender_email = trim($shop->sender_email);
			$sender_name  = $shop->name;

			//test tambahan
            $user_password=$this->config->item('sender_password');
            // Configure email library
           /* $config['protocol'] = 'mail';
            $config['smtp_host'] = 'smtp.gmail.com';
            $config['smtp_port'] = 587;
            $config['smtp_user'] = $sender_email;
            $config['smtp_pass'] = $user_password;
            // $config['smtp_crypto'] = $this->config->item('smtp_crypto');
            $config['mailtype']='html';
            $config['charset']='iso-8859-1';

            // Load email library and passing configured values to email library
            $this->load->library('email', $config);*/
            //end test tambahan
			/*$to = $appuser->email;
			$subject = 'Order Confirmation';
			$html = "<p>Hi ".$appuser->username.",</p>".
						"<p>Your order has been sent to the restaurant for the following dish at below : <br/><br/>".
						$order_items. "<br>".
						"Total Amount : " . $total_amount . " " . html_entity_decode($shop->currency_symbol) . "<br/><br/>".
						$trans_info.$bank_info .
						"<p>Best Regards,<br/>".$sender_name."</p>";*/
						
			/*$this->email->from($sender_email,$sender_name);
			$this->email->to($to); 
			$this->email->subject($subject);
			$this->email->message($html);*/

            $ci = get_instance();
            $ci->load->library('email');
            $config['protocol'] = "smtp";
            $config['smtp_host'] = "ssl://smtp.gmail.com";
            $config['smtp_port'] = "465";
            $config['smtp_user'] = $sender_name;
            $config['smtp_pass'] = $user_password;
            $config['charset'] = "utf-8";
            $config['mailtype'] = "html";
            $config['newline'] = "\r\n";

            $ci->email->initialize($config);

            $to = $appuser->email;
            $subject = 'Order Confirmation';
            $html = "<p>Hi ".$appuser->username.",</p>".
                "<p>Your order has been sent to the restaurant for the following dish at below : <br/><br/>".
                $order_items. "<br>".
                "Total Amount : " . $total_amount . " " . html_entity_decode($shop->currency_symbol) . "<br/><br/>".
                $trans_info.$bank_info .
                "<p>Best Regards,<br/>".$sender_name."</p>";

            $ci->email->from($sender_email,$sender_name);
            //$list = array('xxx@gmail.com');
            $ci->email->to($to);
            // $this->email->reply_to('my-email@gmail.com', 'Explendid Videos');
            $ci->email->subject($subject);
            $ci->email->message($html);
            $ci->email->send();

			if($ci->email->send()){
				return true;
			} else {
				return false;
			}
			
		}
		
	}
		
	function send_email_to_shop($user_id,$payment_method,$shop_id,$delivery_address,$billing_address,$email,$phone,$trans_id,$order_data)
	{
		
		$appuser = $this->appuser->get_info($user_id);
		$shop = $this->shop->get_info($shop_id);
		
		$order_items = "";
		$total_amount = 0;
		
		for($i=0;$i<count($order_data);$i++) 
		{
			$order_items .= $i + 1 .". " . $order_data[$i]->name . " (Price : " .  $order_data[$i]->unit_price . html_entity_decode($shop->currency_symbol) . ", QTY : " . $order_data[$i]->qty . ") <br>";
			
			$total_amount += $order_data[$i]->unit_price * $order_data[$i]->qty;
			
			
		}
		
		if($payment_method == "cod") {
			$payment_info = "Payment Method : Cash On Delivery";
		} else if($payment_method == "bank") {
			$payment_info = "Payment Method : Bank Transfer";
		} else if($payment_method == "stripe") {
			$payment_info = "Payment Method : Stripe";
		}
		
		
		$trans_info = "Please take note Transaction Id is " . $trans_id . " for future inquiry and reference.";
				
		$del_address = "Here is delivery address for the order.<br/>";
		$del_address = $del_address . $delivery_address;
		
		$bil_address = "Here is billing address for the order.<br/>";
		$bil_address = $bil_address . $billing_address;
		
		$cust_info  = "Here is customer information.<br/>";
		$cust_info .= "User Name : " . $appuser->username . "<br>";
		$cust_info .= "Email     : " . $email . "<br>";
		$cust_info .= "Phone     : " . $phone . "<br>";		
		
		
		$sender_email = $shop->sender_email;
		$sender_name = $shop->name;
		$to = $shop->cod_email;
        $user_password=$this->config->item('sender_password');

		/*$subject = 'Order Received';
		$html = "<p>Hi ".$shop->name.",</p>".
					"<p>You have been received the order for the following item at below : <br/><br/>".
					$order_items. "<br>".
					"Total Amount : " . $total_amount . " " . html_entity_decode($shop->currency_symbol) . "<br/><br/>".
					$trans_info. "<br/><br/>" . $del_address ."<br/><br/>". 
					$bil_address ."<br/><br/>".$cust_info."<br/><br/>". $payment_info
					."<br/><br/>"."<p>Best Regards,<br/>".$sender_name."</p>";*/
					
		/*$this->email->from($sender_email,$sender_name);
		$this->email->to($to); 
		$this->email->subject($subject);
		$this->email->message($html);*/

        $ci = get_instance();
        $ci->load->library('email');
        $config['protocol'] = "smtp";
        $config['smtp_host'] = "ssl://smtp.gmail.com";
        $config['smtp_port'] = "465";
        $config['smtp_user'] = $sender_name;
        $config['smtp_pass'] = $user_password;
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";

        $ci->email->initialize($config);

       // $to = $appuser->email;
        $subject = 'Order Confirmation';
        $html = "<p>Hi ".$shop->name.",</p>".
            "<p>You have been received the order for the following item at below : <br/><br/>".
            $order_items. "<br>".
            "Total Amount : " . $total_amount . " " . html_entity_decode($shop->currency_symbol) . "<br/><br/>".
            $trans_info. "<br/><br/>" . $del_address ."<br/><br/>".
            $bil_address ."<br/><br/>".$cust_info."<br/><br/>". $payment_info
            ."<br/><br/>"."<p>Best Regards,<br/>".$sender_name."</p>";

        $ci->email->from($sender_email,$sender_name);
        //$list = array('xxx@gmail.com');
        $ci->email->to($to);
        // $this->email->reply_to('my-email@gmail.com', 'Explendid Videos');
        $ci->email->subject($subject);
        $ci->email->message($html);
        $ci->email->send();

        if($ci->email->send()){
			return true;
		} else {
			return false;
		}
		
		
		
	}
	
	
		
}
?>