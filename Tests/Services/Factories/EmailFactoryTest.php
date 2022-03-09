<?php

/**
 * @package     downloadmail
 * @since       09.03.2022 - 14:43
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2022
 * @license     EULA
 */

declare(strict_types=1);

namespace Esit\Downloadmail\Tests\Services\Factories;

use Esit\Downloadmail\Classes\Services\Factories\EmailFactory;
use Esit\Downloadmail\EsitTestCase;

class EmailFactoryTest extends EsitTestCase
{


    public function testCreate(): void
    {
        $factory = new EmailFactory();
        self::assertNotNull($factory->create());
    }
}
