<?php
/**
 * @package     downloadmail
 * @filesource  DownloadView.php
 * @version     2.0.0
 * @since       20.10.2018 - 10:20
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
namespace Esit\Downloadmail\Classes\Contao\Dca;

use Contao\BackendTemplate;
use Contao\Database;
use Contao\Environment;
use Contao\Input;
use Contao\System;
use Esit\Downloadmail\Classes\Events\OnShowDownloadEvent;

/**
 * Class DownloadView
 * @package Esit\Downloadmail\Classes\Contao\Dca
 */
class DownloadView
{


    /**
     * Name des Ausgabetemplates
     * @var string
     */
    protected $templateName = 'downloadView';


    /**
     * Zeigt die Informationen zu einem Download an.
     * @return string
     */
    public function show($dc)
    {
        $dispatcher             = System::getContainer()->get('event_dispatcher');
        $template               = new BackendTemplate($this->templateName);
        $event                  = new OnShowDownloadEvent();
        $event->setId($dc->id);

        if (Input::get('key') == 'reset') {
            $event->setReset(true);
        }

        $dispatcher->dispatch($event::NAME, $event);

        $template->setData($event->getData());
        $template->backlink     = Environment::get('scriptName');
        $template->lang         = @$GLOBALS['TL_LANG']['tl_dm_downloads'];
        $template->defaultLang  = $GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform'];

        return $template->parse();
    }
}
