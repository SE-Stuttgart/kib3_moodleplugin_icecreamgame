# Icecream game / Eisverkauf Ratespiel

## English description

**German version please see below**

This plugin implements a game for guessing icecream sales. It is intended for introducing students to the topic of machine learning. Learners estimate sales in three groups. Each group represents a different typical strategy in machine learning. 

The guessing game is provided in an activity of type "icecreamgame". When viewing this material, learners see an HTML page with an embedded introductory video. After viewing the video, learners pick one of three groups. This choice is irreversible and is stored in the Moodle database as well as in the browser cache. In each of the three groups, participants receive information about expected sales, however in different form. 

Finally learners are asked to guess sales for a specific day, given the information received above. The number of guesses can be configured. Learners receive feedback about their result. 

The plugin requires configuring a webservice in Moodle. 

Installation of the plugin and configuration of the webservice are described below. 

### Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

### Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/mod/icecreamgame

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

### Configuring the web service

1. Install the plugin 
2. `Site administration` ->  scroll to section "Advanced features" -> Enable web services  `yes`
3. Create new user named 'kib3_webservice' and give user permission to create webservice tokens:
	1. Go to `Site administration` -> `Users` -> `Add a new user`
		* Name: `kib3_webservice`
		* fill out the remaining required fields in any way you want
	2. Go to `Site administration` -> `Users` -> `Define role`, then click `Add a new role`
		* Use role or archetype: `manager`
		* Click `Continue`
		* Short Name: `kib3webservice`
		* Scroll to the Capabilities section at the bottom. There, search for 
			* `webservice/rest:use` and enable it
			* `moodle/webservice:createtoken` and enable it
		* Fill out the remaining required fields as you want, and click `Create Role`
	3. Assign the new role to the user: Go to `Site administration` -> `Users` -> `Permissions` -> `Assign system roles`
		* In the list of roles, click the newly created role
		* On the next page, select the newly created user in the right hand column, and click `Add`
4. `Site administration` -> `Server` -> scroll to section "Category: Web services" -> Overview
	1. `Enable web services` -> `yes`
	2. `Enable protocols` -> `rest`
	5. `Select a service` -> `Add`
		* Name: `kib3_webservices`
		* Short name: `kib3_webservices`
		* Enabled : `yes`
		* Authorised users only: `yes`
		* Required capability: `no required capability`
		* `Add service` -> Automatic redirect to:
	6. Add functions to the service 'kib3_webservices' (if no redirect: `Site administration` -> `Server` -> `Category: Webservices` -> `Custom services` (Moodle 3.x: External Services), then click `Functions` for kib3_webservices in section `Custom services` (Moodle 3.x: External Services))
		* Click `Add functions`
		* add all functions that start with `mod_icecreamgame` (7 functions)
	7. Navigate back (`Site administration` -> `Server` -> `Category: Webservices` -> `Custom services`). In row `kib3_webservices`, click on the link `Authorised users`.
		* In the right hand column, select the webservice user that you created in step 3.1, then click `Add`
	8. Enrol the webservice user that you created in step 3.1 into the course
	9. Create a token for the webservice user: Go to `Site administration` -> `Server` -> `Category: Web Services` -> `Manage Tokens`. Click `Create Token`
		* Select the webservice user that you created in step 3.1
		* Select the service `kib3_webservices`
		* Click `Save changes`
5. Add an activity of type icecreamgame
6. Log in as as student (not as a trainer / admin - you will get a token error!)
7. Test the game

## German description / Deutsche Beschreibung

Dieses Plugin implementiert ein Ratespiel, bei dem Lernende Absatzzahlen für Eis schätzen sollen. Es dient zur Vorbereitung auf das Thema maschinelles Lernen. Die Lernenden schätzen die Absatzzahlen in drei Gruppen. Jede Gruppe repräsentiert dabei ein anderes für maschinelles Lernen typisches Vorgehen. 

Das Ratespiel ist als Aktivität namens "Eisverkauf Ratespiel" umgesetzt. Bei Anklicken von Material dieses Typs wird den Lernenden eine HTML-Seite mit einem eingebetteten einführenden Video angezeigt. Weiter unten teilen sich die Lernenden selbst einer der drei Gruppen zu. Die Auswahl ist unwiderruflich und wird sowohl in der Moodle-Datenbank als auch im Browsercache gespeichert. In jeder der drei Gruppen werden Informationen über zu erwartende Absatzzahlen präsentiert, allerdings in unterschiedlicher Form.  

Abschließend sollen die Lernenden mithilfe der erhaltenen Informationen die Absatzzahlen für einen konkreten Tag schätzen. Die Anzahl der Rateversuch kann konfiguriert werden. Die Lernenden erhalten Rückmeldung über ihr Ergebnis. 

Das Plugin erfordert die Konfiguration eines Webservice in Moodle. 

Installation und Konfiguration des Webservice sind im Folgenden beschrieben. 

### Installation mithilfe des .zip Files

1. Loggen Sie sich als Admin ein und gehen Sie zu Website-Administration -> Plugins -> Plugins installieren
2. Laden Sie das .zip-File mit dem Plugin-Code hoch. 
3. Überprüfen Sie die Überblicksseite und schließen Sie die Installation ab. 

### Manuelle Installation  ##

Das Plugin kann alternativ auch installiert werden, indem man den Inhalt der ausgepackten .zip-Datei in folgendes Verzeichnis schreibt

    {ihr/moodle/verzeichnis}/mod/icecreamgame

Anschließend bitte als Admin in Ihrem Moodle einloggen. Gehen Sie zu Website-Administration -> Systemnachrichten, um die Installation abzuschließen. 

Alternativ können Sie auch

    $ php admin/cli/upgrade.php

ausführen, um die Installation auf der Kommandozeile abzuschließen. 

### Konfiguration des Webservice

1. Plugin installieren
2. Website Administration ->  Scrolle zu Abschnitt "Zusatzoptionen" -> Webservices aktivieren -> `ja`
3.  Website Administration -> Server -> Scrolle zu Abschnitt "Bereich: Webservices" -> Übersicht 
	1. Webservices aktivieren -> `ja`
	2. Protokolle aktivieren -> `rest`
	5. Service auswählen -> Hinzufügen
		* Name: `kib3_webservices`
		* Kurzbezeichnung: `kib3_webservices`
		*  Aktiviert : `ja`
		* Nur berechtigte Personen: `nein`
		* Notwendige Rechte: `keine notwendige Rechte`
		* Service hinzufügen anklicken -> automatische Weiterleitung zu:
	6. Funktionen zum Service 'kib3_webservices' hinzufügen (falls keine Weiterleitung: Website Administration -> Server -> Webservices -> 6. Funktionen hinzufügen -> Spezifische Services (Moodle 3.x: Externe Services), dann im Abschnitt `Spezifische Services` (Moodle 3.x: Externe Services) auf `Funktionen` klicken)
		* Funktionen hinzufügen anklicken
		* Alle Funktionen, die mit `mod_icecreamgame` beginnen hinzufügen (es sind 4 Funktionen)
		* Funktionen hinzufügen anklicken
4. TODO: Neuen Nutzer anlegen der 'kib3_webservice' heißt und die Berechtigung besitzt, tokens zu generieren
5. Eine Instanz des Eiskverkauf-Spiels hinzufügen oder ein Backup wiederherstellen
6. Als Teilnehmer/in einloggen (nicht als Trainer!)
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

## Acknlowledgement / Förderhinweis

This software is developed in the project $KI$ $B^3$ -  *Künstliche Intelligenz in die Berufliche Bildung bringen*. The project is funded by the German Federal Ministry of Education and Research (BMBF) as part of the InnoVET funding line, with the Bundesinstitut für Berufsbildung (BIBB) as funding organization. The project also develops vocational training programs on Artificial Intelligence and Machine Learning (for DQR levels 4, 5, and 6). The software supports teaching in these programs. 

Diese Software wird im Rahmen des Projekts $KI$ $B^3$ -  *Künstliche Intelligenz in die Berufliche Bildung bringen* als InnoVeET-Projekt aus Mitteln des Bundesministeriums für Bildung und Forschung gefördert. Projektträger ist das Bundesinstitut für Berufsbildung (BIBB). Im Projekt werden eine Zusatzqualifikation (DQR 4) sowie zwei Fortbildungen (auf DQR5- bzw. DQR-6 Level) für KI und Maschinelles Lernen entwickelt. Die Software soll die Lehre in diesen Fortbildungen unterstützen.

