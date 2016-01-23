<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Language file for tool_userrestore, EN
 *
 * File         tool_userrestore.php
 * Encoding     UTF-8
 *
 * @package     tool_userrestore
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @translator  GERMAN translation: Guido Hornig hornig@lernlink.de
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['pluginname'] = 'User Restore';

$string['promo'] = 'Benutzerweiderherstellung für  Moodle';
$string['promodesc'] = 'Dieses Plugin wurde von Sebsoft Managed Hosting & Software Development erstellt. Deutsche Übersetzeung: www.lern.link.de
    (<a href=\'http://www.sebsoft.nl/\' target=\'_new\'>http://sebsoft.nl</a>).<br /><br />
    {$a}<br /><br />';

$string['timedeleted'] = 'Gelöscht am';
$string['deletedby'] = 'Geöscht von';
$string['hasloginfo'] = 'Log verfügbar?';

$string['link:restore'] = 'Benutzer wiederherstellen';
$string['link:log'] = 'Log-Daten wiederherstellen';
$string['link:viewstatus'] = 'Zeige Statusliste';
$string['link:log:overview'] = 'Zeige Statusänderungs-Log';
$string['link:currentstatus:overview'] = 'Zeige aktuelle Statusänderung';

$string['form:label:sendmail'] = 'E-Mail senden';
$string['form:label:email'] = 'E-Mail Text';
$string['form:label:subject'] = 'E-Mail Betreff';
$string['button:userrestore:continue'] = 'Restore users';
$string['form:static:email:template'] = 'You can use the following template strings in your email.
They will automatically be replaced with the correct variables. Please use the exactly as indicated, or the result might be unexpected.
Die folgenden Platzhalter können im E-Mail Text genutzt werden. Sie werden automatisch mit durch die aktuellen Inhaltern ersetzt. Schreibweise bitte exakt einhalten.
<ul>
<li>{firstname} - Vorname des wiederhergestellten Benutzers</li>
<li>{lastname} - Nachname des wiederhergestellten Benutzers</li>
<li>{fullname} - Zusammengesetzter Name des wiederhergestellten Benutzers</li>
<li>{username} - Anmeldename des wiederhergestellten Benutzers (ist ggf. anders als zu Löschzeitpunkt)</li>
<li>{signature} - Signatur (Name, der Support-Person der moodle-Installation.)</li>
<li>{contact} - Support E-Mail  (Support-E-Mail der moodle-Installation.)</li>
<li>{loginlink} - Login-link der moodle-Installation (mit Anmeldename)</li>
</ul>
';

$string['restoresettings'] = 'Benutzer Wiederherstellungs Einstellungen';
$string['restoresettingsdesc'] = '';
$string['setting:enablecleanlogs'] = 'Log-Datei aufräumen';
$string['setting:desc:enablecleanlogs'] = 'Aktiviert/deaktiviert automatisches löschen der Wiederherstellungshistorie.';
$string['setting:cleanlogsafter'] = 'Wiederherstellungshistorie regelmäßig löschen';
$string['setting:desc:cleanlogsafter'] = 'Einstellungen zur löschung der Wiederherstellunghistorie. Historie wird unwiederruflich gelöscht.';
$string['config:cleanlogs:disabled'] = 'Automaische Löschung der Wiederherstellungshistorie ist allgemein abgeschaltet.';

$string['page:view:restore.php:introduction'] = 'Dieses Formular ermöglicht die Auswahl von gelöschten Benutzerdaten zur Wiederherstellung und Zusendung einer Info-Mail. Beachten:Die Daten werden aus dem Event.Log erzeugt.';
$string['page:view:log.php:introduction'] = 'Diese Tabelle zeigt den  Statusbericht zu den wiederhergestellten Benutzerdaten.';

$string['config:tool:disabled'] = 'Die Funktion Benutzerdaten-Wiederherstellung ist grundsätzlich abgeschaltet.';

$string['err:statustable:set_sql'] = 'set_sql() is disabled. This table defines it\'s own and is not customomizable';
$string['label:users:potential'] = 'Potentielle Benutzerdaten';
$string['restore:username-exists'] = 'Nicht wiederherstellbar: Anmeldename \'{$a->username}\' wird von einem aktiven Benutzer verwendet';
$string['restore:email-exists'] = 'Nicht wiederherstellbar: E-Mail-Adresse \'{$a->email}\' wird von einem aktiven Benutzer verwendet';
$string['restore:user-mnet-not-local'] = 'Nicht wiederherstellbar: MNET host für diesen Benutzer ist kein MNET-Host';
$string['restore:user-restored'] = 'Benutzer <i>\'{$a->username}\'</i> (\'{$a->email}\') wurde wiederhergestellt';
$string['restore:deleted-user-not-found'] = 'Nicht wiederherstellbar: Gelöschter Benutzer wurde nicht gefunden';

$string['th:userid'] = 'User ID';
$string['th:name'] = 'Name';
$string['th:restored'] = 'Wiederhergestellt';
$string['th:mailsent'] = 'E-Mail gesendet';
$string['th:mailedto'] = 'E-Mail an';
$string['th:timecreated'] = 'Erzeugt am';
$string['th:action'] = 'Aktion';

$string['button:backtocourse'] = 'Zurück zum Kurs';
$string['button:backtoform'] = 'Zurück zum Wiederherstellungsformular';

$string['email:user:restore:subject'] = 'Ihre Benutzerdaten wurden wiederhergestellt';
$string['email:user:restore:body'] = '<p>Guten Tag {fullname}</p>
<p>Nach einer Löschung wurden Ihre Benutzerdaten wiederhergestellt.</p>
<p>Evtl. wurde Ihr Benutzername nicht korrekt wiederhergestellt, da alle Daten aus Log-Dateien erzeugt werden und möglicherweise nicht alle Log-Daten lange genug aufbewahrt wurden, um eine vollständige Rekonstruktion zu ermöglichen.</p>
<p>Ihr aktueller Anmeldename ist: {username}</p>
<p>Wenn die Daten nicht stimmen oder andere Fragen entstanden sind, wenden Sie sich bitte an: {contact}.</p>
<p>Wenn Ihr bisheriges Kennwort nicht mehr funktioniert,<br/> dann nutzen Sie Ihre E-Mailadresse für die Zurücksetzung des Kennworts.<br/>
{loginlink}</p>
<p>Mit freundlichen Grüßen<br/>{signature}</p>';
$string['table:logs'] = 'Log-Daten';
$string['table:log:all'] = 'Wiederherstellungshistorie';
$string['table:log:latest'] = 'Aktuelle Wiederherstellungshistorie' ;
$string['task:logclean'] = 'Wiederherstellungshistorie aufräumen';
$string['msg:no-users-to-restore'] = 'keine Wiederherstellungshistorie gefunden.';