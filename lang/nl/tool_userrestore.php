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
 * Language file for tool_userrestore, NL
 *
 * File         tool_userrestore.php
 * Encoding     UTF-8
 *
 * @package     tool_userrestore
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['pluginname'] = 'Gebruikersherstel';

$string['promo'] = 'Gebruikersherstel plugin voor Moodle';
$string['promodesc'] = 'Deze plugin is ontwikkeld door Sebsoft Managed Hosting & Software Development
    (<a href=\'http://www.sebsoft.nl/\' target=\'_new\'>http://www.sebsoft.nl</a>).<br /><br />
    {$a}<br /><br />';

$string['timedeleted'] = 'Verwijderd op';
$string['deletedby'] = 'Verwijderd door';

$string['link:restore'] = 'Gebruikers herstellen';
$string['link:log'] = 'Gebruikersherstel logs';
$string['link:viewstatus'] = 'Status overzicht inzien';
$string['link:log:overview'] = 'Statiswijzigingen inzien';
$string['link:currentstatus:overview'] = 'Huidige statussen inzien';

$string['form:label:sendmail'] = 'E-mail verzenden?';
$string['form:label:email'] = 'E-mail tekst';
$string['form:label:subject'] = 'E-mail onderwerp';
$string['button:userrestore:continue'] = 'Herstel gebruikers';
$string['form:static:email:template'] = 'Je kunt de volgende template variabelen in je email gebruiken.
Ze zullen automatisch worden vervangen door de correcte waarden. Gebruik ze alsjebielft exact zoals aangegeven, anders zijn de resultaten mogelijk anders dan verwacht.
<ul>
<li>{firstname} - Voornaam van de te herstellen gebruiker</li>
<li>{lastname} - Achternaam van de te herstellen gebruiker</li>
<li>{fullname} - Volledige naam van de te herstellen gebruiker</li>
<li>{username} - Gebruikersnaam voor de herstelde gebruiker (deze KAN afwijken van voordat het account werd verwijderd)</li>
<li>{signature} - Ondertekening (volledige naam van de support account)</li>
<li>{contact} - Contact emailadres (op basis van van de support account)</li>
<li>{loginlink} - Link om op de site in te loggen (op basis van herstelde gebruikersnaam)</li>
</ul>
';

$string['restoresettings'] = 'Instellingen voor gebruikersherstel';
$string['restoresettingsdesc'] = '';
$string['setting:enablecleanlogs'] = 'Inschakelen logopschoning';
$string['setting:desc:enablecleanlogs'] = 'Schakelt automatisch opschonen van historische logs aan of uit.';
$string['setting:cleanlogsafter'] = 'Frequentie logopschoning';
$string['setting:desc:cleanlogsafter'] = 'Configureer de frequentie waarop historische logs worden opgeschoond. Alle logs ouder dan de ingegeven waarde zullen fysiek verwijderd worden.';
$string['config:cleanlogs:disabled'] = 'Automatisch opschonen van logs is uitgeschakeld in de globale configuratie';

$string['page:view:restore.php:introduction'] = 'Dit formulier stelt je in staat gebruikersaccounts te herstellen en de gebruikers
per email te informeren over het herstel van hun account. Let op, in de onderstaande tabel stellen de gebruikersnaam en e-mailadres de originele
gegevens voor en zijn teruggehaald uit de event log gegevens.';
$string['page:view:log.php:introduction'] = 'De tabel hieronder toont de statussen van gebruikers die zijn hersteld.';

$string['config:tool:disabled'] = 'Gebruikersherstel is uitgeschakeld middels globale configuratie';

$string['err:statustable:set_sql'] = 'set_sql() is uitgeschakeld. De tabel definieert zijn eigen queries.';
$string['label:users:potential'] = 'Potentiele gebruikers';
$string['restore:username-exists'] = 'Kan gebruikersaccount niet herstellen: er is al een andere actieve gebruiker met gebruikersnaam \'{$a->username}\'';
$string['restore:email-exists'] = 'Kan gebruikersaccount niet herstellen: er is al een andere actieve gebruiker met emailadres \'{$a->email}\'';
$string['restore:user-mnet-not-local'] = 'Kan gebruikersaccount niet herstellen: mnet host voor de te herstellen gebruiker komt niet overeen met de geconfigureerde lokale mnet host';
$string['restore:user-restored'] = 'Gebruiker <i>\'{$a->username}\'</i> (\'{$a->email}\') is succesvol hersteld';
$string['restore:deleted-user-not-found'] = 'Kan gebruikersaccount niet herstellen: verwijderde gebruiker kan niet worden gevonden';

$string['th:userid'] = 'GebruikersID';
$string['th:name'] = 'Naam';
$string['th:restored'] = 'Hersteld';
$string['th:mailsent'] = 'E-mail verzonden';
$string['th:mailedto'] = 'E-mail veronden naar';
$string['th:timecreated'] = 'Aangemaakt op';
$string['th:action'] = 'Actie';

$string['button:backtocourse'] = 'Terug naar cursus';
$string['button:backtoform'] = 'Terug naar gebruikersherstel formulier';

$string['email:user:restore:subject'] = 'Je account is hersteld';
$string['email:user:restore:body'] = '<p>Beste {fullname}</p>
<p>Je gebruikersaccount is hersteld</p>
<p>Je gebruikersnaam kon mogelijk niet correct hersteld worden.
Dit is afhankelijk van hoe moodle gebruikeraccount verwijderd en of de event logs al dan niet opgeschooned zijn.
Vanaf nu is je gebruikersnaam dan ook {username}.</p>
<p>Wanneer je van mening bent dat dit onbedoeld is, of wanneer je vragen hebt,
neem dan alsjeblieft contact op met {contact}</p>
<p>Je zou moeten kunnen inloggen met je originele wachtwoord.<br/>
Als dit niet het geval is, gebruik dan het emailadres waar deze email naartoe is verzonden om een wachtwoord reset aan te vragen.<br/>
Log alsjeblieft in op de site om alle informatie weer aan te vullen door onderstaande link te volgen:<br/>
{loginlink}</p>
<p>Met vriendelijke groet,<br/>{signature}</p>';
$string['table:logs'] = 'Logs';
$string['table:log:all'] = 'Historie herstellog';
$string['table:log:latest'] = 'Laatste herstellogs';
$string['task:logclean'] = 'Opschonen logs voor gebruikersherstel';
$string['msg:no-users-to-restore'] = 'Er zijn geen verwijderde gebruikersaccounts gevonden om te kunnen herstellen.';