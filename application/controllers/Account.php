<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {

	public $response;

	public function __construct(){
		parent::__construct();
		$this->load->model('Account_model');
		$this->response = [
			'version' => '0.1',
            'links' => [
            	[
            		'href'=> '/balance',
            		'method' => 'GET'
            	],
            	[
            		'href'=> '/withdraw',
            		'method' => 'POST'
            	],
            	[
            		'href'=> '/deposit',
            		'method' => 'POST'
            	]
            ],
            'status'=>'Success',
            'message'=>'Ok'
		];
	}

	/**
	 * Default action
	 * Returns an list of links available in the API
	 */
	public function index()
	{
		$output = json_encode($this->response);

		return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($output);
	}

	/**
	 * Balance action
	 * returns current account balance
	 */ 
	public function balance(){
		// read balance from db
		$balance = $this->Account_model->get_balance();

		// add balance to the response
		$this->response['account'] = ['balance' => $balance];
		$output = json_encode($this->response);

		return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($output);
	}

	/**
	 * Deposit action
	 * Allows updating account details
	 */ 
	public function deposit(){
		if($this->input->method(TRUE) == 'GET'){

			// add error message and status
			$this->response['status'] = 'Failed';
			$this->response['message'] = 'Method not allowed';

			$output = json_encode($this->response);

			// prevent GET requests
			return $this->output
				->set_content_type('application/json')
				->set_status_header(405)
				->set_output($output);
		}

		$amount = $this->input->post('amount',TRUE);

		// validate amount is numeric
		if($this->Account_model->validate_amount($amount) == FALSE){

			// add error message and status
			$this->response['status'] = 'Failed';
			$this->response['message'] = 'Amount must be numeric and greater than zero';

			$output = json_encode($this->response);
			return $this->output
				->set_content_type('application/json')
				->set_status_header(400)
				->set_output($output);
		}

		// check transaction limit
		if($this->Account_model->validate_deposit_count($amount) == FALSE){

			// add error message and status
			$this->response['status'] = 'Failed';
			$this->response['message'] = 'Total number of deposit transactions should not exceed 4';

			$output = json_encode($this->response);
			return $this->output
				->set_content_type('application/json')
				->set_status_header(400)
				->set_output($output);
		}

		// check transaction limit
		if($this->Account_model->validate_deposit($amount) == FALSE){

			// add error message and status
			$this->response['status'] = 'Failed';
			$this->response['message'] = 'Deposit transactional amount should not exceed 40,000';

			$output = json_encode($this->response);
			return $this->output
				->set_content_type('application/json')
				->set_status_header(400)
				->set_output($output);
		}

		// check total daily transaction limit
		if($this->Account_model->validate_deposit_limit($amount) == FALSE){

			// add error message and status
			$this->response['status'] = 'Failed';
			$this->response['message'] = 'Total deposit amount per day should not exceed 150,000';

			$output = json_encode($this->response);
			return $this->output
				->set_content_type('application/json')
				->set_status_header(400)
				->set_output($output);
		}

		// store transcaction to db
		$insert = $this->Account_model->set_deposit($amount);

		// read balance from db
		$balance = $this->Account_model->get_balance();

		// add success message
		$this->response['status'] = 'Success';
		$this->response['message'] = 'Ok';

		// add balance to the response
		$this->response['account'] = ['balance' => $balance];

		// add transcaction info
		$this->response['transaction'] = [
			'type' => 'Deposit',
			'amount' => (float)$amount,
			'date' => date('Y-m-d')
		];


		$output = json_encode($this->response);
		return $this->output
			->set_content_type('application/json')
			->set_status_header(201)
			->set_output($output);
	}


	/**
	 * Withdraw action
	 * Allows updating account details
	 */ 
	public function withdraw(){
		if($this->input->method(TRUE) == 'GET'){

			// add error message and status
			$this->response['status'] = 'Failed';
			$this->response['message'] = 'Method not allowed';

			$output = json_encode($this->response);

			// prevent GET requests
			return $this->output
				->set_content_type('application/json')
				->set_status_header(405)
				->set_output($output);
		}

		$amount = $this->input->post('amount',TRUE);

		// validate amount
		if($this->Account_model->validate_amount($amount) == FALSE){

			// add error message and status
			$this->response['status'] = 'Failed';
			$this->response['message'] = 'Amount must be numeric and greater than zero';

			$output = json_encode($this->response);
			return $this->output
				->set_content_type('application/json')
				->set_status_header(400)
				->set_output($output);
		}

		// check transaction limit number
		if($this->Account_model->validate_withdraw_count($amount) == FALSE){

			// add error message and status
			$this->response['status'] = 'Failed';
			$this->response['message'] = 'Total number of withdrawal transactions should not exceed 3';

			$output = json_encode($this->response);
			return $this->output
				->set_content_type('application/json')
				->set_status_header(400)
				->set_output($output);
		}
		
		// check transaction limit
		if($this->Account_model->validate_withdraw($amount) == FALSE){

			// add error message and status
			$this->response['status'] = 'Failed';
			$this->response['message'] = 'Withdrawal transactional amount should not exceed 20,000';

			$output = json_encode($this->response);
			return $this->output
				->set_content_type('application/json')
				->set_status_header(400)
				->set_output($output);
		}

		// check total daily transaction limit
		if($this->Account_model->validate_withdraw_limit($amount) == FALSE){

			// add error message and status
			$this->response['status'] = 'Failed';
			$this->response['message'] = 'Total withdrawn amount per day should not exceed 50,000';

			$output = json_encode($this->response);
			return $this->output
				->set_content_type('application/json')
				->set_status_header(400)
				->set_output($output);
		}

		// check amount isn't less than balance
		if($this->Account_model->validate_withdraw_balance($amount) == FALSE){

			// add error message and status
			$this->response['status'] = 'Failed';
			$this->response['message'] = 'Amount to withdraw should not exceed account balance';

			$output = json_encode($this->response);
			return $this->output
				->set_content_type('application/json')
				->set_status_header(400)
				->set_output($output);
		}

		// store transcaction to db
		$insert = $this->Account_model->set_withdraw($amount);

		// read balance from db
		$balance = $this->Account_model->get_balance();

		// add success message
		$this->response['status'] = 'Success';
		$this->response['message'] = 'Ok';

		// add balance to the response
		$this->response['account'] = ['balance' => $balance];

		// add transcaction info
		$this->response['transaction'] = [
			'type' => 'Withdrawal',
			'amount' => (float)$amount,
			'date' => date('Y-m-d')
		];


		$output = json_encode($this->response);
		return $this->output
			->set_content_type('application/json')
			->set_status_header(201)
			->set_output($output);
	}
}
