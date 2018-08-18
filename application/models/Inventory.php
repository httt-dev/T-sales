<?php
class Inventory extends CI_Model 
{	
	public function insert($inventory_data)
	{
		return $this->db->insert('inventory', $inventory_data);
	}
	
	public function get_inventory_data_for_item($item_id, $location_id = FALSE)
	{
		$this->db->from('inventory');
		$this->db->where('trans_items', $item_id);
        if($location_id != FALSE)
        {
            $this->db->where('trans_location', $location_id);
        }
		$this->db->order_by('trans_date', 'desc');

		return $this->db->get();		
	}

	public function getLogs(){
		$this->db->from('logs');
		return $this->db->get()->result_array();
	}

	public function salesLog($action,$module,$mode,$sale_id){
		$save_data = array(
			'user_id' => $this->session->userdata('person_id'),
			'action' => $action,
			'module' => $module,
			'type' => $mode,
			'order_id' => $sale_id,
			'date' => date('Y/m/d h:i:s'),
		);
		$success = $this->db->insert('logs', $save_data);
	}

	public function deleteSalesLog($action,$module,$type,$id){
		$content =' Xóa';
		if($type == 'sale'){
			$status = 'sale';
			$content .= ' hóa đơn bán hàng';
		}
		if($type == 'return'){
			$status = 'sale';
			$content .= ' hóa đơn trả lại';
		}
		if($type == 'taiche'){
			$status = 'receving';
			$content .= ' hóa đơn tái chế';
		}
		if($type == 'huy'){
			$status = 'sale';
			$content .= ' hóa đơn hủy hàng';
		}
		if($type == 'tra_ncc'){
			$status = 'receving';
			$content .= ' hóa đơn trả nhà cung cấp';
		}
		if($status == 'sale'){
			$this->db->select("*");
			$this->db->from('sales');
			$this->db->join('people', 'sales.customer_id = people.person_id');
			$this->db->where('sales.sale_id', $id);
			$data = $this->db->get()->result_array();
			if(isset($data[0])){
				$content .= " vào ". $data[0]['sale_time'] . " của khách hàng ". $data[0]['full_name'] . " (" . $data[0]['address'] . "), hóa đơn có giá trị: ". to_currency($data[0]['order_money']);
			}
		}else if($status == 'receving'){
			$this->db->select("*");
			$this->db->from('receivings');
			$this->db->join('people', 'receivings.employee_id = people.person_id');
			$this->db->where('receivings.receiving_id', $id);
			$data = $this->db->get()->result_array();
			if(isset($data[0])){
				$content .= " vào ". $data[0]['receiving_time'] . " của nhà cung cấp ". $data[0]['full_name'] . " (" . $data[0]['address'] . "), hóa đơn có giá trị: ". to_currency($data[0]['order_money']);
			}
		}
		$save_data = array(
			'user_id' => $this->session->userdata('person_id'),
			'action' => $action,
			'module' => $module,
			'type' => $content,
			'order_id' => $id,
			'date' => date('Y/m/d h:i:s'),
		);
		$success = $this->db->insert('logs', $save_data);
	}

	public function loginLog($action,$type){
		$save_data = array(
			'user_id' => $this->session->userdata('person_id'),
			'action' => 'login',
			'date' => date('Y/m/d h:i:s'),
		);
		$success = $this->db->insert('logs', $save_data);
	}

	public function searchLogs($search,$limit,$offset,$people,$type,$statDate,$fromdate){
		$this->db->select("action,module,type,order_id,date,people.full_name as name_people");
		$this->db->from('logs');
		$this->db->join('employees', 'employees.person_id = logs.user_id');
		$this->db->join('people', 'employees.person_id = people.person_id');
		$this->db->where('DATE(t_logs.date) BETWEEN ' . $this->db->escape($statDate) . ' AND ' . $this->db->escape($fromdate));
		if($search && $search !== '')
		{
			$this->db->like('logs.content', $search);
		}

		if($people && $people !== '')
		{
			$this->db->like('logs.user_id', $people);
		}

		if($type && $type !== '')
		{
			$this->db->like('logs.action', $type);
		}


		if($offset > 0) 
		{	
			$this->db->limit($offset, $limit);
		}
		$this->db->order_by('date', 'desc');
		return $this->db->get()->result_array();
	}

	public function getInforEditSale($id,$type){
		$status = '';
		$content = ' Đã hiệu chỉnh';
		if($type == 'sale'){
			$status = 'sale';
			$content .= ' hóa đơn bán hàng';
		}
		if($type == 'return'){
			$status = 'sale';
			$content .= ' hóa đơn trả lại';
		}
		if($type == 'taiche'){
			$status = 'receving';
			$content .= ' hóa đơn tái chế';
		}
		if($type == 'huy'){
			$status = 'sale';
			$content .= ' hóa đơn hủy hàng';
		}
		if($type == 'tra_ncc'){
			$status = 'receving';
			$content .= ' hóa đơn nhà cung cấp';
		}

		if($status == 'sale'){
			$this->db->select("*");
			$this->db->from('sales');
			$this->db->join('people', 'sales.customer_id = people.person_id');
			$this->db->where('sales.sale_id', $id);
			$data = $this->db->get()->result_array();
			if(isset($data[0])){
				$content .= " vào ". $data[0]['sale_time'] . " của khách hàng ". $data[0]['full_name'] . " (" . $data[0]['address'] . "), hóa đơn có giá trị: ". to_currency($data[0]['order_money']);
			}
		}else if($status == 'receving'){
			$this->db->select("*");
			$this->db->from('receivings');
			$this->db->join('people', 'receivings.employee_id = people.person_id');
			$this->db->where('receivings.receiving_id', $id);
			$data = $this->db->get()->result_array();
			if(isset($data[0])){
				$content .= " vào ". $data[0]['receiving_time'] . " của nhà cung cấp ". $data[0]['full_name'] . " (" . $data[0]['address'] . "), hóa đơn có giá trị: ". to_currency($data[0]['order_money']);
			}
		}
		return $content;
	}
}
?>