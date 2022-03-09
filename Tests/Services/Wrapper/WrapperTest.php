<?php

/**
 * @package     downloadmail
 * @since       09.03.2022 - 15:17
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2022
 * @license     EULA
 */

declare(strict_types=1);

namespace Esit\Downloadmail\Tests\Services\Wrapper;

use Esit\Downloadmail\Classes\Services\Wrapper\StringUtil;
use PHPUnit\Framework\TestCase;

class WrapperTest extends TestCase
{


    public function testCall(): void
    {
        $expected   = 'TEST';
        $string     = new StringUtil();
        self::assertSame($expected, $string->wordWrap($expected));
    }
}
