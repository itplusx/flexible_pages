<?php
namespace ITplusX\FlexiblePages\Tests\Unit\Page;

use ITplusX\FlexiblePages\Page\Icon;
use PHPUnit\Framework\TestCase;

class IconTest extends TestCase
{
    public function testIconConstruction()
    {
        $iconIdentifier = 'test-identifier';
        $source = 'test-value';

        $icon = new Icon($iconIdentifier, $source);
        $this->assertSame($icon->getIdentifier(), $iconIdentifier);
    }
}
