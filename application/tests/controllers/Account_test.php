<?php
/**
 * API controller tests
 *
 * @author     Paul Ngumii
 * @link       https://github.com/paulkish/bankacc
 */

class Account_test extends TestCase
{	
	public function test_balance()
	{
		$controller = $this->newController('Account');
		$this->assertTrue(method_exists($controller, 'balance'));
	}

	public function test_deposit()
	{
		$controller = $this->newController('Account');
		$this->assertTrue(method_exists($controller, 'deposit'));
	}

	public function test_withdraw()
	{
		$controller = $this->newController('Account');
		$this->assertTrue(method_exists($controller, 'withdraw'));
	}

	public function test_method_404()
	{
		$this->request('GET', 'account/method_not_exist');
		$this->assertResponseCode(404);
	}

	public function tearDown()
    {
        Mockery::close();
    }

}
