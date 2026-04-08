<?php
namespace ITplusX\FlexiblePages\Tests\Unit\Configuration;

use ITplusX\FlexiblePages\Configuration\Exceptions\InvalidConfigurationException;
use ITplusX\FlexiblePages\Configuration\PageTypeConfiguration;
use ITplusX\FlexiblePages\Tests\Fixtures\PageTypesConfigurationFixture;
use PHPUnit\Framework\TestCase;

class PageTypeConfigurationTest extends TestCase
{
    /**
     * @var array
     */
    private $pageTypesConfiguration;

    protected function setUp()
    {
        $this->pageTypesConfiguration = PageTypesConfigurationFixture::getPageTypesConfiguration();
    }

    public function testConstructionOfPageTypeFromConfiguration()
    {
        $dokType = 111;
        $configuration = $this->pageTypesConfiguration[$dokType];
        $configuration['dokType'] = $dokType;
        $pageTypeConfiguration = new PageTypeConfiguration($configuration);

        $label = $this->pageTypesConfiguration[$dokType]['label'];
        $pageType = $pageTypeConfiguration->getPageType();

        $this->assertSame($label, $pageType->getLabel());
    }

    public function testInvalidConfigurationExceptionMessageContainsClassName()
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessageMatches('/PageTypeConfiguration/');

        new PageTypeConfiguration([]);
    }
}
