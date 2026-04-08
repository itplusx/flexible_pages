<?php
namespace ITplusX\FlexiblePages\Tests\Unit\Configuration;

use ITplusX\FlexiblePages\Configuration\Exceptions\InvalidConfigurationException;
use ITplusX\FlexiblePages\Configuration\IconConfiguration;
use ITplusX\FlexiblePages\Page\Icon;
use ITplusX\FlexiblePages\Tests\Fixtures\PageTypesConfigurationFixture;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use ITplusX\FlexiblePages\Configuration\Validation\ValidationResult;

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

        $this->assertSame('tx-flexiblepages-icon-111', $icon->getIdentifier());
    }

    public function testInvalidConfigurationExceptionMessageContainsClassName()
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessageRegExp('/IconConfiguration/');

        new IconConfiguration([]);
    }

    public function testCustomIdentifierIsPreservedWhenSourceIsProvided()
    {
        $iconConfiguration = PageTypesConfigurationFixture::getIconConfigurationByDokTypeAndIconType(
            111,
            'rootPageIcon'
        );

        $icon = GeneralUtility::makeInstance(IconConfiguration::class, $iconConfiguration)->getIcon();

        $this->assertSame('custom-icon-identifier', $icon->getIdentifier());
    }

    public function testValidateReturnsErrorForIdentifierOnlyConfigWithUnknownIdentifier()
    {
        $configuration = ['identifier' => 'non-existing-icon-identifier'];

        $validationResult = IconConfiguration::validate($configuration);

        $this->assertInstanceOf(ValidationResult::class, $validationResult);
        $this->assertTrue($validationResult->hasErrors());
    }

    public function testValidateReturnsErrorForCustomConfigWhenIdentifierAlreadyExists()
    {
        $configuration = [
            'identifier' => 'apps-pagetree-page-content-from-page-hideinmenu',
            'source' => 'EXT:flexible_pages/Tests/Fixtures/Icon-111.png',
        ];

        $validationResult = IconConfiguration::validate($configuration);

        $this->assertInstanceOf(ValidationResult::class, $validationResult);
        $this->assertTrue($validationResult->hasErrors());
    }
}
