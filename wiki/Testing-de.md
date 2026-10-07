# Tests

[English](Testing)

## Eigenständige Prüfsuite

Vom Repositoryverzeichnis aus mit PHP 8.1 oder höher starten:

```sh
php tests/regression.php runtime/deckerweb-github-release-updater-v2.php
php tests/regression.php tests/fixtures/updater-v2-baseline.php
php tests/class-loading.php tests/fixtures/updater-v2-baseline.php runtime/deckerweb-github-release-updater-v2.php public
php tests/class-loading.php runtime/deckerweb-github-release-updater-v2.php tests/fixtures/updater-v2-baseline.php private
```

Erwartet: 69 aktuelle Prüfungen, 22 ursprüngliche Public-Prüfungen und die passenden Entscheidungen bei alter/neuer Klassenladung. Die Suite simuliert WordPress und Credentials, ohne Kontozugriff.

## Echte Hostabnahme

Isolierte WordPress-Testinstallationen verwenden. Public-/Private-Assets, Source-Fallback, Einzel/Bulk/Auto, neuen Ersatztoken, echten Widerruf trotz Cache, Sprachwechsel, mehrere Hosts und alte Klassenladung prüfen. Falsche Identität, Repository, Downgrade, abweichende Angebotsversion, ungültige Version, fehlende/unpassende Anforderungen und übergroße/fehlende Hauptdatei vor Austausch abweisen. Aktivierungsstatus und Fehlerlifecycle ebenso prüfen wie den Erhalt des Codestands.

Das tatsächlich ausgelieferte Host-ZIP mit seinen Anforderungen prüfen. Unterstützte PHP-/Datenbankvarianten und Multisite berücksichtigen. Credentials außerhalb der Fixtures halten, HTTP-Monitoring redigieren. Echter Cronlauf und Fatal-Error-Rollback aktiver Plugins sind eigene Tests. Siehe [Abnahmeumfang](Validation-de).
