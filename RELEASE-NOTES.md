## English

The deckerweb Updater 2.2.0 extends the existing V2 engine for public and private GitHub repositories.

- **New:** Optional public catalog providers with fresh release approval and independent SHA-256 verification.
- **Improved:** One shared, dismissible administrator notice helps identify potential conflicts with active Git Updater installations.
- **Fixed:** Host-translated configuration exceptions are escaped, malformed update contexts are ignored, and public package URLs are validated more strictly.
- **Misc:** Expanded inline API documentation and English/German host translation templates, including formal German.

This is an embedded component, not a standalone WordPress plugin. Integrate the pinned runtime into each host, merge its strings into the host textdomain, and retain the host’s package identity, compatibility and lifecycle checks. Existing public updates, private authentication, artwork and release information remain supported. Older embedded V2 copies still require capability checks.

PHP 8.1 or later. WordPress requirements follow the host. See the English and German integration and validation documents. Verify the attached ZIP using its SHA-256 file before integration.

## Deutsch

Der deckerweb Updater 2.2.0 erweitert die bestehende V2-Engine für öffentliche und private GitHub-Repositories.

- **Neu:** Optionale öffentliche Kataloganbieter mit frischer Release-Freigabe und unabhängiger SHA256-Prüfung.
- **Verbessert:** Ein gemeinsamer, ausblendbarer Admin-Hinweis hilft bei möglichen Konflikten mit aktivem Git Updater.
- **Behoben:** Übersetzte Konfigurationsfehler werden maskiert ausgegeben, ungültige Updatekontexte ignoriert und öffentliche Paket-URLs strenger geprüft.
- **Sonstiges:** Ergänzte interne API-Dokumentation und Übersetzungsvorlagen für Host-Plugins auf Englisch und Deutsch einschließlich Sie-Anrede.

Dies ist eine eingebettete Komponente, kein eigenständig installierbares WordPress-Plugin. Übernimm den festgelegten Runtime-Stand in den jeweiligen Host, führe seine Strings in der Host-Textdomain zusammen und erhalte die Identitäts-, Kompatibilitäts- und Lebenszyklusprüfungen des Hosts. Öffentliche Updates, private Authentifizierung, Grafiken und Release-Informationen bleiben erhalten. Bei älteren parallel eingebetteten V2-Kopien sind weiterhin Fähigkeitsprüfungen erforderlich.

PHP 8.1 oder neuer. Die WordPress-Anforderungen richten sich nach dem Host. Beachte die deutschen und englischen Einbindungs- und Abnahmehinweise. Prüfe das beigefügte ZIP vor Einbindung anhand seiner SHA256-Datei.
