<?php

/**
 * @package     downloadmail
 * @since       18.10.2018 - 10:52
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */

declare(strict_types=1);

namespace Esit\Downloadmail\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class EsitDownloadmailExtension extends Extension
{


    /**
     * @param  array            $mergedConfig
     * @param  ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $mergedConfig, ContainerBuilder $container): void
    {
        $path   = __DIR__ . '/../Resources/config';
        $loader = new YamlFileLoader($container, new FileLocator($path));
        $loader->load('services.yml');
        $loader->load('listener.yml');
    }
}
