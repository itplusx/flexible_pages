<?php
defined('TYPO3') or die();

$init = function ($extKey) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['flexiblepages_configurationcache'] ??= [];
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['flexiblepages_configurationcache']['frontend'] ??= \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['flexiblepages_configurationcache']['backend'] ??= \TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend::class;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['flexiblepages_configurationcache']['groups'] ??= ['system'];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '@import \'EXT:' . $extKey . '/Configuration/TSconfig/Page/Mod/Wizards/NewContentElement.tsconfig\''
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '@import \'EXT:' . $extKey . '/Configuration/TSconfig/Page/Mod/WebLayout/TtContent/Preview.tsconfig\''
    );
};

$init(\ITplusX\FlexiblePages\Utilities\ExtensionConfigurationUtility::EXTKEY);
unset($init);
