# Integration im Host Plugin

V2.1.0 wird als eingebettete Komponente mit festgelegtem Versionsstand übernommen. Das ZIP ist kein aktivierbares WordPress-Plugin. Hauptdatei, stabilen Plugin-Slug und exakte GitHub-Update-URI im jeweiligen Host prüfen. Nach dem Start der Hostübersetzungen auf init registrieren; Vorlage: examples/host-adapter.php.

## Eine Textdomain pro Host

examples/host-translations.php übernehmen, example-host durch die feste Host-Textdomain ersetzen und die literalen __()-Aufrufe in die Host-POT extrahieren. Du-/Sie-Einträge aus host-strings in die Host-PO übernehmen und deren MO-Dateien neu erstellen. Der Updater lädt keine zusätzliche Domain. Übersetzung erfolgt über den Callback pro Instanz erst bei der Ausgabe. Icons, sprachabhängige Banner, strukturierter Footer-Changelog und Wiki-Verlinkung bleiben Hostfunktionen.

## Private Repositories

Als Optionen private=true und einen AuthProvider übergeben. Der EnvironmentAuthProvider erhält die exakte Repository-URL und den Namen der sicher serverseitig provisionierten Umgebungsvariable. Nur benötigte Repositories und Contents: read auswählen; Metadata: read belassen. Token nicht in Plugin-Dateien, ZIPs, Git, Metadaten oder URLs speichern. PHP-FPM muss das Secret tatsächlich erhalten. Externes HTTP-Debugging muss Authorization-Header und signierte URLs redigieren.

Rotation: Ersatz prüfen, clear_cache() aufrufen, WordPress-Updatecache leeren und erneut prüfen; danach alten Token widerrufen und aus der Secretkonfiguration entfernen. Die private Downloadberechtigung wird trotz gültigem Metadatencache beim tatsächlichen Download geprüft.

## Klassenladung und Paketprüfung

Die zuerst geladene V2-Kopie bestimmt die gemeinsame Klasse. Vor neuen Optionen SUPPORTS_PRIVATE_REPOSITORIES und SUPPORTS_HOST_TRANSLATIONS prüfen. Alte Klassen nicht neu deklarieren; private Integration bei fehlender Fähigkeit mit verständlicher Hostmeldung abbrechen. Gemeinsame Host-Versionen abstimmen.

Die Engine prüft Hauptdatei und Archivverzeichnis. Die vollständige Identitäts-, Update-URI-, Angebotsversions- und WP-/PHP-Prüfung gehört zum Hostadapter und muss vor Austausch erfolgen. Einzel- und Bulkpfad berücksichtigen: Core lässt bei Bulk je Paket type/action weg. Nur matching Pluginbasename und tatsächlicher Plugin_Upgrader mit bulk=true ohne widersprechende Felder legitimieren diesen Sonderfall. Vorlage: examples/host-update-context.php.

Aktivierung, Reaktivierung, Uninstall und Fehlerlifecycle je Host prüfen. Der Updater aktiviert keine Plugins und schaltet keine automatischen Updates ein. Beispiele sind Integrationsvorlagen und ersetzen keine vorhandenen Host-Sicherungen.

Den freigegebenen Stand zunächst auf Testinstallationen des jeweiligen Hosts integrieren. Für jedes Hostrelease Public/Private, Einzel/Bulk/Auto, Locale-Wechsel, fehlerhafte Pakete sowie parallele ältere/neue Updaterkopien prüfen. Die vollständige technische Anleitung steht in INTEGRATION.md; die Abnahmegrenzen in VALIDATION.md.
