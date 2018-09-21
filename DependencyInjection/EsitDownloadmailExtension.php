<?php
/**
 * @package     downloadmail
 * @filesource  EsitDownloadmailExtension.php
 * @version     1.0.0
 * @since       21.09.18 - 16:29
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     EULA
 */
namespace Esit\Downloadmail\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class EsitDownloadmailExtension
 * @package Esit\Downloadmail\DependencyInjection
 */
class EsitDownloadmailExtension extends Extension
{


    /**
     * @param array            $mergedConfig
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $mergedConfig, ContainerBuilder $container)
    {
        // Pfad zu den Konfigurationsdateien des Bandles erstellen.
        $path = __DIR__.'/../Resources/config';

        // Erstellen des Loaders für das aktuelle Verzeichnis
        $loader = new YamlFileLoader($container, new FileLocator($path));

        // Laden von TL_ROOT/src/Esit/Core/Resources/config/services.yml
        $loader->load('services.yml');

        // Laden von TL_ROOT/Esit/Core/Resources/config/listener.yml
        $loader->load('listener.yml');
    }
}
