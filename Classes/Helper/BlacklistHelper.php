<?php
/**
 * @package     downloadmail
 * @filesource  BlacklistHelper.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:53
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
namespace Esit\Downloadmail\Classes\Helper;

use Contao\Database;
use Contao\Idna;
use Contao\Validator;

/**
 * Class BlacklistHelper
 * @package Esit\Downloadmail\Classes\Helper
 */
class BlacklistHelper
{


    /**
     * @var Database
     */
    protected $db;


    /**
     * dmBlacklist constructor.
     * @param Database|null $db
     */
    public function __construct(Database $db = null)
    {
        $this->db = $db ?: Database::getInstance();
    }


    /**
     * Prüft, ob eine Mailadresse valide ist und nicht in der Blacklist steht.
     * @param $strMail
     * @return bool
     */
    public function validateMailaddress($strMail)
    {
        if ($this->checkMailaddress($strMail)) {
            // Mailadresse nicht in Blacklist!
            $strMail = Idna::encodeEmail($strMail);

            if (Validator::isEmail($strMail)) {
                // Es ist eine valide Mailaderesse und nicht in der Blacklist!
                return true;
            }
        }

        return false;
    }


    /**
     * Ueberprueft eine Mailadresse gegen die Blacklist.
     * @param $strMail
     * @return bool
     */
    public function checkMailaddress($strMail)
    {
        $query  = "SELECT * FROM tl_dm_blacklist";
        $result = $this->db->execute($query);

        if ($result->numRows) {
            while ($result->next()) {
                if ($result->regex) {
                    $strPattern = html_entity_decode($result->pattern);

                    if ($result->pattern != '' && preg_match('|' . $strPattern . '|i', $strMail)) {
                        // Adresse stimmt mit dem Regulaeren Ausdruck ueberein.
                        return false;
                    }
                } else {
                    if ($result->pattern != '' && substr_count($strMail, $result->pattern)) {
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
