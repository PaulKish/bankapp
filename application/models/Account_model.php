<?php

/**
 * Account model
 *
 * @author     Paul Ngumii
 * @link       https://github.com/paulkish/bankacc
 */

class Account_model extends CI_Model {

	/**
	 * Check balance by sum (deposits) - sum(withdraws)
	 */ 
	public function get_balance(){
		// get all deposits
		$query = $this->db->query('SELECT sum(amount) as deposits FROM transaction WHERE type = 1');
		$deposits = $query->row()->deposits;

		// get all withdras
		$query = $this->db->query('SELECT sum(amount) as withdraws FROM transaction WHERE type = 2');
		$withdraws = $query->row()->withdraws;

		// balance
		$balance = $deposits - $withdraws;
		return $balance;
	}

	/**
	 * Stores deposit
	 */ 
	public function set_deposit($amount){
		$data = [
			'amount' => $amount,
	        'type' => 1, // deposit
	        'datetime' => date('Y-m-d H:i:s')
		];
		return $this->db->insert('transaction', $data);
	}

	/**
	 * Stores withdraws
	 */ 
	public function set_withdraw($amount){
		$data = [
			'amount' => $amount,
	        'type' => 2, // withdraw
	        'datetime' => date('Y-m-d H:i:s')
		];
		return $this->db->insert('transaction', $data);
	}

	/**
	 * Validate amount
	 */ 
	public function validate_amount($amount){
		if($amount == NULL || !is_numeric($amount) || $amount <= 0){
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Validate deposit transaction limit
	 */ 
	public function validate_deposit($amount){
		if($amount <= 40000){
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Validate deposit transaction limit
	 */ 
	public function validate_deposit_limit($amount){
		$query = $this->db->query('SELECT sum(amount) as deposits FROM transaction WHERE type = 1 AND DATE (`datetime`) = CURDATE()');
		$deposits = $query->row()->deposits;

		// account for current deposit amount
		$total_amt = $amount + $deposits;

		if($total_amt > 150000){
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Validate count
	 */ 
	public function validate_count($type){
		$query = $this->db->query('SELECT count(amount) as count FROM transaction WHERE type = ? AND DATE (`datetime`) = CURDATE()',[$type]);
		$count = $query->row()->count;
		return $count;
	}

	/**
	 * Validate deposit count limit
	 */
	public function validate_deposit_count(){
		$deposits = $this->validate_count(1);
		if($deposits < 4){
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Validate withdraw transaction limit
	 */ 
	public function validate_withdraw($amount){
		if($amount <= 20000){
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Validate withdraw transaction limit
	 */ 
	public function validate_withdraw_limit($amount){
		$query = $this->db->query('SELECT sum(amount) as withdraws FROM transaction WHERE type = 2 AND DATE (`datetime`) = CURDATE()');
		$withdraws = $query->row()->withdraws;

		// account for current deposit amount
		$total_amt = $amount + $withdraws;
		if($total_amt > 50000){
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Validate withdraw amount is not greater than balance
	 */ 
	public function validate_withdraw_balance($amount){
		$balance = $this->get_balance();

		// check if amount is greater than balance
		if($amount > $balance){
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Validate withdraw count limit
	 */
	public function validate_withdraw_count(){
		$withdraw = $this->validate_count(2);
		if($withdraw < 3){
			return TRUE;
		}

		return FALSE;
	}
}