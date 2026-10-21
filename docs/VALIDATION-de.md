# Validierung und Abnahmegrenzen

[English](VALIDATION.md)

Version 2.1.0 wurde in isolierten Testumgebungen geprüft. 69 eigenständige Regression-/Lokalisierungsprüfungen bestehen unter PHP 8.4.5 und 8.1.29; der ursprüngliche V2-Stand besteht seine 22 Public-Prüfungen. Alte und neue Klassenladung wurden getrennt geprüft.

Private Release-Assets und Source-Archive wurden über Einzel-, Sammel- und direkte automatische WordPress-Updates geprüft. Ein eingeschränkter Fine-grained PAT genügt für die geprüften Endpunkte. Rotation funktioniert; der tatsächlich widerrufene alte Token erhält trotz gültigem Cache HTTP 401. Der Ersatz aktualisiert weiterhin erfolgreich. Downloadhosts erhalten keinen Authorization-Header.

Acht echte Hostkatalogprüfungen decken zwei getrennte Textdomains und en_US/de_DE/de_DE_formal einschließlich Sprachwechsel ab. 66 echte Core-Paketprüfungen decken Single-Site/SQLite, Multisite/SQLite und Single-Site/MySQL 8.0.35 ab, jeweils im Einzel-/Bulkpfad. Gültige Pakete werden installiert; zehn falsche oder ungeeignete Varianten behalten den alten Codestand. Manche Anforderungen weist Core bereits vor der Hostprüfung ab.

Private Multisite-Assetupdates bestehen unter WordPress 7.1.2/PHP 8.4.5, Bulk zusätzlich unter PHP 8.1.29. Die Einzelupdate-Reaktivierung wurde vom Testaufrufer durchgeführt. Private MySQL-8.0.35-Updates bestehen unter PHP 8.4.5/8.1.29; Source-Bulk-/Auto-Pfade wurden zusätzlich auf WordPress 6.7 geprüft. Public-/Private-Artwork und Auth-Verhalten bleiben getrennt; Unterseiten teilen den Netzwerkcache.

Vollständige Paketprüfungen bleiben Hostverantwortung. Die privaten Downloadfixtures und Hostpaketfixtures waren getrennt; dies ist keine vollständige Abnahme eines produktiven Host-Plugins. Direkte Auto-Updater-Aufrufe belegen keinen echten Cronlauf oder aktiven Fatal-Error-Rollback. Konkrete Hostintegrationen, kombinierte MySQL-Multisite und vollständiger Lifecycle benötigen eigene Tests. MySQL 8.4 wurde wegen eines Initialisierungsfehlers der lokalen Laufzeit nicht abgenommen; 8.0 besteht.

Zugangsdaten, private Betriebswrapper und Testdatenbanken werden nicht veröffentlicht. Die mitgelieferten Simulationstests laufen ohne Konto oder Netzwerk.


## Abnahme von 2.2.0

Alle 84 Regressions-/Sicherheits-/Provider-Prüfungen bestehen mit PHP 8.1.29 und 8.4.5. Private Asset-Einzelupdates und Source-Archiv-Sammelupdates wurden mit dem Testrepository unter WordPress 7.1.2 wiederholt. Hinweisprüfungen decken WordPress 6.7 und 7.1.2, Einzelwebsite und Multisite sowie PHP 8.1.29 ab. Der tatsächliche Quellverzeichnis-Callback von Git Updater 14.4.2 weist einen eigenen Installationsnonce ab, erhält eine fremde Quelle mit gültigem nativem Updatekontext aber unverändert; dies ist keine vollständige Ende-zu-Ende-Abnahme der Git-Updater-Kompatibilität. Plugin Check 2.1.0 mit CLI-Laufzeit-Bootstrap meldet im eingebetteten Updater 2.2.0 des geprüften Hosts keine Befunde. Meldungen in anderen Hostbestandteilen sind separat zugeordnet. Diese Prüfungen ersetzen weder konkrete Host-Paketprüfungen noch begründen sie eine neue allgemeine WordPress-Untergrenze.
