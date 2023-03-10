<?php

/**
 * @package     downloadmail
 * @since       18.10.2018 - 10:54
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */

declare(strict_types=1);

namespace Esit\Downloadmail\Classes\Contao\Hooks;

use Contao\System;
use Esit\Downloadmail\Classes\Services\Helper\BlacklistHelper;

class BlacklistHook
{


    /**
     * Hook: Prüft, ob eine Mailadresse auf der Blacklist steht.
     * @param $strRegexp
     * @param $varValue
     * @param  \Widget $objWidget
     * @return bool
     */
    public function onBlacklistRegex($strRegexp, $varValue, \Widget $objWidget): bool
    {
        if ('mailblacklist' === $strRegexp) {
            $blacklist = System::getContainer()->get(BlacklistHelper::class);

            if (!$blacklist?->validateMailaddress($varValue)) {
                $err = $GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['custrgxperr'];
                $objWidget->addError(\sprintf($err, $objWidget->label));
            }

            return true;
        }

        return false;
    }
}
