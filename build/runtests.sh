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
        ;;

    *)          # unknown option
        myerror "Parameter [${1}] unbekannt!"
        ;;
    esac
    shift
done


## Variablen
error=0
tmperr=0
#SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )
configFolder="./build"
toolFolder="${configFolder}/tools"
classesFolder="./Classes"


## phpcpd
if [ -f ${toolFolder}/phpcpd ]
then
    myecho "Prüfe auf doppelten Code"
    if [ "${VERBOSE}" == "TRUE" ]
    then
        ${toolFolder}/phpcpd ${classesFolder}
        tmperr=$?
    else
        ${toolFolder}/phpcpd ${classesFolder} 1>/dev/null
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

echo

## PHPUnit
if [ -f ../../../vendor/bin/phpunit ]
then
    # PHPUnit gobal mit composer installiert
    myecho "Führe UnitTests mit globalem PHPUnit durch"
    XDEBUG_MODE=coverage ../../../vendor/bin/phpunit --configuration ${configFolder}/phpunit/phpunit.xml.dist --testdox
    tmperr=$?

    if [ ${tmperr} -ne 0 ]
    then
        error=${tmperr}
        myerror "Es ist ein Fehler ausgetreten [${tmperr}]"
    fi
else
    myinfo "Ausführen der UnitTests ausgelassen. PHPUnit nicht vorhanden!"
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
    myecho ">>>>>>>>>>>>>>>>>>>>>>> Es sind keine Fehler aufgetreten <<<<<<<<<<<<<<<<<<<<<<<"
    echo
    exit 0
fi
