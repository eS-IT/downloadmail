<?php
/**
 * @package     downloadmail
 * @filesource  ModuleRequestDownload.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:54
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
namespace Esit\Downloadmail\Classes\Contao\Modules;

use Contao\BackendTemplate;
use Contao\FrontendTemplate;
use Contao\Input;
use Contao\Module;
use Contao\System;
use Esit\Downloadmail\Classes\Events\OnManageDownloadEvent;

/**
 * Class ModuleRequestDownload
 * @package Esit\Downloadmail\Classes\Contao\Modules
 */
class ModuleRequestDownload extends Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_easy_downloadmail';


    /**
     * Return a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {
            $objTemplate            = new BackendTemplate('be_wildcard');
            $objTemplate->wildcard  = '### easyDownloadmail-Modul ###';
            $objTemplate->title     = $this->headline;
            $objTemplate->id        = $this->id;
            $objTemplate->link      = $this->name;
            $objTemplate->href      = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }


    /**
     * Generate the module
     */
    protected function compile()
    {
        $formLang   = $GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform'];
        $di         = System::getContainer()->get('event_dispatcher');
        $template   = new FrontendTemplate('downloadmail');
        $event      = new OnManageDownloadEvent();

        if (Input::get('key')) {
            $event->setDownloadKey(Input::get('key'));
        }

        $event->setModulId($this->id);
        $event->setFeFormLang($formLang);
        $event->setTamplate($template);

        $di->dispatch($event::NAME, $event);

        $this->Template->content = $template->parse();
    }
}
