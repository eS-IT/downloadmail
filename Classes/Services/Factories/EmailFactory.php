<?php

declare(strict_types=1);
/**
 * @package     downloadmail
 * @version     1.0.0
 * @since       03.10.21 - 14:27
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2021
 * @license     EULA
 */
namespace Esit\Downloadmail\Classes\Services\Factories;

use Contao\Email;

/**
 * Class EmailFactory
 * @package Esit\Downloadmail\Classes\Services\Factories
 */
class EmailFactory
{

    public function create(): Email
    {
        return new Email();
    }
}