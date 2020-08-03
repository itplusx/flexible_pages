<?php
defined('TYPO3_MODE') or die ('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'flexible_pages',
    'Configuration/TypoScript/',
    'Flexible Pages'
);

if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('headless')) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'flexible_pages',
        'Configuration/TypoScript/Headless/',
        'Flexible Pages Headless'
    );
}
