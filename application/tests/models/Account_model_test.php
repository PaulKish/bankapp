<?php 

/**
 * API model tests
 *
 * @author     Paul Ngumii
 * @link       https://github.com/paulkish/bankacc
 */

class Account_model_test extends TestCase
{
    public function setUp()
    {
        $this->obj = $this->newModel('Account_model');
    }

    /**
     * Test retrieve balance
     */ 
    public function test_get_balance()
    {
        $balance = $this->obj->get_balance();
        $this->assertTrue(is_numeric($balance));
    }

    /**
     * Verify amount is numeric and greater than zero
     */ 
    public function test_validate_amount()
    {
        $amount = 50;
        $amt = $this->obj->validate_amount($amount);
        $this->assertTrue(is_numeric($amount));
        $this->assertNotNull($amount);
        $this->assertGreaterThan(0,$amount);
        $this->assertTrue($amt);
    }

    /**
     * Test for failure
     */ 
    public function test_validate_amount_failure()
    {
        $amount = -50;
        $amt = $this->obj->validate_amount($amount);
        $this->assertFalse($amt);
    }

    /**
     * Verify deposit is not greater than limit per transaction
     */ 
    public function test_validate_deposit()
    {
        $amount = 5000;
        $deposit = $this->obj->validate_deposit($amount);
        $this->assertLessThanOrEqual(40000,$amount);
        $this->assertTrue($deposit);
    }

    /**
     * Test for failure
     */ 
    public function test_validate_deposit_failure()
    {
        $amount = 50000;
        $deposit = $this->obj->validate_deposit($amount);
        $this->assertFalse($deposit);
    }

    /**
     * Verify deposit limit not reached
     */ 
    public function test_validate_deposit_limit()
    {
        $amount = 0; // zero works best here
        $deposit = $this->obj->validate_deposit_limit($amount);
        $this->assertTrue($deposit);
    }

    /**
     * Test failure
     */ 
    public function test_validate_deposit_limit_failure()
    {
        $amount = 500000;
        $deposit = $this->obj->validate_deposit_limit($amount);
        $this->assertFalse($deposit);
    }

    /**
     * Verify transaction count
     */ 
    public function test_validate_count()
    {
        $deposit = $this->obj->validate_count(1);
        $this->assertTrue(is_numeric($deposit));
    }

    /**
     * Verify deposit limit not reached
     */ 
    public function test_validate_deposit_count()
    {
        $deposit = $this->obj->validate_count(1);
        $resp = $this->obj->validate_deposit_count();
        if($deposit < 4){
            $this->assertTrue($resp);
        }else{
            $this->assertFalse($resp);
        }
    }

    /**
     * Verify withdraw is not greater than limit per transaction
     */ 
    public function test_validate_withdraw()
    {
        $amount = 5000;
        $withdraw = $this->obj->validate_withdraw($amount);
        $this->assertLessThanOrEqual(20000,$amount);
        $this->assertTrue($withdraw);
    }

    /**
     * Test for failure
     */ 
    public function test_validate_withdraw_failure()
    {
        $amount = 50000;
        $withdraw = $this->obj->validate_withdraw($amount);
        $this->assertFalse($withdraw);
    }

    /**
     * Verify withdraw limit not reached
     */ 
    public function test_validate_withdraw_limit()
    {
        $amount = 5000;
        $withdraw = $this->obj->validate_withdraw_limit($amount);
        $this->assertTrue($withdraw);
    }

    /**
     * Test failure
     */ 
    public function test_validate_deposit_withdraw_failure()
    {
        $amount = 500000;
        $withdraw = $this->obj->validate_withdraw_limit($amount);
        $this->assertFalse($withdraw);
    }

    /**
     * Verify withdraw amount is not greater than balance
     */ 
    public function test_validate_withdraw_balance()
    {
        $amount = 5000;
        $balance = $this->obj->get_balance();
        $withdraw = $this->obj->validate_withdraw_balance($amount);
        if($amount < $balance){
            $this->assertTrue($withdraw);
        }else{
            $this->assertFalse($withdraw);
        }
        
    }

    /**
     * Verify withdraw limit not reached
     */ 
    public function test_validate_withdraw_count()
    {
        $withdraw = $this->obj->validate_count(2);
        $resp = $this->obj->validate_withdraw_count();
        if($withdraw < 3){
            $this->assertTrue($resp);
        }else{
            $this->assertFalse($resp);
        }
    }


    public function tearDown()
    {
        Mockery::close();
    }
}