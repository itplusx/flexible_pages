<?php
namespace ITplusX\FlexiblePages\Tests\Unit\Configuration;

use ITplusX\FlexiblePages\Configuration\IconConfiguration;
use ITplusX\FlexiblePages\Page\Icon;
use ITplusX\FlexiblePages\Tests\Fixtures\PageTypesConfigurationFixture;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @extensionScannerIgnoreFile
 */
class IconConfigurationTest extends TestCase
{
    /**
     * @var PageTypesConfigurationFixture
     */
    private $pageTypesConfiguration;

    protected function setUp()
    {
        $this->pageTypesConfiguration = PageTypesConfigurationFixture::getPageTypesConfiguration();
    }

    public function testConstructionOfIconFromConfigurationWithCustomIdentifier()
    {
        $iconConfiguration = PageTypesConfigurationFixture::getIconConfigurationByDokTypeAndIconType(
            111,
            'rootPageIcon'
        );

        $icon = GeneralUtility::makeInstance(IconConfiguration::class, $iconConfiguration)->getIcon();

        $this->assertInstanceOf(Icon::class, $icon);
    }

    public function testConstructionOfIconFromConfigurationWithRegisteredIdentifier()
    {
        $iconConfiguration = PageTypesConfigurationFixture::getIconConfigurationByDokTypeAndIconType(
            111,
            'hideInMenuIcon'
        );

        $icon = GeneralUtility::makeInstance(IconConfiguration::class, $iconConfiguration)->getIcon();

        $this->assertInstanceOf(Icon::class, $icon);
    }

    public function testConstructionOfIconFromConfigurationWithSourceOnly()
    {
        $iconConfiguration = PageTypesConfigurationFixture::getIconConfigurationByDokTypeAndIconType(
            111,
            'defaultIcon'
        );

        $icon = GeneralUtility::makeInstance(IconConfiguration::class, $iconConfiguration)->getIcon();

        $this->assertInstanceOf(Icon::class, $icon);
    }

    public function testIdentifierCreationFromConfigurationWithPathOnly()
    {
        $iconConfiguration = PageTypesConfigurationFixture::getIconConfigurationByDokTypeAndIconType(
            111,
            'defaultIcon'
        );

        $icon = GeneralUtility::makeInstance(IconConfiguration::class, $iconConfiguration)->getIcon();

        $this->assertSame('icon-111', $icon->getIdentifier());
    }
}
