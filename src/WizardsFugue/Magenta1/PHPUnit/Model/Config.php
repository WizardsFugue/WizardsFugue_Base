<?php
/**
 * 
 * 
 * 
 * 
 * 
 */

namespace WizardsFugue\Magenta1\PHPUnit\Model;


class Config extends \EcomDev_PHPUnit_Model_Config{

    public function __construct($sourceData=null)
    {
        if( $sourceData == null ){
            $sourceData = include(__DIR__ . '/../../../../../../base_magento/run-options.php');
        }
        parent::__construct($sourceData);
        if( $sourceData && isset( $sourceData['wizards_fudge']['paths'] ) )
        {
            foreach( $sourceData['wizards_fudge']['paths'] as $key=>$val ){
                $this->_options->setData( $key, $val );
            }

        }

    }

    public function getModuleDir($type, $moduleName){

        $codePool       = (string)$this->getModuleConfig($moduleName)->codePool;
        $coreDistPart   = (string)$this->getModuleConfig($moduleName)->coreDistPart;

        if( $codePool == "core" || $coreDistPart === "true" ){
            $dir = parent::getModuleDir($type, $moduleName);
        }else{

            $dir = $this->getOptions()->getWfCodeDir().DS.$codePool.DS.uc_words($moduleName, DS);

            switch ($type) {
                case 'etc':
                    $dir .= DS.'etc';
                    break;

                case 'controllers':
                    $dir .= DS.'controllers';
                    break;

                case 'sql':
                    $dir .= DS.'sql';
                    break;
                case 'data':
                    $dir .= DS.'data';
                    break;

                case 'locale':
                    $dir .= DS.'locale';
                    break;
            }

            $dir = str_replace('/', DS, $dir);
        }

        return $dir;
    }

}