<?php
namespace ITplusX\FlexiblePages\Tests\Unit\Configuration;

use ITplusX\FlexiblePages\Configuration\IconConfiguration;
use ITplusX\FlexiblePages\Configuration\IconSetConfiguration;
use ITplusX\FlexiblePages\Page\Icon;
use ITplusX\FlexiblePages\Page\IconSet;
use ITplusX\FlexiblePages\Tests\Fixtures\PageTypesConfigurationFixture;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class IconSetConfigurationTest extends TestCase
{
    /**
     * @var PageTypesConfigurationFixture
     */
    private $pageTypesConfiguration;

    protected function setUp()
    {
        $this->pageTypesConfiguration = PageTypesConfigurationFixture::getPageTypesConfiguration();
    }

    public function testConstructionOfIconSetFromConfiguration()
    {
        $iconSetConfiguration = $this->pageTypesConfiguration[111]['iconSet'];

        $iconSet = GeneralUtility::makeInstance(IconSetConfiguration::class, $iconSetConfiguration)->getIconSet();

        $this->assertInstanceOf(IconSet::class, $iconSet);
    }

    public function testConstructionOfDefaultIconFromConfiguration()
    {
        $iconSetConfiguration = $this->pageTypesConfiguration[111]['iconSet'];

        /** @var IconSet $iconSet */
        $iconSet = GeneralUtility::makeInstance(IconSetConfiguration::class, $iconSetConfiguration)->getIconSet();

        $this->assertInstanceOf(Icon::class, $iconSet->getDefaultIcon());
    }

    public function testDefaultIconIdentifierFromConfiguration()
    {
        $iconSetConfiguration = $this->pageTypesConfiguration[111]['iconSet'];

        /** @var IconSet $iconSet */
        $iconSet = GeneralUtility::makeInstance(IconSetConfiguration::class, $iconSetConfiguration)->getIconSet();

        $this->assertSame('icon-111', $iconSet->getDefaultIcon()->getIdentifier());
    }
}
