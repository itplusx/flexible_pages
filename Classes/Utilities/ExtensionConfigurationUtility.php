<?php

namespace ITplusX\FlexiblePages\Utilities;

use TYPO3\CMS\Core\Utility\ArrayUtility;

/**
 * ExtensionConfigurationUtility class
 */
class ExtensionConfigurationUtility
{
    const EXTKEY = 'flexible_pages';
    const CACHE_ENTRY_IDENTIFIER = 'pageType-configuration';
    const CACHE_IDENTIFIER = 'cache_' . self::EXTKEY;

    /**
     * Get the merged extension configuration from $GLOBALS['TYPO3_CONF_VARS']['EXT'] and TsConfig
     *
     * @param int $pageUid
     * @param string $extensionName The name of the extension to get the configuration from
     * @return array
     */
    public function getMergedExtensionConfiguration($pageUid, $extensionName)
    {
        $extensionConfiguration = [];
        // Check if the layouts are extended by ext_tables
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$extensionName])
            && is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$extensionName])
        ) {
            $extensionConfiguration = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$extensionName];
        }

        $tsConfig = TsConfigUtility::getTsConfig(
            $pageUid,
            'tx_' . str_replace('_', '', $extensionName)
        );

        ArrayUtility::mergeRecursiveWithOverrule($extensionConfiguration, $tsConfig);

        return $extensionConfiguration;
    }
}
