<?php
defined('TYPO3') or die();

$init = function ($extKey) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$extKey] ??= [
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\PhpFrontend::class,
        'backend' => \TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend::class,
        'options' => [
            'defaultLifetime' => 0,
        ],
        'groups' => ['system']
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '@import \'EXT:' . $extKey . '/Configuration/TSconfig/Page/Mod/Wizards/NewContentElement.tsconfig\''
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '@import \'EXT:' . $extKey . '/Configuration/TSconfig/Page/Mod/WebLayout/TtContent/Preview.tsconfig\''
    );
};

$init(\ITplusX\FlexiblePages\Utilities\ExtensionConfigurationUtility::EXTKEY);
unset($init);
