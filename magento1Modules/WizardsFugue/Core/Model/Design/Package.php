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
		        $result = 'adminhtml/default/default/template/' . $file;
	        } else {
		        $result = 'frontend/base/default/template/' . $file;

	        }
        }
        return $result;
    }

	public function getPackageList()
	{
		$originalOptions = Mage::app()->getConfig()->getOptions()->getWizardsFudge();

//		var_dump($originalOptions['paths']);die();
		$basePath = $originalOptions['paths']['design_dir'];


		$directory = $basePath . DS . 'frontend';
		return $this->_listFrontendDirectories($directory);
	}


	public function getThemeList($package = null)
	{
		$result = array();

		$originalOptions = Mage::app()->getConfig()->getOptions()->getWizardsFudge();

		$basePath = $originalOptions['paths']['design_dir'];
		if (is_null($package)){
			foreach ($this->getPackageList() as $package){
				$result[$package] = $this->getThemeList($package);
			}
		} else {
			$directory = $basePath . DS . 'frontend' . DS . $package;
			$result = $this->_listFrontendDirectories($directory);
		}

		return $result;
	}


	/**
	 * Had to replace the _listDirectories method somehow as it is a private method in the original claas
	 * @param $path
	 * @param bool $fullPath
	 * @return array
	 */
	protected function _listFrontendDirectories($path, $fullPath = false)
	{
		$result = array();
//		echo $path . "<br />";
		$dir = opendir($path);
		if ($dir) {
			while ($entry = readdir($dir)) {
				if (substr($entry, 0, 1) == '.' || !is_dir($path . DS . $entry)){
					continue;
				}
				if ($fullPath) {
					$entry = $path . DS . $entry;
				}
				$result[] = $entry;
			}
			unset($entry);
			closedir($dir);
		}

		return $result;
	}
}