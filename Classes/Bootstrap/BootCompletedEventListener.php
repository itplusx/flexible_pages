<?php

declare(strict_types=1);

namespace ITplusX\FlexiblePages\Bootstrap;

use ITplusX\FlexiblePages\Configuration\Exceptions\InvalidConfigurationException;
use ITplusX\FlexiblePages\Configuration\PageTypesConfiguration;
use ITplusX\FlexiblePages\Registry\PageTypesRegistration;
use ITplusX\FlexiblePages\Utilities\ExtensionConfigurationUtility;
use ITplusX\FlexiblePages\Utilities\IconRegistrationUtility;
use ITplusX\FlexiblePages\Utilities\PageRegistrationUtility;
use ITplusX\FlexiblePages\Utilities\TcaUtility;
use ITplusX\FlexiblePages\Utilities\TsConfigUtility;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Core\Event\BootCompletedEvent;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BootCompletedEventListener
{
    public function __construct(
        private readonly FrontendInterface $cache,
    ) {}

    public function __invoke(BootCompletedEvent $event): void
    {
        if (!(isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][ExtensionConfigurationUtility::EXTKEY]['pageTypes'])
            && is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][ExtensionConfigurationUtility::EXTKEY]['pageTypes']))) {
            $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][ExtensionConfigurationUtility::EXTKEY]['pageTypes'] = [];
        }

        $cachedPageTypesConfiguration = $this->cache->get('fullConfiguration');
        if ($cachedPageTypesConfiguration !== false) {
            $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][ExtensionConfigurationUtility::EXTKEY]['pageTypes'] = $cachedPageTypesConfiguration;
        } else {
            $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);

            // Global configuration path (/config/flexible_pages/)
            $configPaths[] = Environment::getConfigPath() . '/' . ExtensionConfigurationUtility::EXTKEY;

            // Configuration path in every active local extension
            $packageManager = GeneralUtility::makeInstance(PackageManager::class);
            $localExtensions = array_filter($packageManager->getActivePackages(), function($extension) {
                return $extension->getPackageMetaData()->isExtensionType() === true;
            });

            foreach ($localExtensions as $extension) {
                foreach (['YAML', 'Yaml'] as $yamlDirectoryName) {
                    $configPaths[] = $extension->getPackagePath() . 'Configuration/' . $yamlDirectoryName . '/' . ExtensionConfigurationUtility::EXTKEY;
                }
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

                if(ExtensionManagementUtility::isLoaded('headless')) {
                    ExtensionManagementUtility::addTypoScript(
                        ExtensionConfigurationUtility::EXTKEY,
                        'setup',
                        'page.10.fields.type {
                            ' . $pageType->getDokType() .' = TEXT
                            ' . $pageType->getDokType() .'.value = ' . $pageType->getLabel() .'
                        }'
                    );
                }
            }
        }

        if (
            $event->isCachingEnabled()
            && $cachedPageTypesConfiguration != $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][ExtensionConfigurationUtility::EXTKEY]['pageTypes']
        ) {
            $this->cache->set(
                'fullConfiguration',
                $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][ExtensionConfigurationUtility::EXTKEY]['pageTypes']
            );
        }
    }
}
