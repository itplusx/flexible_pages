<?php
namespace ITplusX\FlexiblePages\Tests\Unit\Page;

use ITplusX\FlexiblePages\Page\Icon;
use ITplusX\FlexiblePages\Page\IconSet;
use ITplusX\FlexiblePages\Page\PageType;
use ITplusX\FlexiblePages\Tests\Fixtures\PageTypesConfigurationFixture;
use PHPUnit\Framework\TestCase;

class PageTypeTest extends TestCase
{
    public function testIconSetConstruction()
    {
        $dokType = 123;
        $label = 'My Label';

        $iconIdentifier = 'test-identifier';
        $iconFileReference = 'test-value';

        $icon = new Icon($iconIdentifier, $iconFileReference);
        $iconSet = new IconSet($icon);
        $pageType = new PageType($dokType, $label, $iconSet);

        $this->assertSame($iconIdentifier, $pageType->getIconSet()->getDefaultIcon()->getIdentifier());
    }
}
