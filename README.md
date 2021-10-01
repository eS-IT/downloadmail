# DownloadMail v2

DownloadMail v2 ist eine Erweiterung für das Open Source CMS Contao, die den Download einer geschützten Datei nach dem
Ausfüllen eines Formulars ermöglicht. Das Formular kann im Formulargenerator frei gestalltet werden. Die eingegebenen
Daten stehen im Backend zur Verfügung. __Diese Version benötigt Contao 4, für Contao 3 bitte die erste Version verwenden.__


## Requirements

- PHP >= 7.3
- Contao >= 4.4


## Copyright

2018 by e@sy Solutions IT <info@easySoltionsIT.de>


## Support

Wenden Sie sich für Fragen oder Anregungen bitte einfach an:

info@easySolutionsIT.de


## Licence

CC-BY-SA-4.0

https://creativecommons.org/licenses/by-sa/4.0/deed.de


## Installation

Im Contao Manager nach `eS-IT/downloadmail` suchen und installieren.


## Einrichtung

### Formulare

In der neuen Version wird kein vorgefertigtes Formular mehr ausgegeben. Es können nun beliebige Formulare aus dem
Formulargenerator verwendet werden. Bei den gewünschten Formularen muss in der Einstellungen der Formulare der Haken
__Formular für DownloadMail__ gesetzt sein. Hier können dann auch die Einstellungen für die Downloads getätigt werden.

Die __Weiterleitungsseite__ des Formulars verweist auf die Seite, die angezeigt wird, wenn das Formular abgesendet
wird.

Die __Downloadseite__ ist die Seite, auf die der Downloadlink in der E-Mail verweist. Auf dieser Seite muss das Modul
vom Typ __easy_Downloadmail__ eingebunden werden.

### Formularfeld

Das Formular muss ein Textfeld enthalten, in dem der Haken __E-Mail-Adresse für DownloadMail__ gesetzt ist,
enthalten. Als Eingabeprüfung kann hier __E-Mail-Adresse__ verwendet werden, dann sind alle Adressen erlaubt. Wird
als Eingabeprüfung stattdessen __E-Mail-Blacklist__ verwendet, wird auch geprüft, ob es sich um eine gültige
E-Mail-Adresse handelt, zusätzlich wird sie aber auch gegen die Blacklist geprüft (s. Blacklist, weiter unten).

__Es sollte in jedem Formularfeld nur ein Formularfeld mit dem Haken "E-Mail-Adresse für DownloadMail" geben,
da nur die erste E-Mailadresse berücksichtigt wird!__

### Modul

Das Modul vom Typ __easy_Downloadmail__ muss auf der Downloadseite eingebunden werden. Es kümmert sich um die Prüfung
und das Bereitstellen des Downloads.

### Einstellungen

Es gibt mehrere Stellen an denen die Einstellungen gesetzt werden können. Die Einstellungen werden in den
Contao-Setting global für alle Formulare auf allen Seiten gesetzt. Diese können in der Rootpage der entsprechenden
Seite überschrieben, bzw. ergänzt werden. Diese Einstellungen gelten nur für die Formulare auf Unterseiten der
entsprechenden Rootpage. Dann gibt es noch die Möglichkeit die Einstellungen direkt im Formular zu setzen. Diese
gelten dann nur für das eine Formular.

| Name der Einstellung | Erklärng |
| -------------------- | -------- |
| __Gültigkeitsdauer__ | Dauer, in der die Datei nach der Anfrage runter geladen werden kann. _Vorgabewert: 12 Stunden_ |
| __Zeit bis zum Start des Downloads__ | Zeit die nach dem Aufruf der Downloadseite gewartet wird, bis der Download automatisch startet. _Vorgabewert: 5 Sekunden_ |
| __Absender__ | Absendeadresse für die Mail mit dem Downloadlink. |
| __Betreff__ | Betreff der Mail mit dem Downloadlink. |
| __Empfänger der Bildkopie__ | Hier können Empfänger eingetragen werden, die die Mail mit dem Downloadlink auch erhalten sollen, z.B. der Admin, oder Redakteur. |
| __Mailtext__ | Text der Mail mit dem Downloadlink. Die Formularfelder können mit den InsertTags `{{download::FELDNAME}}` ausgegeben werden. Der Link für den Download kann mit `{{download::link}}` ausgegeben werden. |
| __Downloadseite__ | Seite auf die der Downloadlink zeigt. |
| __TinyMCE verwenden__ | Wird der TinyMce aktiviert, wird er in den Einstellungen eingeblendet, und es werden HTML-Mails statt Textmails erzeugt. |
| __Quelldatei__ | Die Datei, die über den Link aus der E-Mail runter geladen werden kann. |

- __TinyMCE verwenden__ kann nur in den Contao-Settings eingestellt werden.
- Die __Quelldatei__ kann nur direkt im Formular festgelegt werden.


## Blacklist

Hier können E-Mail-Adressen oder reguläre Ausdrücke für E-Mail-Adressen angegeben werden, die für Downloads nicht
verwendet werden können (z.B. Einmaladressen wie byom.de). Wird eine solche E-Mail-Adresse im Formular eingegeben, wird
im Formular eine entsprechende Fehlermeldung ausgegeben.

Damit dies funktioniert, muss im betreffenden Formularfeld (s. Formularfeld weiter oben) als Eingabeprüfung
__E-Mail-Blacklist__ verwendet werden.


## Downloads

Unter dem Menüpunkt __Downloads__ können im Backend die Anfragen und die getätigten Download eingesehen werden. Klick
man in der Liste auf die Lupe, werden Metadaten, die Zeiten und anonymisierten IPs zu den einzelnen Downloads angezeigt.
Dort ist auch ein Button __Anforderungszeit zurücksetzen__. Dieser setzt die Anforderungszeit auf die aktuelle Zeit,
so dass über den gleichen Link auch nach Ablauf der Gültigkeitsdauer wieder die entsprechende Datei runter geladen
werden kann.
