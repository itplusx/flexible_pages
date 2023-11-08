<?php
declare(strict_types=1);

namespace ITplusX\FlexiblePages\Registry;

use ITplusX\FlexiblePages\Utilities\ExtensionConfigurationUtility;
use Symfony\Component\Finder\Finder;
use TYPO3\CMS\Core\Configuration\Loader\YamlFileLoader;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PageTypesRegistration implements SingletonInterface
{
    /**
     * Config yaml paths
     *
     * @var array
     */
    protected $configPaths;

    /**
     * Config yaml file name.
     *
     * @internal
     * @var string
     */
    protected $configFileName = '*.yaml';

    /**
     * @param array additionalConfigPaths
     */
    public function __construct(array $configPaths = [])
    {
        $this->configPaths = $configPaths;
    }

    /**
     * Register pageTypes from all collected Yaml files
     *
     * @throws \TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException
     */
    public function registerPageTypesFromYamlFiles(): void
    {
        $pageTypes = $this->getAllPageTypeConfigurationFromFiles();

        foreach ($pageTypes as $pageTypeConfiguration) {
            self::registerPageType(...$pageTypeConfiguration);
        }
    }

    /**
     * Register a single pageType
     *
     * @param int $dokType
     * @param string $label
     * @param array $iconSet
     * @param bool $isDraggableInNewPageDragArea
     * @return void
     */
    public static function registerPageType(
        int $dokType,
        string $label,
        array $iconSet,
        bool $isDraggableInNewPageDragArea = false
    ): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][ExtensionConfigurationUtility::EXTKEY]['pageTypes'][$dokType] = [
            'label' => $label,
            'iconSet' => $iconSet,
            'isDraggableInNewPageDragArea' => $isDraggableInNewPageDragArea
        ];
    }

    /**
     * Read the pageType configuration from config files.
     *
     * @return array
     * @throws \TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException
     */
    protected function getAllPageTypeConfigurationFromFiles(): array
    {
        $pageTypeConfiguration = [];

        $finder = (new Finder())->files()->name($this->configFileName);
        $hasConfigurationFiles = false;
        foreach (array_filter($this->configPaths) as $configPath) {
            try {
                $finder->in($configPath);
            } catch (\InvalidArgumentException $e) {
                // Directory $configPath does not exist
                continue;
            }
            $hasConfigurationFiles = true;
        }

        if($hasConfigurationFiles) {
            $loader = GeneralUtility::makeInstance(YamlFileLoader::class);
            $pageTypeConfiguration = [];

            foreach ($finder as $fileInfo) {
                $configuration = $loader->load(GeneralUtility::fixWindowsFilePath((string)$fileInfo));
                $identifier = $configuration['dokType'];
                $pageTypeConfiguration[$identifier] = $configuration;
            }
        }

        return $pageTypeConfiguration;
    }
}
