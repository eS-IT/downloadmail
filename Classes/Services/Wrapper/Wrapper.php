<?php

declare(strict_types=1);
/**
 * @package     downloadmail
 * @version     1.0.0
 * @since       11.01.19 - 15:32
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2021
 * @license     CC-BY-SA-4.0
 */

namespace Esit\Downloadmail\Classes\Services\Wrapper;

/**
 * Wrapper für die Klassen von Contao. Da diese statisch sind
 * und so schlecht für Tests injeziert werden können.
 */
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
    }
}
