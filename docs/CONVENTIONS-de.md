# Release- und Pflegekonventionen

[English](CONVENTIONS.md)

Dreiteilige semantische Versionen verwenden. Kompatible Funktionen erhöhen Minor, Korrekturen Patch; inkompatible API-/Transportänderungen benötigen einen ausdrücklichen Kompatibilitätsplan. Code, Dokumente und Grafiken nur nach Check veröffentlichen. Die Übernahme durch einen Host ist eine getrennte, fest versionierte Integrationsänderung.

Öffentliche Texte zentral in docs/content.json pflegen. tools/build-docs.py erzeugt konsistente EN/DE-Readmes, FAQ, Changelogs und Wiki-Seiten. Quelle ändern, Export starten und Links/Grafiken prüfen. Generierte Dateien nicht von Hand bearbeiten. Changelog-Reihenfolge: New, Improved, Fixed, Misc; Deutsch Neu, Verbessert, Behoben, Sonstiges. Nutzerrelevantes Releaseverhalten beschreiben, keine internen Arbeitsnotizen.

Runtime-Doc-Blöcke folgen den WordPress-Dokumentationskonventionen des Projekts: Klassenverantwortung, Methodenparameter in Reihenfolge, Rückgabewerte und relevante Nebenwirkungen. Eigene Entwickler-Hooks erhalten Zweck, Parameter und Einführungsversion. Vollständigkeit gehört zum nächsten Code-/Release-Abgleich; redundante Zeilenkommentare vermeiden.

Host-/Updaterversionen, Fähigkeiten, Paketprüfungen, Übersetzungskataloge und Lifecycle abstimmen. Tatsächliche Auslieferung auf Secrets, veraltete Assets und unnötige Entwicklungsdateien prüfen. Sicherheits- und Mindestversionsänderungen bewusst prüfen. Herkunft und GPL-Lizenz erhalten.
