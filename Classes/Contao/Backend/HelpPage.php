<?php declare(strict_types = 1);
/**
 * @package     smartgallery
 * @filesource  HelpPage.php
 * @since       10.07.2020 - 10:55
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Smartgallery\Classes\Contao\Backend;

use Contao\DataContainer;

/**
 * Class HelpPage
 * @package Smartgallery\Classes\Contao\Backend
 */
class HelpPage extends \BackendModule
{


    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'esit_backend_help';


    /**
     * @var \Parsedown
     */
    protected $parser;


    /**
     * Datei mit den Informationen
     * @var string
     */
    protected $markdownFile = TL_ROOT . '/files/IMPORTANT_README.md';


    /**
     * HelpPage constructor.
     * @param null|DataContainer $dc
     */
    public function __construct(DataContainer $dc = null)
    {
        parent::__construct($dc);

        $this->parser = new \Parsedown();
        $this->parser->setSafeMode(true);
        $this->parser->setBreaksEnabled(true);
    }


    /**
     * Generate the module
     */
    protected function compile(): void
    {
        if (\is_file($this->markdownFile)) {
            $this->Template->content = $this->parser->parse(\file_get_contents($this->markdownFile));
        }
    }
}
