<?php

declare(strict_types=1);
/**
 * @package     downloadmail
 * @since       08.01.2021 - 18:24
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2021
 * @license     CC-BY-SA-4.0
 */

namespace Esit\Downloadmail\Classes\Contao\Dca;

use Contao\Controller;
use Contao\Image;
use Contao\StringUtil;
use Contao\System;

/**
 * Class TlDmDownloads
 * @package Esit\Downloadmail\Classes\Contao\Dca
 */
class TlDmDownloads
{
    /**
     * Erzeugt das Icon für den Links der Details,
     * @param $row
     * @param $href
     * @param $label
     * @param $title
     * @param $icon
     * @param $attributes
     * @param $table
     * @param $rootIds
     * @param $childRecordIds
     * @param $circularReference
     * @param $previous
     * @param $next
     * @return string
     */
    public function generateIcon(
        $row,
        $href,
        $label,
        $title,
        $icon,
        $attributes,
        $table,
        $rootIds,
        $childRecordIds,
        $circularReference,
        $previous,
        $next
    ): string {
        $container = System::getContainer();
        $tokenManager = $container->get('contao.csrf.token_manager');
        $tokenName = $container->getParameter('contao.csrf_token_name');
        $href .= '&id=' . $row['id'];

        if (null !== $tokenManager) {
            $href .= '&rt=' . $tokenManager->getToken($tokenName)->getValue();
        }

        if (!empty($row['downloadcount'])) {
            $link = '<a href="' . Controller::addToUrl($href) . '" title="';
            $link .= StringUtil::specialchars($title) . '"' . $attributes . '>';
            $link .= Image::getHtml($icon, $label) . '</a> ';

            return $link;
        }

        return Image::getHtml(\str_replace('.png', '-sw.png', $icon), $label);
    }
}
