<?php




class WizardsFugue_Core_Model_Design_Package extends Mage_Core_Model_Design_Package
{
    
    protected function _fallback($file, array &$params, array $fallbackScheme = array(array()))
    {
        $result = parent::_fallback($file, $params, $fallbackScheme);
        /*
        var_dump(
            $file,
            $result,
            file_exists($result)
        );
        die(__FILE__.':'.__LINE__);
         */
        if( !$result ){
            $result = 'frontend/base/default/template/' . $file;
        }
        return $result;
    }
}