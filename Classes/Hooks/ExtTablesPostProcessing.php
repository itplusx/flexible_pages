<?php

namespace ITplusX\FlexiblePages\Hooks;

use ITplusX\FlexiblePages\Configuration\Exceptions\InvalidConfigurationException;
use ITplusX\FlexiblePages\Configuration\PageTypesConfiguration;
use ITplusX\FlexiblePages\Registry\PageTypesRegistration;
use ITplusX\FlexiblePages\Utilities\ExtensionConfigurationUtility;
use ITplusX\FlexiblePages\Utilities\IconRegistrationUtility;
use ITplusX\FlexiblePages\Utilities\PageRegistrationUtility;
use ITplusX\FlexiblePages\Utilities\TcaUtility;
use ITplusX\FlexiblePages\Utilities\TsConfigUtility;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\TableConfigurationPostProcessingHookInterface;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExtTablesPostProcessing implements TableConfigurationPostProcessingHookInterface
{
    /**
     * @throws InvalidConfigurationException
     */
    public function processData()
    {
        if (!(isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][ExtensionConfigurationUtility::EXTKEY]['pageTypes'])
            && is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][ExtensionConfigurationUtility::EXTKEY]['pageTypes']))) {
            $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][ExtensionConfigurationUtility::EXTKEY]['pageTypes'] = [];
        }

        // Check if the data is already cached
        if ($this->hasCache() && $this->getCache()->get(ExtensionConfigurationUtility::CACHE_ENTRY_IDENTIFIER)) {
            $cachedPageTypesConfiguration = $this->getCache()->require(
                ExtensionConfigurationUtility::CACHE_ENTRY_IDENTIFIER
            );

            $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][ExtensionConfigurationUtility::EXTKEY]['pageTypes'] = $cachedPageTypesConfiguration;
        } else {
            $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);

            $configPaths = [];

            // Global configuration path
            $configPaths[] = Environment::getConfigPath() . '/' . ExtensionConfigurationUtility::EXTKEY;

            // Configuration path in every active extension
            foreach (ExtensionManagementUtility::getLoadedExtensionListArray() as $activeExtension) {
                $configPaths[] = Environment::getPublicPath() . '/typo3conf/ext/' . $activeExtension . '/Configuration/Yaml/' . ExtensionConfigurationUtility::EXTKEY;
            }

            // Custom configuration path from extension configuration
            $configPaths[] = GeneralUtility::getFileAbsFileName(
                (string)$extensionConfiguration->get(
                    ExtensionConfigurationUtility::EXTKEY,
                    'additionalYamlConfigPath'
                )
            );

            /** @var PageTypesRegistration $pageTypesRegistration */
            $pageTypesRegistration = GeneralUtility::makeInstance(PageTypesRegistration::class, $configPaths);
            $pageTypesRegistration->registerPageTypesFromYamlFiles();
        }

        /** @var PageTypesConfiguration $pageTypesConfiguration */
        $pageTypesConfiguration = GeneralUtility::makeInstance(
            PageTypesConfiguration::class,
            $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][ExtensionConfigurationUtility::EXTKEY]['pageTypes']
        );

        $coreDoktypes = [
            (int) PageRepository::DOKTYPE_DEFAULT,
            (int) PageRepository::DOKTYPE_LINK,
            (int) PageRepository::DOKTYPE_SHORTCUT,
            (int) PageRepository::DOKTYPE_BE_USER_SECTION,
            (int) PageRepository::DOKTYPE_MOUNTPOINT,
            (int) PageRepository::DOKTYPE_SPACER,
            (int) PageRepository::DOKTYPE_SYSFOLDER,
            (int) PageRepository::DOKTYPE_RECYCLER,
        ];

        $pageTypes = $pageTypesConfiguration->getPageTypes();

        /** @var \ITplusX\FlexiblePages\Page\PageType $pageType */
        foreach ($pageTypes as $pageType) {
            if(in_array($pageType->getDokType(), $coreDoktypes)) {
                throw new InvalidConfigurationException(
                    'Doktype "' . $pageType->getDokType() . '" is a doktype defined by the the TYPO3 core and therefore can\'t be registered by flexible_pages.'
                );
            } else {
                IconRegistrationUtility::registerIcons(
                    $pageType->getIconSet()->toArray()
                );

                TcaUtility::addPageTypeSelectItem(
                    $pageType->getDokType(),
                    $pageType->getLabel(),
                    $pageType->getIconSet()->getDefaultIcon()->getIdentifier()
                );

                TcaUtility::addPageTypeIconSet(
                    $pageType->getDokType(),
                    $pageType->getIconSet()
                );

                PageRegistrationUtility::registerDokTypeInPagesTypes(
                    $pageType->getDokType()
                );

                if ($pageType->isDraggableInNewPageDragArea() === true) {
                    TsConfigUtility::enableDragAndDropOfPageType(
                        $pageType->getDokType()
                    );
                }

                if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('headless')) {
                    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
                        \ITplusX\FlexiblePages\Utilities\ExtensionConfigurationUtility::EXTKEY,
                        'setup',
                        'lib.doktypeName {
                            ' . $pageType->getDokType() .' = TEXT
                            ' . $pageType->getDokType() .'.value = ' . $pageType->getLabel() .'
                        }'
                    );
                }
            }
        }
        if (empty($cachedPageTypesConfiguration) && $this->hasCache()) {
            $this->getCache()->set(
                ExtensionConfigurationUtility::CACHE_ENTRY_IDENTIFIER,
                'return ' . var_export($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][ExtensionConfigurationUtility::EXTKEY]['pageTypes'], true) . ';'
            );
        }
    }

    /**
     * Short-hand function for the cache
     *
     * @return FrontendInterface
     * @throws \TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException
     */
    protected function getCache(): FrontendInterface
    {
        return GeneralUtility::makeInstance(CacheManager::class)
            ->getCache(ExtensionConfigurationUtility::CACHE_IDENTIFIER);
    }

    /**
     * Short-hand function for the cache
     *
     * @return FrontendInterface
     * @throws \TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException
     */
    protected function hasCache()
    {
        return GeneralUtility::makeInstance(CacheManager::class)
            ->hasCache(ExtensionConfigurationUtility::CACHE_IDENTIFIER);
    }
}

