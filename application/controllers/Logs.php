<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Persons.php");

class Logs extends Persons
{

	public function __construct()
	{
		parent::__construct('employees');
	}

	public function search(){

		$search = $this->input->get('search');
		$limit  = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$people  = $this->input->get('people_manager');
		$type  = $this->input->get('persion_type');
		$statDate = $this->input->get('start_date');
		$fromdate = $this->input->get('end_date');

		$datas = $this->Inventory->searchLogs($search,$limit,$offset,$people,$type,$statDate,$fromdate)->result_array();
		$total_rows = $this->Inventory->get_found_rows($search,$limit,$offset,$people,$type,$statDate,$fromdate);
		$i = 0;
		$data_rows = array();
		foreach($datas as $data_row){
			$content = '';
			$action = '';
			// Neu la dang nhap
			if($data_row['action'] == 'login'){
				$action = 'Đăng nhập';
			}
			// Neu la hieu chinh
			if($data_row['action'] == 'edit'){
				$action = 'Sửa';
			}
			// Neu la xoa
			if($data_row['action'] == 'delete'){
				$action = 'Xóa';
			}
			$data_rows[] = array (
				'action' => $action,
				'content' => $data_row['content'],
				'date' => $data_row['date'],
				'id' => $data_row['id'],
				'name_people' => $data_row['name_people'],
				'edit' => ''
			);
			$i++;
		}
		echo json_encode(array('total' => $total_rows,'rows' => $data_rows));
	}

	public function delete(){
		$list_id = $this->xss_clean($this->input->post('ids'));
		if($this->Inventory->delete_logs($list_id))
		{
			echo json_encode(array('success' => TRUE,'message' => "Xóa thành công"));
		}
		else
		{
			echo json_encode(array('success' => FALSE,'message' => "Xóa thất bại"));
		}
	}

}