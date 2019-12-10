<?php
class licensesModel extends Model {
	public function createLicense($data) {
		$sql = "INSERT INTO `licenses` SET ";
		$sql .= "user_id = '" . (int)$data['user_id'] . "', ";
		$sql .= "license_ip = '" . $this->db->escape($data['license_ip']) . "', ";
		$sql .= "license_token = '" . $this->db->escape($data['license_token']) . "', ";
		$sql .= "license_mark = '" . $this->db->escape($data['license_mark']) . "', ";
		$sql .= "license_port = '" . (int)$data['license_port'] . "', ";
		$sql .= "license_status = '" . (int)$data['license_status'] . "', ";
		$sql .= "license_date_reg = NOW(), ";
		$sql .= "license_date_end = NOW() + INTERVAL " . (int)$data['license_months'] . " MONTH";
		$this->db->query($sql);
		return $this->db->getLastId();
	}
	
	public function deleteLicense($licenseid) {
		$sql = "DELETE FROM `licenses` WHERE license_id = '" . (int)$licenseid . "'";
		$this->db->query($sql);
	}
	
	public function updateLicense($licenseid, $data = array()) {
		$sql = "UPDATE `licenses`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				if($value == "NOW()") $sql .= " $key = $value";
				else $sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `license_id` = '" . (int)$licenseid . "'";
		$query = $this->db->query($sql);
		return true;
	}
	
	public function getLicenses($data = array(), $joins = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM `licenses`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "users":
					$sql .= " ON licenses.user_id=users.user_id";
					break;
			}
		}
		
		if(!empty($data)) {
			$count = count($data);
			$sql .= " WHERE";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= " AND";
			}
		}
		
		if(!empty($sort)) {
			$count = count($sort);
			$sql .= " ORDER BY";
			foreach($sort as $key => $value) {
				$sql .= " $key " . $value;
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		
		if(!empty($options)) {
			if ($options['start'] < 0) {
				$options['start'] = 0;
			}
			if ($options['limit'] < 1) {
				$options['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$options['start'] . "," . (int)$options['limit'];
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getLicenseById($licenseid, $joins = array()) {
		$sql = "SELECT * FROM `licenses`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "users":
					$sql .= " ON licenses.user_id=users.user_id";
					break;
			}
		}
		$sql .=  " WHERE `license_id` = '" . (int)$licenseid . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}

	public function getLicenseByIpToken($ip, $token, $joins = array()) {
		$sql = "SELECT * FROM `licenses`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "users":
					$sql .= " ON licenses.user_id=users.user_id";
					break;
			}
		}
		$sql .=  " WHERE `license_ip` = '" . $this->db->escape($ip) . "' AND `license_token` = '" . $this->db->escape($token) . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getTotalLicenses($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `licenses`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " WHERE";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= " AND";
			}
		}
		$query = $this->db->query($sql);
		return $query->row['count'];
	}
	
	public function extendLicense($licenseid, $month, $fromCurrent) {
		$sql = "UPDATE `licenses` SET license_date_end = ";
		if($fromCurrent)
			$sql .= "NOW()";
		else
			$sql .= "license_date_end";
		$sql .= "+INTERVAL " . (int)$month . " MONTH WHERE license_id = '" . (int)$licenseid . "'";
		
		$this->db->query($sql);
	}
}
?>
