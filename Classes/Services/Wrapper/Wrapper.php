<?php

/**
 * @package     downloadmail
 * @since       11.01.19 - 15:32
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2021
 * @license     CC-BY-SA-4.0
 */

declare(strict_types=1);

namespace Esit\Downloadmail\Classes\Services\Wrapper;

abstract class Wrapper
{


    /**
     * Ruft eine statische Methode auf.
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $class = \str_replace(__NAMESPACE__, 'Contao', static::class);

        if (\class_exists($class) && \method_exists($class, $name)) {
            return \call_user_func_array([$class, $name], $arguments);
        }

        return null;
    }
}
