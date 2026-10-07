# Testing

[Deutsch](Testing-de)

## Self-contained suite

Run from the repository root with PHP 8.1 or later:

```sh
php tests/regression.php runtime/deckerweb-github-release-updater-v2.php
php tests/regression.php tests/fixtures/updater-v2-baseline.php
php tests/class-loading.php tests/fixtures/updater-v2-baseline.php runtime/deckerweb-github-release-updater-v2.php public
php tests/class-loading.php runtime/deckerweb-github-release-updater-v2.php tests/fixtures/updater-v2-baseline.php private
```

Expected: 69 current checks, 22 original public checks and the correct old/new capability decisions. These use synthetic WordPress functions and credentials, with no account access.

## Real host acceptance

Use disposable WordPress installations. Test public and private assets, missing assets/source fallback, single/bulk/automatic calls, distinct replacement token, genuine revocation against valid cache, language switches, parallel hosts and old class loading. Reject wrong identity, wrong repository, downgrade, offered-version mismatch, invalid version, missing/incompatible requirements, oversized/missing main file before installed code is replaced. Check activation state and failed-update lifecycle as well as code preservation.

Inspect the actual shipped host ZIP and its requirements. Use multiple supported PHP/database configurations and Multisite. Keep private credentials outside fixtures and redact HTTP monitoring. Native cron and active-plugin fatal-error rollback need separate checks. See [validated scope](Validation).
