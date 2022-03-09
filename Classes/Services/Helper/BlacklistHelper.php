<?php

declare(strict_types=1);
/**
 * @package     downloadmail
 * @version     2.0.0
 * @since       18.10.2018 - 10:53
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */

namespace Esit\Downloadmail\Classes\Services\Helper;

use Doctrine\DBAL\Connection;
use Esit\Downloadmail\Classes\Services\Wrapper\Idna;
use Esit\Downloadmail\Classes\Services\Wrapper\Validator;

class BlacklistHelper
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var Idna
     */
    private $idna;

    /**
     * @param Connection  $db
     * @param Validator $validator
     * @param Idna      $idna
     */
    public function __construct(Connection $db, Validator $validator, Idna $idna)
    {
        $this->db = $db;
        $this->validator = $validator;
        $this->idna = $idna;
    }


    /**
     * Prüft, ob eine Mailadresse valide ist und nicht in der Blacklist steht.
     * @param $strMail
     * @return bool
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function validateMailaddress($strMail): mixed
    {
        if ($this->checkMailaddress($strMail)) {
            // Mailadresse nicht in Blacklist!
            $strMail = $this->idna->encodeEmail($strMail);

            return (bool)$this->validator->isEmail($strMail);
        }

        return false;
    }


    /**
     * Überprueft eine Mailadresse gegen die Blacklist.
     * @param $strMail
     * @return bool
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function checkMailaddress($strMail): bool
    {
        $query  = $this->db->createQueryBuilder();
        $result = $query->select('*')->from('tl_dm_blacklist')->execute();
        $data   = $result->fetchAllAssociative();

        foreach ($data as $item) {
            if ($item['regex']) {
                $strPattern = \html_entity_decode($item['pattern']);

                if (!empty($item['pattern']) && \preg_match('|' . $strPattern . '|i', $strMail)) {
                    // Adresse stimmt mit dem Regulaeren Ausdruck ueberein.
                    return false;
                }
            } elseif (!empty($item['pattern']) && \substr_count($strMail, $item['pattern'])) {
                // String ist in Mailadresse enthalten.
                return false;
            }
        }

        // Mailadresse nicht in Blacklist oder Blacklist leer!
        return true;
    }
}
