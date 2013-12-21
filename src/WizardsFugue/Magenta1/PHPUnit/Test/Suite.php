<?php
/**
 * 
 * 
 * 
 * 
 * 
 */

namespace WizardsFugue\Magenta1\PHPUnit\Test;


class Suite extends \EcomDev_PHPUnit_Test_Suite{

    public static function suite(){
        $groups = \Mage::getConfig()->getNode(self::XML_PATH_UNIT_TEST_GROUPS);
        $modules = \Mage::getConfig()->getNode(self::XML_PATH_UNIT_TEST_MODULES);
        $testSuiteClass = \EcomDev_Utils_Reflection::getReflection((string) \Mage::getConfig()->getNode(self::XML_PATH_UNIT_TEST_SUITE));

        if (!$testSuiteClass->isSubclassOf('\EcomDev_PHPUnit_Test_Suite_Group')) {
            new \RuntimeException('Test Suite class should be extended from EcomDev_PHPUnit_Test_Suite_Group');
        }

        $suite = new self('Magento Test Suite');

        // Walk through different groups in modules for finding test cases
        foreach ($groups->children() as $group) {
            foreach ($modules->children() as $module) {
                $realModule = \Mage::getConfig()->getNode('modules/' . $module->getName());
                if (!$realModule || !$realModule->is('active')) {
                    $suite->addTest(self::warning('There is no module with name: ' . $module->getName()));
                    continue;
                }

                $moduleCodeDir = \Mage::getBaseDir('wfCode') . DS . (string) $realModule->codePool;
                $searchPath = \Mage::getModuleDir('', $module->getName()) . DS . 'Test' . DS . (string) $group;

                if (!is_dir($searchPath)) {
                    continue;
                }

                $currentGroups = array(
                    $group->getName(),
                    $module->getName()
                );

                $testCases = self::_loadTestCases($searchPath, $moduleCodeDir);

                foreach ($testCases as $className) {
                    $suite->addTest($testSuiteClass->newInstance($className, $currentGroups));
                }
            }
        }

        if (!$suite->count()) {
            $suite->addTest(self::warning('There were no test cases for the current run'));
        }

        return $suite;
    }

}
