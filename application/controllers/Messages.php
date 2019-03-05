<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Messages extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('messages');
		
		$this->load->library('sms_lib');
		$this->load->library('sale_lib');
				$this->CI =& get_instance();
	}
	
	public function index()
	{
		$this->load->view('messages/sms');
	}

	public function view($person_id = -1)
	{ 
		$info = $this->Person->get_info($person_id);
		foreach(get_object_vars($info) as $property => $value)
		{
			$info->$property = $this->xss_clean($value);
		}
		$data['person_info'] = $info;

		$this->load->view('messages/form_sms', $data);
	}

	public function send()
	{	
		$phone   = $this->input->post('phone');
		$message = $this->input->post('message');

		$response = $this->sms_lib->sendSMS($phone, $message);

		if($response)
		{
			echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('messages_successfully_sent') . ' ' . $phone));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('messages_unsuccessfully_sent') . ' ' . $phone));
		}
	}
	
	public function send_form($person_id = -1)
	{	
		$phone   = $this->input->post('phone');
		$message = $this->input->post('message');

		$response = $this->sms_lib->sendSMS($phone, $message);

		if($response)
		{
			echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('messages_successfully_sent') . ' ' . $phone, 'person_id' => $this->xss_clean($person_id)));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('messages_unsuccessfully_sent') . ' ' . $phone, 'person_id' => -1));
		}
	}

	public function closedata(){
		$data = array();
		$this->load->view("messages/closedata", $data);
	}

	public function updateNewdb(){
		$dbName = $this->input->get('dbName');
		$person_id = $this->session->userdata('person_id');
		$person_info = $this->Employee->get_info($person_id);
		$mode = $this->sale_lib->get_mode();
		if(!$this->Employee->has_grant('config', $person_id))
		{
			echo json_encode(array('success' => FALSE, 'message' => "Bạn không có quyền"));
		}
		// Xoa tat ca du lieu
		$this->deleteAll();
		// Dong bo du lieu
		$this->dongbodulieu($dbName);
		echo json_encode(array('success' => TRUE, 'message' => "Đồng bộ dữ liệu thành công"));
	}

	public function deleteAll(){
		// Xóa bảng log
		$this->db->delete('logs', array(1 => 1));
		$SQL = "ALTER TABLE t_logs AUTO_INCREMENT = 1 ";
		$this->db->query($SQL);
		// Xóa bảng t_receivings
		$this->db->delete('receivings_items', array(1 => 1));
		$SQL = "ALTER TABLE t_receivings_items AUTO_INCREMENT = 1 ";
		$this->db->query($SQL);
		// Xóa bảng t_receivings
		$this->db->delete('receivings', array(1 => 1));
		$SQL = "ALTER TABLE t_receivings AUTO_INCREMENT = 1 ";
		$this->db->query($SQL);
		// Xóa bảng sales_items
		$this->db->delete('sales_items', array(1 => 1));
		$SQL = "ALTER TABLE t_sales_items AUTO_INCREMENT = 1 ";
		$this->db->query($SQL);
		// Xóa bảng t_sales
		$this->db->delete('sales', array(1 => 1));
		$SQL = "ALTER TABLE t_sales AUTO_INCREMENT = 1 ";
		$this->db->query($SQL);
		// Xoa bang item_quantities
		$this->db->delete('item_quantities', array(1 => 1));
	}

	public function dongbodulieu($dbName){
		// Dong bo bang t_sales
		$this->dongbot_sales($dbName);
	}

	public function dongbot_sales($dbName){
		$arrSales = array();
		$j=0;
		for($i=1;$i<=7;$i++){
			$SQL = "customer_id
			,SUM(car_money) as car_money
			,SUM(order_money) as order_money
			,SUM(pay_money) as pay_money
			,SUM(sanluong_dongia_dd) as sanluong_dongia_dd
			,SUM(sanluong_dongia_hh) as sanluong_dongia_hh
			,SUM(sanluong_soluong_dd) as sanluong_soluong_dd
			,SUM(sanluong_soluong_hh) as sanluong_soluong_hh
			FROM  ".$dbName.".t_sales   
			where type = ".$i."         
			GROUP BY customer_id
			;";
			$arrSales = $this->db->select($SQL)->get()->result_array();
			echo "<pre>"; print_r($arrSales); die;
			
			$j++;
		}
		
	}
}
?>
