<?php

namespace ITplusX\FlexiblePages\Utilities;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TsConfigUtility
{
    /**
     * @var TypoScriptService
     */
    protected $typoScriptService;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);
    }

    /**
     * Allow backend users to drag and drop the page type with the given dokType.
     *
     * @param int $dokType
     */
    public static function enableDragAndDropOfPageType(int $dokType)
    {
        // Allow backend users to drag and drop the new page type:
        ExtensionManagementUtility::addUserTSConfig(
            'options.pageTree.doktypesToShowInNewPageDragArea := addToList(' . $dokType . ')'
        );
    }

    /**
     * Get the TSconfig
     *
     * @param int $pageUid The uid of the page to get the TSconfig from
     * @param string $tsPath The TypoScript path as dot notation
     * @return array
     */
    public static function getTsConfig($pageUid, $tsPath)
    {
        $tsConfig = [];
        $pagesTsConfig = BackendUtility::getPagesTSconfig($pageUid);

        /** @var TypoScriptService $typoScriptService */
        $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);
        $pagesTsConfig = $typoScriptService->convertTypoScriptArrayToPlainArray($pagesTsConfig);

        if (ArrayUtility::isValidPath($pagesTsConfig, $tsPath, '.')) {
            $tsConfig = ArrayUtility::getValueByPath($pagesTsConfig, $tsPath, '.');
        }

        return $tsConfig;
    }
}
