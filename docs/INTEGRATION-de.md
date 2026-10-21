# Integration im Host Plugin

V2.2.0 wird als eingebettete Komponente mit festgelegtem Versionsstand übernommen. Das ZIP ist kein aktivierbares WordPress-Plugin. Hauptdatei, stabilen Plugin-Slug und exakte GitHub-Update-URI im jeweiligen Host prüfen. Nach dem Start der Hostübersetzungen auf init registrieren; Vorlage: examples/host-adapter.php.

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


## Öffentliche Kataloganbieter

Version 2.2.0 stellt SUPPORTS_CATALOG_PROVIDER bereit. Übergib beide Callbacks release_provider und package_provider nur für öffentliche Repositories in den Optionen. Der Release-Callback erhält (Repository-URL, Plugin-Basisdatei, fresh als booleschen Wert). false ist ausschließlich für einen ausdrücklich deaktivierten Katalog mit direktem GitHub-Modus vorgesehen; null steht für fehlende oder verweigerte Freigabe. Ein freigegebenes Array enthält version (drei numerische Bestandteile), package (Release-Asset-URL desselben Repositories), sha256 (64 kleine Hexadezimalzeichen) sowie optional notes, published, requires_wp und requires_php als Zeichenketten. Verweigerung und Provider-Ausnahmen führen nicht still zu einem direkten Download.

Der Paket-Callback erhält (Repository-URL, Plugin-Basisdatei, Paket-URL) und liefert einen temporären ZIP-Pfad oder WP_Error. Er ist für sicheren Download und Bereinigung fehlgeschlagener temporärer Dateien verantwortlich. Vor dem Vorgang fragt der Updater die Freigabe frisch ab, prüft die unveränderte Angebots-URL und kontrolliert unabhängig die SHA-256-Prüfsumme der gelieferten Datei. Provider-Fehler werden durch feste Texte ohne Zugangsdaten oder Ausnahmedetails ersetzt. Die Identitäts- und Anforderungsprüfungen des Hosts bleiben erforderlich. Im Private-Modus sind Katalog-Callbacks nicht zulässig.

## Zusammenspiel mit Git Updater

Der Updater erkennt aktive Git-Updater- und historische GitHub-Updater-Identitäten lokal. Berechtigte Administratoren sehen auf Plugin-, Update- und Library-Seiten einen gemeinsamen Hinweis; seine Ausblendungskennung wird mit der Library geteilt. Das Ausblenden gilt je Nutzer und erkanntem Versions-/Aktivierungsstand. Es erfolgen weder Netzwerkabfragen zur Erkennung noch automatische Deaktivierungen. Bei einem fehlgeschlagenen Vorgang deaktiviere Git Updater vorübergehend und versuche es erneut. Aktiviere ihn danach wieder, wenn andere Erweiterungen ihn benötigen. Eine dauerhafte Deaktivierung ist erst sinnvoll, wenn alle betroffenen Plugins und Themes einen anderen zuverlässigen Updateweg besitzen. Der Hinweis garantiert keine Kompatibilität und verändert keine fremden Hooks.
