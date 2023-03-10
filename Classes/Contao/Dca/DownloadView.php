<?php

/**
 * @package     downloadmail
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */

declare(strict_types=1);

namespace Esit\Downloadmail\Classes\Contao\Dca;

use Contao\BackendTemplate;
use Contao\Environment;
use Contao\Input;
use Contao\System;
use Esit\Downloadmail\Classes\Events\OnShowDownloadEvent;

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
     * @param  mixed  $dc
     */
    public function show($dc)
    {
        $dispatcher = System::getContainer()->get('event_dispatcher');
        $template = new BackendTemplate($this->templateName);
        $event = new OnShowDownloadEvent();
        $event->setId((int)$dc->id);

        if ('reset' === Input::get('key')) {
            $event->setReset(true);
        }

        if (null !== $dispatcher) {
            $dispatcher->dispatch($event);
        }

        $template->setData($event->getData());
        $template->backlink = Environment::get('scriptName');
        $template->lang = @$GLOBALS['TL_LANG']['tl_dm_downloads'];
        $template->defaultLang = @$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform'];

        return $template->parse();
    }
}
