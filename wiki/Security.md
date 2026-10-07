# Security reporting

[Deutsch](Security-de)

Do not put credentials, temporary signed URLs, customer data or unpatched security details in public issues. Report component problems privately to the maintainer using an existing private contact channel. For host-specific vulnerabilities use the host’s private reporting channel. If no private route is available, request one without exposing the vulnerability publicly.

Include updater/host versions, WordPress/PHP versions, redacted reproduction and impact. Never include the token itself. Reports are assessed confidentially, affected versions identified and fixes tested before coordinated disclosure. No fixed response deadline is promised.

Maintain the latest stable updater and consider older affected versions as needed. Host integration owns package validation, protected secrets and activation lifecycle. The engine does not isolate secrets from other privileged local PHP code. See [authentication](Authentication) and [data](Data).
