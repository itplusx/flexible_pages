<?php
namespace ITplusX\FlexiblePages\Tests\Unit\Configuration;

use ITplusX\FlexiblePages\Configuration\PageTypeConfiguration;
use ITplusX\FlexiblePages\Configuration\PageTypesConfiguration;
use ITplusX\FlexiblePages\Page\PageType;
use ITplusX\FlexiblePages\Tests\Fixtures\PageTypesConfigurationFixture;
use PHPUnit\Framework\TestCase;

class PageTypesConfigurationTest extends TestCase
{
    /**
     * @var array
     */
    private $pageTypesConfiguration;

    protected function setUp()
    {
        $this->pageTypesConfiguration = PageTypesConfigurationFixture::getPageTypesConfiguration();
    }

    public function testConstructionOfPageTypesFromConfiguration()
    {
        $pageTypesConfiguration = new PageTypesConfiguration($this->pageTypesConfiguration);

        $firstPageType = reset($pageTypesConfiguration->getPageTypes());

        $this->assertInstanceOf(PageType::class, $firstPageType);
    }
}
