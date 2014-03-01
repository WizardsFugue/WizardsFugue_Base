<?php
/**
 * 
 * 
 * 
 * 
 * 
 */

namespace WizardsFugue\Magenta1\Core\Model;


class Config extends \Mage_Core_Model_Config{

    public function __construct($sourceData=null)
    {
        parent::__construct($sourceData);
        $this->_options         = new Config\Options($sourceData);
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
        $codePath       = (string)$this->getModuleConfig($moduleName)->codePath;
        
        $classNames     = array(
            $moduleName . '_Helper_Data',
            $moduleName . '_Model_Observer',
        );
        foreach( $classNames as $className 
        ){
            if( !class_exists($className) ){ continue; }
            $reflector = new \ReflectionClass( $className );
            $codePath       = dirname(dirname( $reflector->getFileName() ));
            if( $codePath != '' ){ break; }
        }



            $dir = $codePath;

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

        return $dir;
    }

}