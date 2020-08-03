<?php
namespace ITplusX\FlexiblePages\Tests\Unit\Page;

use ITplusX\FlexiblePages\Page\Icon;
use ITplusX\FlexiblePages\Page\IconSet;
use PHPUnit\Framework\TestCase;

class IconSetTest extends TestCase
{
    public function testIconSetConstruction()
    {
        $iconIdentifier = 'test-identifier';
        $iconFileReference = 'test-value';

        $icon = new Icon($iconIdentifier, $iconFileReference);
        $iconSet = new IconSet($icon);

        $this->assertSame($iconIdentifier, $iconSet->getDefaultIcon()->getIdentifier());
    }
}
