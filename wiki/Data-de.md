# Daten und externe Verbindungen

[English](Data)

## Metadaten und Cache

Stabile GitHub-Releases liefern Version, dauerhaften Paketendpunkt, Release-Notizen und Veröffentlichungsdatum. Repositorybezogene Site-Transients speichern erfolgreiche Metadaten 30 Minuten, Fehlschläge 10 Minuten. Private verwendet einen eigenen Schlüsselsuffix. In Multisite gilt der aktuelle Netzwerk-Scope; das isoliert Zugangsdaten nicht je Unterseite. Übersetzte Oberflächentexte, Tokens und temporäre signierte Download-URLs werden nicht gespeichert.

## Verbindungen und Secrets

Updateprüfungen kontaktieren api.github.com. Paketdownloads können zu zugelassenen GitHub-Downloadhosts führen; diese sehen die anfragende IP-Adresse. Private API-Anfragen enthalten das Provider-Credential, das nicht an Redirecthosts weitergegeben wird. Die Engine enthält keine Analysefunktion und verlangt für Public keine Credentials. Core-/Hostanfragen sind getrennt zu betrachten.

Geschützte Installationskonfiguration verwaltet das Secret. Privilegierter PHP-Code und HTTP-Debug-Hooks können Anfragen sehen; Monitoring muss Authorization und signierte URLs redigieren.

## Aktivierung und Deinstallation

Aktivierung, Deaktivierung, Reaktivierung und Uninstall verantwortet der Host. Die Komponente fügt weder Einstellungsseite noch eigene Datenbanktabellen hinzu. clear_cache() bereinigt Repository-Metadaten; Secrets werden in der Installationskonfiguration entfernt. Gemeinsam genutzte Caches nicht ohne abgestimmte Eigentümerschaft löschen. Deinstallation widerruft keinen GitHub-Token; das ist ein eigener Vorgang.
