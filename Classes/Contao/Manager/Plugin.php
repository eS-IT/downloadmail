<?php

declare(strict_types=1);
/**
 * @package     downloadmail
 * @filesource  Plugin.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:54
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */

namespace Esit\Downloadmail\Classes\Contao\Manager;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

/**
 * Class Plugin
 * @package Esit\Downloadmail\Classes\Contao\Manager
 */
class Plugin implements BundlePluginInterface
{
    /**
     * @param  ParserInterface                                             $parser
     * @return array|\Contao\ManagerPlugin\Bundle\Config\ConfigInterface[]
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(\Esit\Downloadmail\EsitDownloadmailBundle::class)
                ->setLoadAfter([\Contao\CoreBundle\ContaoCoreBundle::class])
        ];
    }
}
