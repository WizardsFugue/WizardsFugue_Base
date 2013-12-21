<?php
/**
 * 
 * 
 * 
 * 
 * 
 */

namespace WizardsFugue\Magenta1\PHPUnit\Test;


class Listener extends \EcomDev_PHPUnit_Test_Listener{


    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        \EcomDev_Utils_Reflection::setRestrictedPropertyValue('EcomDev_PHPUnit_Model_App', '_configClass', 'WizardsFugue\Magenta1\PHPUnit\Model\Config');
        parent::startTestSuite($suite);

    }
}