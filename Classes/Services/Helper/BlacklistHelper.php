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

use Esit\Downloadmail\Classes\Services\Wrapper\Database;
use Esit\Downloadmail\Classes\Services\Wrapper\Idna;
use Esit\Downloadmail\Classes\Services\Wrapper\Validator;

class BlacklistHelper
{
    /**
     * @var Database
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
     * @param Database  $db
     * @param Validator $validator
     * @param Idna      $idna
     */
    public function __construct(Database $db, Validator $validator, Idna $idna)
    {
        $this->db = $db;
        $this->validator = $validator;
        $this->idna = $idna;
    }

    /**
     * Prüft, ob eine Mailadresse valide ist und nicht in der Blacklist steht.
     * @param $strMail
     * @return bool
     */
    public function validateMailaddress($strMail): bool
    {
        if ($this->checkMailaddress($strMail)) {
            // Mailadresse nicht in Blacklist!
            $strMail = $this->idna->encodeEmail($strMail);

            return $this->validator->isEmail($strMail);
        }

        return false;
    }

    /**
     * Ueberprueft eine Mailadresse gegen die Blacklist.
     * @param $strMail
     * @return bool
     */
    public function checkMailaddress($strMail): bool
    {
        $query = "SELECT * FROM tl_dm_blacklist";
        $result = $this->db->execute($query);

        if ($result->numRows) {
            while ($result->next()) {
                if ($result->regex) {
                    $strPattern = \html_entity_decode($result->pattern);

                    if ('' != $result->pattern && \preg_match('|' . $strPattern . '|i', $strMail)) {
                        // Adresse stimmt mit dem Regulaeren Ausdruck ueberein.
                        return false;
                    }
                } else {
                    if ('' != $result->pattern && \substr_count($strMail, $result->pattern)) {
                        // String ist in Mailadresse enthalten.
                        return false;
                    }
                }
            }
        }

        // Mailadresse nicht in Blacklist oder Blacklist leer!
        return true;
    }
}
