# deckerweb Updater

A small, GitHub-only release updater for WordPress plugins maintained by DECKERWEB. Version **2.1.0** extends the existing V2 engine with optional private repository authentication and host-owned translations.

This is an embedded component, not a standalone WordPress plugin. Copy a pinned, tested version into the host plugin. It does not enable automatic updates or replace installed host integrations.

## Features

- One engine for public and private GitHub repositories.
- Latest stable release metadata, preferred slug/version ZIP assets and source-ZIP fallback.
- WordPress update offers, plugin details, local icon/banner URL maps and archive normalization.
- Optional repository-bound authentication provider; an environment provider is included.
- Authenticated private API downloads with controlled HTTPS redirects and no credential forwarding to download hosts.
- Per-host translation callback; all user-facing strings belong to the host's existing textdomain.

Structured footer changelogs, locale-specific wiki links, artwork selection and full candidate identity/version/requirement checks belong to the integrating host. Keep those host features intact.

## Integration

See [English integration guide](docs/INTEGRATION.md), [German integration guide](docs/INTEGRATION-de.md), [host adapter template](examples/host-adapter.php) and [host translation template](examples/host-translations.php).

Existing five-argument public constructor calls remain compatible. A sixth options array supports `private`, `auth` and `translate`. Private mode without a valid provider fails closed. Credentials must come from protected installation configuration, never from plugin files, ZIPs or Git.

## Development status

This is the official 2.1 release. Public regression, private asset/source downloads, token rotation/revocation, host translations, isolated host package checks, Multisite and PHP 8.1/MySQL 8.0 tests have been performed. See [validation and limits](docs/VALIDATION.md). Each real host integration still requires its own acceptance checks before production use.

## Tests

Run from the repository root with PHP 8.1 or later:

```sh
php tests/regression.php runtime/deckerweb-github-release-updater-v2.php
php tests/regression.php tests/fixtures/updater-v2-baseline.php
php tests/class-loading.php tests/fixtures/updater-v2-baseline.php runtime/deckerweb-github-release-updater-v2.php public
php tests/class-loading.php runtime/deckerweb-github-release-updater-v2.php tests/fixtures/updater-v2-baseline.php private
```

The self-contained tests use synthetic WordPress functions and credentials. They require no account access. Private live tests require a separate disposable installation and a narrowly scoped token; neither credentials nor internal test databases are included.

## License and origin

GPL-2.0-or-later. Copyright David Decker – DECKERWEB. The engine originated in the existing V2 component shipped in [Brand Admin Schemes 0.18.0](https://github.com/deckerweb/brand-admin-schemes/releases/tag/v0.18.0), commit `a9a1c0da350c3e8cfc0eec5db8ab1d923bd4ba17`. The unchanged original engine is included only as a regression fixture.
