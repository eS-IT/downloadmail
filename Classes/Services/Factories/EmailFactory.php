<?php

/**
 * @package     downloadmail
 * @since       03.10.21 - 14:27
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2021
 * @license     EULA
 */

declare(strict_types=1);

namespace Esit\Downloadmail\Classes\Services\Factories;

use Contao\Email;

class EmailFactory
{

    public function create(): Email
    {
        return new Email();
    }
}
