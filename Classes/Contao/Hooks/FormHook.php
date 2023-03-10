<?php

/**
 * @package     downloadmail
 * @since       18.10.2018 - 18:02
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */

declare(strict_types=1);

namespace Esit\Downloadmail\Classes\Contao\Hooks;

use Contao\System;
use Esit\Downloadmail\Classes\Events\OnManageFormEvent;

class FormHook
{


    /**
     * @param $postData
     * @param $formData
     * @param $filesData
     */
    public function onProcessFormData($postData, $formData, $filesData): void
    {
        if (isset($formData['downloadmailform']) && $formData['downloadmailform']) {
            $dispatcher = System::getContainer()->get('event_dispatcher');
            $event = new OnManageFormEvent();
            $event->setPostData($postData);
            $event->setFormData($formData);

            if (\is_array($filesData)) {
                $event->setFilesData($filesData);
            }

            $dispatcher?->dispatch($event);
        }
    }
}
