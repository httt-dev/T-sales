<?php
class Employee extends Person
{
	/*
	Determines if a given person_id is an employee
	*/
	public function exists($person_id)
	{
		$this->db->from('employees');	
		$this->db->join('people', 'people.person_id = employees.person_id');
		$this->db->where('employees.person_id', $person_id);

		return ($this->db->get()->num_rows() == 1);
	}	

	public function delete_session()
	{
		$SQL = "delete from t_sessions ";
		$this->db->query($SQL);
	}
	/*
	Gets total of rows
	*/
	public function get_total_rows()
	{
		$this->db->from('employees');
		$this->db->where('deleted', 0);

		return $this->db->count_all_results();
	}

	/*
	Returns all the employees
	*/
	public function get_all($limit = 10000, $offset = 0)
	{
		$this->db->from('employees');
		$this->db->where('deleted', 0);		
		$this->db->join('people', 'employees.person_id = people.person_id');			
		$this->db->order_by('full_name', 'asc');
		$this->db->limit($limit);
		$this->db->offset($offset);

		return $this->db->get();		
	}

	public function getalls(){
		$this->db->from('employees');	
		$this->db->join('people', 'employees.person_id = people.person_id');		
		$this->db->order_by('full_name', 'asc');
		return $this->db->get()->result_array();
	}
	
	/*
	Gets information about a particular employee
	*/
	public function get_info($employee_id)
	{
		$this->db->from('employees');	
		$this->db->join('people', 'people.person_id = employees.person_id');
		$this->db->where('employees.person_id', $employee_id);
		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $employee_id is NOT an employee
			$person_obj = parent::get_info(-1);

			//Get all the fields from employee table
			//append those fields to base parent object, we we have a complete empty object
			foreach($this->db->list_fields('employees') as $field)
			{
				$person_obj->$field = '';
			}

			return $person_obj;
		}
	}
	public function get_info_by_sales($employee_id)
	{
		$this->db->from('employees');
		$this->db->join('people', 'people.person_id = employees.person_id');
		$this->db->where('employees.person_id', $employee_id);

		return $this->db->get()->result_array();
	}
	/*
	Gets information about multiple employees
	*/
	public function get_multiple_info($employee_ids)
	{
		$this->db->from('employees');
		$this->db->join('people', 'people.person_id = employees.person_id');		
		$this->db->where_in('employees.person_id', $employee_ids);
		$this->db->order_by('last_name', 'asc');

		return $this->db->get();		
	}

	/*
	Inserts or updates an employee
	*/
	public function save_employee(&$person_data, &$employee_data, &$grants_data, $employee_id = FALSE)
	{
		$success = FALSE;

		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		if(parent::save($person_data, $employee_id))
		{
			if(!$employee_id || !$this->exists($employee_id))
			{
				$employee_data['person_id'] = $employee_id = $person_data['person_id'];
				$success = $this->db->insert('employees', $employee_data);
			}
			else
			{
				$this->db->where('person_id', $employee_id);
				$success = $this->db->update('employees', $employee_data);
			}

			//We have either inserted or updated a new employee, now lets set permissions. 
			if($success)
			{
				//First lets clear out any grants the employee currently has.
				$success = $this->db->delete('grants', array('person_id' => $employee_id));
				
				//Now insert the new grants
				if($success)
				{
					foreach($grants_data as $permission_id)
					{
						$success = $this->db->insert('grants', array('permission_id' => $permission_id, 'person_id' => $employee_id));
					}
				}
			}
		}

		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}

	/*
	Deletes one employee
	*/
	public function delete($employee_id)
	{
		$success = FALSE;

		//Don't let employees delete theirself
		if($employee_id == $this->get_logged_in_employee_info()->person_id)
		{
			return FALSE;
		}

		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		//Delete permissions
		if($this->db->delete('grants', array('person_id' => $employee_id)))
		{	
			$this->db->where('person_id', $employee_id);
			$success = $this->db->update('employees', array('deleted' => 1));
		}

		$this->db->trans_complete();

		return $success;
	}

	/*
	Deletes a list of employees
	*/
	public function delete_list($employee_ids)
	{
		$success = FALSE;

		//Don't let employees delete theirself
		if(in_array($this->get_logged_in_employee_info()->person_id, $employee_ids))
		{
			return FALSE;
		}

		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$this->db->where_in('person_id', $employee_ids);
		//Delete permissions
		if($this->db->delete('grants'))
		{
			//delete from employee table
			$this->db->where_in('person_id', $employee_ids);
			$success = $this->db->update('employees', array('deleted' => 1));
		}

		$this->db->trans_complete();

		return $success;
 	}

	/*
	Get search suggestions to find employees
	*/
	public function get_search_suggestions($search, $limit = 5)
	{
		$suggestions = array();

		$this->db->from('employees');
		$this->db->join('people', 'employees.person_id = people.person_id');
		$this->db->group_start();
			$this->db->like('full_name', $search);
		$this->db->group_end();
		$this->db->where('deleted', 0);
		$this->db->order_by('full_name', 'asc');
		foreach($this->db->get()->result() as $row)
		{
			$suggestions[] = array('value' => $row->person_id, 'label' => $row->full_name);
		}

		$this->db->from('employees');
		$this->db->join('people', 'employees.person_id = people.person_id');
		$this->db->where('deleted', 0);
		$this->db->like('email', $search);
		$this->db->order_by('email', 'asc');
		foreach($this->db->get()->result() as $row)
		{
			$suggestions[] = array('value' => $row->person_id, 'label' => $row->email);
		}

		$this->db->from('employees');
		$this->db->join('people', 'employees.person_id = people.person_id');
		$this->db->where('deleted', 0);
		$this->db->like('username', $search);
		$this->db->order_by('username', 'asc');
		foreach($this->db->get()->result() as $row)
		{
			$suggestions[] = array('value' => $row->person_id, 'label' => $row->username);
		}

		$this->db->from('employees');
		$this->db->join('people', 'employees.person_id = people.person_id');
		$this->db->where('deleted', 0);
		$this->db->like('phone_number', $search);
		$this->db->order_by('phone_number', 'asc');
		foreach($this->db->get()->result() as $row)
		{
			$suggestions[] = array('value' => $row->person_id, 'label' => $row->phone_number);
		}

		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0, $limit);
		}

		return $suggestions;
	}

 	/*
	Gets rows
	*/
	public function get_found_rows($search)
	{
		$this->db->from('employees');
		$this->db->join('people', 'employees.person_id = people.person_id');
		$this->db->group_start();
			$this->db->like('full_name', $search);
			$this->db->or_like('email', $search);
			$this->db->or_like('phone_number', $search);
			$this->db->or_like('username', $search);
		$this->db->group_end();

		return $this->db->get()->num_rows();
	}

	/*
	Performs a search on employees
	*/
	public function search($search, $rows = 0, $limit_from = 0, $sort = 'last_name', $order = 'asc')
	{
		$this->db->from('employees');
		$this->db->join('people', 'employees.person_id = people.person_id');
		$this->db->group_start();
			$this->db->like('full_name', $search);
			$this->db->or_like('email', $search);
			$this->db->or_like('phone_number', $search);
			$this->db->or_like('username', $search);
		$this->db->group_end();
		$this->db->order_by($sort, $order);

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();	
	}

	public function checkiplogin($username,$ip)
	{
		// id persion
		$this->db->from('employees');
		$this->db->join('people', 'employees.person_id = people.person_id');
		$this->db->where('username', $username);
		$result = $this->db->get()->result_array();
		$stringip = $result[0]['ip'];
		$arrids = explode(',', $stringip);
		$checkip = false;
		foreach($arrids as $arrid){
			if($arrid == $ip){
				$checkip = true;
			}
		}
		return $checkip;
	}

	/*
	Attempts to login employee and set session. Returns boolean based on outcome.
	*/
	public function login($username, $password)
	{
		$query = $this->db->get_where('employees', array('username' => $username, 'password' => md5($password)), 1);

		if($query->num_rows() == 1)
		{
			$row = $query->row();
			$this->session->set_userdata('person_id', $row->person_id);

			return TRUE;
		}

		return FALSE;
	}

	/*
	Logs out a user by destorying all session data and redirect to login
	*/
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
	
	/*
	Determins if a employee is logged in
	*/
	public function is_logged_in()
	{
		return ($this->session->userdata('person_id') != FALSE);
	}

	/*
	Gets information about the currently logged in employee.
	*/
	public function get_logged_in_employee_info()
	{
		if($this->is_logged_in())
		{
			return $this->get_info($this->session->userdata('person_id'));
		}

		return FALSE;
	}

	/*
	Determines whether the employee has access to at least one submodule
	 */
	public function has_module_grant($permission_id, $person_id)
	{
		$this->db->from('grants');
		$this->db->like('permission_id', $permission_id, 'after');
		$this->db->where('person_id', $person_id);
		$result_count = $this->db->get()->num_rows();

		if($result_count != 1)
		{
			return ($result_count != 0);
		}

		return $this->has_subpermissions($permission_id);
	}

 	/*
	Checks permissions
	*/
	public function has_subpermissions($permission_id)
	{
		$this->db->from('permissions');
		$this->db->like('permission_id', $permission_id.'_', 'after');

		return ($this->db->get()->num_rows() == 0);
	}

	/*
	Determines whether the employee specified employee has access the specific module.
	*/
	public function has_grant($permission_id, $person_id)
	{
		//if no module_id is null, allow access
		if($permission_id == null)
		{
			return TRUE;
		}

		$query = $this->db->get_where('grants', array('person_id' => $person_id, 'permission_id' => $permission_id), 1);

		return ($query->num_rows() == 1); 
	}

 	/*
	Gets employee permission grants
	*/
	public function get_employee_grants($person_id)
	{
		$this->db->from('grants');
		$this->db->where('person_id', $person_id);

		return $this->db->get()->result_array();
	}
}
?>
