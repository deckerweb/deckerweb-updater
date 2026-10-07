# Authentifizierung und Token-Lifecycle

[English](AUTHENTICATION.md)

## Public und Private

Public-Anfragen bleiben ohne Authentifizierung. Private benötigt einen repositorygebundenen AuthProvider; der EnvironmentAuthProvider liest die benannte Installationsvariable bei der Anfrage. Fehlende oder syntaktisch ungültige Credentials führen zu einer kontrollierten Ablehnung. Provider-Exceptions werden nicht ausgegeben.

Einen Fine-grained PAT nur für benötigte Repositories mit Contents: read und automatisch erforderlichem Metadata: read erzeugen. Ablaufdatum wählen, gegebenenfalls Organisationsfreigabe abwarten und das Secret geschützt für den PHP-Prozess bereitstellen. Nicht ausliefern und nicht als Kommandozeilenargument verwenden. Der Host übergibt private=true, auth und seinen Übersetzungs-Callback.

## Rotation und Widerruf

Gleich eingeschränkten Ersatz erzeugen, provisionieren, Updater- und WordPress-Updatecache leeren und einen tatsächlichen Download prüfen. Danach alten Token in GitHub widerrufen und sein Secret aus der Installationskonfiguration entfernen. Ein widerrufenes Credential kann auch mit gültigem Metadatencache kein privates Paket laden. Bereits geladene Dateien werden nicht rückwirkend ungültig.

## Downloads und Provider

Private Assets verwenden den repositorygebundenen API-Endpunkt assets/ID; der Archivfallback verwendet den Release-Ref. Die Accept-Header unterscheiden sich. API-Anfragen folgen Redirects nicht automatisch. Zugelassene HTTPS-Downloadhosts erhalten keinen Authorization-Header. Signierte URLs werden bei Bedarf aufgelöst und nicht in Updatemetadaten gespeichert.

Ein GitHub-App-Provider kann denselben Tokenvertrag verwenden. Ein späterer externer Updateservice benötigt eine ausdrückliche Transportintegration; Version 2.1 enthält ihn nicht.
