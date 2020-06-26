#!/usr/bin/env bash
#
# =============================================================================
#title:         runtests.sh
#description:   Führt die Tests der Softwate aus
#author:        pfroch <patrick.froch@easySolutionsIT.de>
#date:          20180819
#version:       1.0.0
#usage:         runtests.sh
# =============================================================================
#

BUILD_FULLPACKAGE="false";


## Ausgabe
function myecho() {
    if [ "${VERBOSE}" == "TRUE" ]
    then
        echo -e "\e[1;96m\n================================================================================"
        echo -e "${1}"
        echo -e "--------------------------------------------------------------------------------\n\e[0m"
    fi
}

function myinfo() {
    if [ "${VERBOSE}" == "TRUE" ]
    then
        echo -e "\e[0;37m\n================================================================================"
        echo -e "${1}"
        echo -e "--------------------------------------------------------------------------------\n\e[0m"
    fi
}

function myerror() {
    if [ "${VERBOSE}" == "TRUE" ]
    then
        echo -e "\n\e[1;91m================================================================================\e[0m"
        echo -e "\e[0;101m\u2717 ${1}\e[0m"
        echo -e "\e[1;91m--------------------------------------------------------------------------------\e[0m"
    else
        echo -e "\e[0;101m\u2717 ${1}\e[0m"
    fi
}

function myshortecho() {
    if [ "${VERBOSE}" != "TRUE" ]
    then
        echo -e "\e[0;92m\u2713 ${1}\e[0m"
    fi
}


##
# Header
#
echo -e "\e[1;96m\n================================================================================"
echo -e "e@sy Solutions IT - Test Suite by Patrick Froch - Version: 1.0.1"
echo -e "--------------------------------------------------------------------------------\n\e[0m"


##
# Parameters
##
while [ $# -gt 0 ]
do
    case ${1} in
    -v|--verbose)
        VERBOSE="TRUE"
        #shift  # Kein shift, da kein Wert übergeben wird!
        ;;

    *)          # unknown option
        myerror "Parameter [${1}] unbekannt!"
        #shift  # Kein shift, da kein Wert übergeben wird!
        ;;
    esac
    shift
done


## Variablen
error=0
tmperr=0
configFolder='./build'
toolFolder="${configFolder}/tools"
classesFolder='./Classes'


## generate CHANGELOG.txt
if [ -f /home/pfroch/bin/gitchangelog ]
then
    myshortecho "Erstelle CHANGELOG.txt"
    /home/pfroch/bin/gitchangelog `pwd`
else
    myshortecho "/home/pfroch/bin/gitchangelog nicht gefunden!"
fi


## validate compser.json
if [ -f ${toolFolder}/composer.phar ]
then
    myecho "Prüfe comopser.json (verwende ${toolFolder}/composer.phar)"

    if [ "${VERBOSE}" == "TRUE" ]
    then
        COMPOSER_MEMORY_LIMIT=-1 ${toolFolder}/composer.phar diagnose
        tmperr=$?
    else
        COMPOSER_MEMORY_LIMIT=-1 ${toolFolder}/composer.phar diagnose &>/dev/null
        tmperr=$?
    fi

    if [ ${tmperr} -gt 1 ]
    then
        error=${tmperr}
        myerror "Es ist ein Fehler ausgetreten [${tmperr}]"
    else
       myshortecho "Prüfung des Schemas in der Datei composer.json erfolgreich"
    fi
else
    myinfo "Prüfen des Schemas der composer.json ausgelassen. composer.phar nicht vorhanden!"
fi


## phpcf
if [ -f ${toolFolder}/phpcf ]
then
    myecho "Prüfe Kompatibilität zu PHP 7.0"
    if [ "${VERBOSE}" == "TRUE" ]
    then
        ${toolFolder}/phpcf -t 7.0 ${classesFolder}
        tmperr=$?
    else
        ${toolFolder}/phpcf -t 7.0 ${classesFolder} &>/dev/null
        tmperr=$?
    fi

    if [ ${tmperr} -ne 0 ]
    then
        error=${tmperr}
        myerror "Es ist ein Fehler ausgetreten [${tmperr}]"
    else
       myshortecho "Prüfung Kompatibilität zu PHP 7.0 erfolgreich"
    fi

    myecho "Prüfe Kompatibilität zu PHP 7.1"
    if [ "${VERBOSE}" == "TRUE" ]
    then
        ${toolFolder}/phpcf -t 7.1 ${classesFolder}
        tmperr=$?
    else
        ${toolFolder}/phpcf -t 7.1 ${classesFolder} 1>/dev/null
        tmperr=$?
    fi

    if [ ${tmperr} -ne 0 ]
    then
        error=${tmperr}
        myerror "Es ist ein Fehler ausgetreten [${tmperr}]"
    else
       myshortecho "Prüfung Kompatibilität zu PHP 7.1 erfolgreich"
    fi

    myecho "Prüfe Kompatibilität zu PHP 7.2"
    if [ "${VERBOSE}" == "TRUE" ]
    then
        ${toolFolder}/phpcf -t 7.2 ${classesFolder}
        tmperr=$?
    else
        ${toolFolder}/phpcf -t 7.2 ${classesFolder} 1>/dev/null
        tmperr=$?
    fi

    if [ ${tmperr} -ne 0 ]
    then
        error=${tmperr}
        myerror "Es ist ein Fehler ausgetreten [${tmperr}]"
    else
       myshortecho "Prüfung Kompatibilität zu PHP 7.2 erfolgreich"
    fi
else
    myinfo "Prüfen der Kompatibilität ausgelassen. PhpCodeFixer nicht vorhanden!"
fi


## phpcpd
if [ -f ${toolFolder}/phpcpd ]
then
    myecho "Prüfe auf doppelten Code"
    if [ "${VERBOSE}" == "TRUE" ]
    then
        ${toolFolder}/phpcpd ${classesFolder}
        tmperr=$?
    else
        ${toolFolder}/phpcpd -q ${classesFolder}
        tmperr=$?
    fi

    if [ ${tmperr} -ne 0 ]
    then
        error=${tmperr}
        myerror "Bei der Prüfung auf doppelten Code ist ein Fehler ausgetreten [${tmperr}]"
    else
       myshortecho "Prüfung auf doppelten Code erfolgreich"
    fi
else
    myinfo "Prüfen auf doppelten Code ausgelassen. PhpCopyAndPasteDetector nicht vorhanden!"
fi

## php-cs-fixer
#
# pfroch - 02.01.2019: doesn't run on php 7.3.x, we have to wait, then:
# REMOVE: PHP_CS_FIXER_IGNORE_ENV=1
#
if [ -f ${toolFolder}/php-cs-fixer ]
then
    myecho "Führe automatische Korrektur der Code-Standards mit Php-cs-fixer durch"
    if [ "${VERBOSE}" == "TRUE" ]
    then
        PHP_CS_FIXER_IGNORE_ENV=1 ${toolFolder}/php-cs-fixer --config=${configFolder}/php_cs.dist.php fix
        tmperr=$?
    else
        PHP_CS_FIXER_IGNORE_ENV=1 ${toolFolder}/php-cs-fixer -q --config=${configFolder}/php_cs.dist.php fix
        tmperr=$?
    fi

    if [ ${tmperr} -ne 0 ]
    then
       error=${tmperr}
       myerror "Es ist ein Fehler ausgetreten [${tmperr}]"
    else
       myshortecho "Automatische Korrektur der Code-Standards mit Php-cs-fixer erfolgreich"
    fi
else
   myinfo "Automatische Korrektur der Code-Standards ausgelassen. Php-cs-fixer nicht vorhanden!"
fi


## phpcs
if [ -f ${toolFolder}/phpcs ]
then
    myecho "Führe statische Code-Analyse mit PHP Codesniffer durch"
    if [ "${VERBOSE}" == "TRUE" ]
    then
        ${toolFolder}/phpcs --colors --standard=PSR2 ${classesFolder}
        tmperr=$?
    else
        ${toolFolder}/phpcs -q --colors --standard=PSR2 ${classesFolder}
        tmperr=$?
    fi

    if [ ${tmperr} -ne 0 ]
    then
        error=${tmperr}
        myerror "Es ist ein Fehler ausgetreten [${tmperr}]"
    else
        myshortecho "Statische Code-Analyse mit PHP Codesniffer erfolgreich"
    fi
else
    myinfo "Statische Code-Analyse ausgelassen. PHP Codesniffer nicht vorhanden!"
fi


## phan
#if [ -f ${toolFolder}/phan ]
#then
#    myecho "Führe statische Code-Analyse mit Phan durch"
#    if [ "${VERBOSE}" == "TRUE" ]
#    then
#        ${toolFolder}/phan --allow-polyfill-parser --ignore-undeclared --output-mode text --color --progress-bar --backward-compatibility-checks --dead-code-detection --unused-variable-detection --signature-compatibility --strict-type-checking --directory ${classesFolder}
#        tmperr=$?
#    else
#        ${toolFolder}/phan --allow-polyfill-parser --ignore-undeclared --output-mode text --color --progress-bar --backward-compatibility-checks --dead-code-detection --unused-variable-detection --signature-compatibility --strict-type-checking --directory ${classesFolder} 1>/dev/null
#        tmperr=$?
#    fi
#
#    if [ ${tmperr} -ne 0 ]
#    then
#        error=${tmperr}
#        myerror "Es ist ein Fehler ausgetreten [${tmperr}]"
#    fi
#else
#    myinfo "Statische Code-Analyse ausgelassen. Phan nicht vorhanden!"
#fi

echo

## PHPUnit
if [ -f ${toolFolder}/phpunit ]
then
    # PHPUnit als Phar in build installiert
    myecho "Führe UnitTests mit Phar PHPUnit durch"
    ${toolFolder}/phpunit --configuration ${configFolder}/phpunit/phpunit.xml.dist --testdox
    tmperr=$?

    if [ ${tmperr} -ne 0 ]
    then
        error=${tmperr}
        myerror "Es ist ein Fehler ausgetreten [${tmperr}]"
    fi
else
    if [ -f ../../../vendor/bin/phpunit ]
    then
        # PHPUnit gobal mit composer installiert
        myecho "Führe UnitTests mit globalem PHPUnit durch"
        ../../../vendor/bin/phpunit --configuration ${configFolder}/phpunit/phpunit.xml.dist --testdox
        tmperr=$?

        if [ ${tmperr} -ne 0 ]
        then
            error=${tmperr}
            myerror "Es ist ein Fehler ausgetreten [${tmperr}]"
        fi
    else
        myinfo "Ausführen der UnitTests ausgelassen. PHPUnit nicht vorhanden!"
    fi
fi


## Zusammenfassung
if [ ${error} -ne 0 ]
then
    if [ "${VERBOSE}" != "TRUE" ]
    then
        echo
    fi

    myerror ">>>>>>>>>> Bei der Verarbeitung der Tests sind Fehler aufgetreten ! <<<<<<<<<<"
    echo
    exit 127
else
    if [ -f /home/pfroch/bin/buildfullpackage ] && [ "$BUILD_FULLPACKAGE" != "false" ]
    then
        # Installationsarchiv erstellen
        echo "Erstelle installationsarchiv"
        /home/pfroch/bin/buildfullpackage
    fi

    myecho ">>>>>>>>>>>>>>>>>>>>>>> Es sind keine Fehler aufgetreten <<<<<<<<<<<<<<<<<<<<<<<"
    echo
    exit 0
fi
