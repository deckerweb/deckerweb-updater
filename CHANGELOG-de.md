# Changelog

[English](CHANGELOG.md)

### 2.2.1 · 2026-10-21

- **Behoben:** Aktualisierte Host-Einbindungen verwenden ihren festgelegten Updater auch dann, wenn ein anderes Plugin bereits eine ältere V2-Implementierung geladen hat.

- **Verbessert:** Release-Metadatencaches sind nach Implementierungsversion, Repository und öffentlichem beziehungsweise privatem Modus getrennt.

### 2.2.0 · 2026-10-21

- **Neu:** Optionale öffentliche Kataloganbieter prüfen die aktuelle Release-Freigabe und die Prüfsumme des heruntergeladenen Pakets.

- **Verbessert:** Ein gemeinsamer, ausblendbarer Admin-Hinweis hilft bei möglichen Konflikten mit aktivem Git Updater.

- **Behoben:** Konfigurationsfehler werden maskiert ausgegeben, ungültige Updatekontexte ignoriert und öffentliche Paket-URLs strenger geprüft.

- **Sonstiges:** Ergänzte interne API-Dokumentation und Übersetzungsvorlagen für Host-Plugins.

### 2.1.0 · 2026-10-06

- **Neu:** Private GitHub-Updates über einen repositorygebundenen Authentifizierungsanbieter.

- **Neu:** Updater-Meldungen verwenden die bestehende Host-Textdomain, einschließlich deutscher Du-/Sie-Kataloge.

- **Verbessert:** Authentifizierte Release-Assets und Source-Archive nutzen denselben nativen WordPress-Updateablauf.

- **Verbessert:** Tokenrotation unterstützt die ausdrückliche Invalidierung des Metadatencaches.

- **Behoben:** Private Downloads und Archivnormalisierung berücksichtigen den Core-Bulk-Kontext je Paket.

- **Sonstiges:** Bestehende Public-Aufrufe, lokale Grafiken und Releaseinformationen bleiben erhalten.
