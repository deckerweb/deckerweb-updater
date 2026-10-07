# Fragen nach Themen

[English](FAQ.md)

## Einstieg

### Installiere ich ein eigenes Updater-Plugin?

Nein. Der Host bindet einen festen Versionsstand ein. Nutze die üblichen Installations- und Updateschritte des Host-Plugins.

### Brauchen öffentliche Updates einen GitHub-Token?

Nein. Öffentliche Repositories bleiben ohne Authentifizierung. Nur entsprechend konfigurierte private Repositories benötigen Zugangsdaten.

## Updates

### Kann ich Updates aus einem privaten Repository erhalten?

Ja. Der Host muss den Private-Modus aktivieren und einen repositorygebundenen Provider bereitstellen. Ein Fine-grained PAT mit Contents read und Metadata read unterstützt die geprüften Release- und Archivendpunkte.

### Schaltet der Updater automatische Updates ein?

Nein. Er unterstützt die normale WordPress-Update-Engine. Die Einstellung für automatische Updates bleibt bei WordPress und dem Host.

## Einbindung

### Können mehrere Plugins V2 einbinden?

Ja, mit geschützter Klassenladung. Die zuerst geladene Klasse gilt; Private-/Übersetzungsfähigkeiten prüfen und die ausgelieferten Versionen abstimmen.

## Lokalisierung

### Gibt es eine zusätzliche Textdomain?

Nein. Updater-Strings werden in die Hostkataloge übernommen und über einen Hostcallback übersetzt.

## Sicherheit

### Was passiert nach einem Tokenwiderruf?

Private Downloads werden auch bei noch gecachten Metadaten abgelehnt. Ein abgelehnter Download ersetzt das installierte Plugin nicht; das Aktivierungsverhalten verantwortet der aufrufende Hostablauf.

## Updates

### Wann werden Metadaten aktualisiert?

Erfolgreiche Metadaten werden 30 Minuten, Fehlschläge 10 Minuten gespeichert. WordPress plant die Prüfungen; manuelles Aktualisieren und die Cacheinvalidierung des Hosts können eine neue Abfrage auslösen.

### Was passiert ohne passendes ZIP-Asset?

Die Engine verwendet das Source-Archiv des Releases, sofern gültig. Der Host muss es weiterhin als installierbares Pluginpaket prüfen.

## Einbindung

### Wer prüft das tatsächliche Paket?

Die Engine prüft die Hauptdatei und normalisiert Verzeichnisse. Vollständige Identitäts-, Angebotsversions- und Plattformprüfungen liegen beim Host, auch im besonderen Core-Bulk-Kontext.

## Sicherheit

### Wo liegt der Token?

In geschützter Installationskonfiguration, aus der ihn der Provider bei der Anfrage liest. Er darf nicht in Plugin-Dateien, öffentlichen Repositories, ZIPs, URLs oder Metadaten eingebettet sein.

## Library

### Ersetzt die Library den Host-Updater?

Nein. Kataloganzeige und Updates installierter Plugins haben getrennte Verantwortlichkeiten. Der Anzeige-Cache des Library-Katalogs darf den Prüfzyklus des Host-Updaters nicht vorgeben.
