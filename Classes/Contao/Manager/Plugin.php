<?php declare(strict_types=1);
/**
 * @author      pfroch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     EULA
 * @package     downloadmail
 * @filesource  Plugin.php
 * @version     1.0.0
 * @since       21.09.18 - 11:21
 */
namespace Esit\Downloadmail\Classes\Contao\Manager;

use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

/**
 * Class Plugin
 * @package Esit\Downloadmail\Classes\Contao\Manager
 */
class Plugin implements BundlePluginInterface
{


    /**
     * @param ParserInterface $parser
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