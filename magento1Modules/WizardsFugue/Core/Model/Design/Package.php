<?php




class WizardsFugue_Core_Model_Design_Package extends Mage_Core_Model_Design_Package
{

    protected function _fallback($file, array &$params, array $fallbackScheme = array(array()))
    {
	    if($params['_area'] == 'adminhtml') {
		    // set on the custom admin design dir
		    Mage::app()->getConfig()->getOptions()->setData('design_dir', Mage::app()->getConfig()->getOptions()->getAdminDesignDir());
	    }

	    $result = parent::_fallback($file, $params, $fallbackScheme);


        if( !$result ){
	        if($params['_area'] == 'adminhtml') {
		        $result = 'adminhtml/default/default/' . $file;
	        } else {
		        $result = 'frontend/base/default/template/' . $file;

	        }
        }
        return $result;
    }

}