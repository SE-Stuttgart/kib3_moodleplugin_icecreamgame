# Eisverkauf Ratespiel #

TODO Describe the plugin shortly here.

TODO Provide more detailed description here.

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/mod/icecreamgame

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## Konfiguration

1. Plugin als "Aktivität" installieren
2. Website Administration ->  Scrolle zu Abschnitt "Zusatzoptionen" -> Webservices aktivieren -> `ja`
3.  Website Administration -> Plugins -> Scrolle zu Abschnitt "Webservices" -> Übersicht 
	1. Webservices aktivieren -> `ja`
	2. Protokolle aktivieren -> `REST`
	5. Service auswählen -> Hinzufügen
		1. Name: `moodleservice`
		2. Kurzbezeichnung: `moodleservice`
		3. Aktiviert : `ja`
		4. Nur berechtigte Personen: `nein`
		5. Notwendige Rechte: `keine notwendige Rechte`
		6. Service hinzufügen anklicken
	6. Automatische Weiterleitung zu:  Funktionen zum Service 'moodleservice' hinzufügen (falls keine Weiterleitung: Website Administration -> Plugins -> Webservices -> Externe Services, dann im Abschnitt `Spezifische Services` auf `Funktionen` klicken)
		1. Funktionen hinzufügen anklicken
		2. Alle Funktionen, die mit `mod_icecreamgame` beginnen hinzufügen (es sind 4 Funktionen)
		3. Funktionen hinzufügen anklicken
4. Website Administration -> Nuter/innen ->  Scrolle zu Abschnitt "Rechte" -> Suche in Spalte "Beschreibung" nach `Alle authentifizierten Nutzer/innen auf der Website` -> Klicke "Bearbeiten"
	1. Mit Filter-Suchfeld nach `createtoken` suchen
		1. Ergebnis "moodle/webservice:createtoken" -> `Erlauben`
	2. Mit Filter-Suchfeld nach `webservice/rest` suchen
		1. Ergebnis "webservice/rest:use" -> `Erlauben`
	3. "Änderungen speichern" anklicken
5. Eine Instanz des Eiskverkauf-Spiels hinzufügen oder ein Backup wiederherstellen
6. Als Teilnehmer/in einloggen
7. Spiel testen

## License ##

2022 Universtity of Stuttgart <dirk.vaeth@ims.uni-stuttgart.de>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.
