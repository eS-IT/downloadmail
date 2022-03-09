<?php

/**
 * @package     downloadmail
 * @since       09.03.2022 - 15:56
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2022
 * @license     EULA
 */

declare(strict_types=1);

namespace Esit\Downloadmail\Classes\Services\Helper;

use Esit\Downloadmail\Classes\Services\Wrapper\FilesModel;
use Esit\Downloadmail\Classes\Services\Wrapper\StringUtil;
use Esit\Downloadmail\Classes\Services\Wrapper\Validator;

class FileHelper
{

    /**
     * @var FilesModel
     */
    private $filesModel;

    /**
     * @var StringUtil
     */
    private $stringUtil;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @param FilesModel  $filesModel
     * @param StringUtil  $stringUtil
     * @param Validator   $validator
     */
    public function __construct(FilesModel $filesModel, StringUtil $stringUtil, Validator $validator)
    {
        $this->filesModel   = $filesModel;
        $this->stringUtil   = $stringUtil;
        $this->validator    = $validator;
    }


    /**
     * Gibt einen für Menschen lesbaren String mit der Größe einer Datei zurück.
     * @param  string $strPath
     * @param  int    $decimals
     * @return string
     */
    public function humanFilesize(string $strPath, int $decimals = 2): string
    {
        $strPath = (\substr_count($strPath, TL_ROOT)) ? $strPath : TL_ROOT . '/' . $strPath;
        $bytes = \filesize($strPath);
        $sz = ' KMGTP';
        $factor = (int)\floor((\strlen((string)$bytes) - 1) / 3);

        return \sprintf("%.{$decimals}f", $bytes / (1024 ** $factor)) . ' ' . @$sz[$factor] . 'B';
    }


    /**
     * Erstellt die Informationen zum Downloadfile.
     * @param $binId
     * @return array
     */
    public function genFileInfo($binId): array
    {
        $arrFile = [];
        $binId = ($this->validator->isBinaryUuid($binId)) ? $binId : $this->stringUtil->uuidToBin($binId);
        $objFile = $this->filesModel->findBfyPk($binId);

        if ($objFile) {
            $arrFile = [
                'filepath' => $objFile->path,
                'filename' => \basename($objFile->path),
                'fileext' => $objFile->extension,
                'filesize' => $this->humanFilesize($objFile->path, 0),
                'filehash' => $objFile->hash,
                'fileuuid' => $this->stringUtil->binToUuid($binId)
            ];
        }

        return $arrFile;
    }
}
