<?php
defined('TYPO3_MODE') || die ('Access denied.');

$init = function ($extKey) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['extTablesInclusion-PostProcessing'][] = \ITplusX\FlexiblePages\Hooks\ExtTablesPostProcessing::class;

    if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$extKey])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$extKey] = [
            'frontend' => \TYPO3\CMS\Core\Cache\Frontend\PhpFrontend::class,
            'backend' => \TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend::class,
            'options' => [
                'defaultLifetime' => 0,
            ],
            'groups' => ['system']
        ];
    }

    $icons = [
        'tx-flexiblepages-pagelist' => 'EXT:' . $extKey . '/Resources/Public/Icons/tx-flexiblepages-pagelist.svg'
    ];
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

    foreach ($icons as $iconIdentifier => $iconFile) {
        $iconRegistry->registerIcon(
            $iconIdentifier,
            TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            [
                'source' => $iconFile,
            ]
        );
    }

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '@import \'EXT:' . $extKey . '/Configuration/TSconfig/Page/Mod/Wizards/NewContentElement.tsconfig\''
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '@import \'EXT:' . $extKey . '/Configuration/TSconfig/Page/Mod/WebLayout/TtContent/Preview.tsconfig\''
    );
};

$init(\ITplusX\FlexiblePages\Utilities\ExtensionConfigurationUtility::EXTKEY);
unset($init);
