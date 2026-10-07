# deckerweb Updater

[Deutsch](README-de.md)

![deckerweb Updater](assets-github/banner-en.png)

## About

The deckerweb Updater connects GitHub releases to the normal WordPress plugin update workflow. Plugin authors embed it in their plugins; users keep the familiar update screen. Public and private repositories share the same engine.

This repository contains the reusable PHP component, tests, integration examples and documentation. If you find it inside a plugin, you do not need to install an additional updater plugin.

**Version:** 2.1.0 · PHP ≥ 8.1 · WordPress requirements follow the host; tested on 6.7 and 7.1.2.

[Documentation](docs/INTEGRATION.md) · [FAQ](docs/FAQ.md) · [Security](SECURITY.md)

## Contents

- [At a Glance](#glance)
- [Installation and first steps](#installation)
- [Main features](#features)
- [Found the updater in your plugin?](#users)
- [For developers](#developers)
- [FAQ](#faq)
- [Changelog](#changelog)
- [Author and scope](#author)
- [Questions and support](#support)

<a id="glance"></a>
## At a Glance

- GitHub releases in the native WordPress update workflow.
- Public repositories without credentials; optional private repositories with a provider.
- Release ZIP assets and source-archive fallback.
- Host-provided icons, banners and plugin information.
- All interface strings in the host plugin’s existing textdomain.

<a id="installation"></a>
## Installation and first steps

**For plugin users:** update the embedding plugin as usual under **Dashboard → Updates** or **Plugins**. Your plugin author decides whether and how this component is included.

**For developers:** copy a pinned runtime version into the host, register it after translations initialize and preserve the host’s full package validation. The repository ZIP is a component source package. See [integration](docs/INTEGRATION.md) and the [adapter template](examples/host-adapter.php).

<a id="features"></a>
## Main features

### One workflow for GitHub updates

Published stable releases become update offers for the matching plugin. Drafts, prereleases and older versions are not offered. The component does not turn on automatic updates.

### Public and private repositories

Public repositories use the existing unauthenticated flow. Private repositories obtain credentials from a repository-bound provider. Private API downloads resolve temporary URLs at download time and do not forward the token to the download host.

### Keep the host’s identity

Icons, banners, names and descriptions come from the host. Release notes appear as escaped text in the plugin details dialog. Structured footer changelogs and localized wiki links remain host features.

### One textdomain per plugin

The host owns translation catalogs. A per-instance callback translates updater messages when they are displayed; no extra updater textdomain is loaded.

<a id="users"></a>
## Found the updater in your plugin?

You do not need a GitHub account or token for public plugin updates. Private updates need installation-managed credentials; follow your plugin maintainer’s setup instructions. No token is shipped inside the plugin.

GitHub update checks and package downloads contact external servers, which can see the requesting IP address. This component adds no analytics. Core WordPress and the host may make other requests under their own policies.

Use the host plugin’s documentation for its requirements, activation behavior and uninstall cleanup. See [data and requests](docs/DATA.md).

<a id="developers"></a>
## For developers

The updater is GPL-2.0-or-later. Explore or reuse it with its origin and license intact. Start with [integration](docs/INTEGRATION.md), [authentication](docs/AUTHENTICATION.md), [tests](docs/TESTING.md) and [release conventions](docs/CONVENTIONS.md).

Before replacing an installed plugin, the host must verify the actual candidate identity, Update URI, offered version and WordPress/PHP requirements. Keep this check active in both single and bulk updates. Check capabilities before using new options beside older loaded V2 copies.

<a id="faq"></a>
## FAQ

### Do I install a separate updater plugin?

No. The host embeds a pinned version. Use the host plugin’s normal installation and update steps.

### Do public updates need a GitHub token?

No. Public repositories remain unauthenticated. Only configured private repositories need credentials.

### Can I update from a private repository?

Yes. The host must enable private mode and provision a repository-bound provider. A fine-grained PAT with Contents read and Metadata read supports the tested release and archive endpoints.

### Does the updater enable automatic updates?

No. It supports the normal WordPress update engine. Automatic-update preferences remain with WordPress and the host.

### Can several plugins embed V2?

Yes, with guarded loading. The first loaded class wins; check the private/translation capabilities and coordinate deployed versions.

### Is there an extra textdomain?

No. Updater strings are merged into the host’s catalogs and translated through a host callback.

### What happens when a token is revoked?

Private downloads are rejected even if metadata is still cached. A rejected download does not replace the installed plugin; activation behavior remains the responsibility of the calling host workflow.

[All questions by topic](docs/FAQ.md).

<a id="changelog"></a>
## Changelog

### 2.1.0 · 2026-10-06

- **New:** Private GitHub updates through a repository-bound authentication provider.

- **New:** Updater messages use the host plugin’s existing textdomain, including German Du/Sie catalogs.

- **Improved:** Authenticated release assets and source archives share the native WordPress update workflow.

- **Improved:** Token rotation supports explicit metadata-cache invalidation.

- **Fixed:** Private downloads and archive normalization handle Core’s per-package bulk context.

- **Misc:** Existing public calls, local artwork and release information remain supported.

<a id="author"></a>
## Author and scope

Developed and maintained by David Decker — DECKERWEB. A shared embedded component for GitHub-distributed WordPress plugins.

<a id="support"></a>
## Questions and support

Use [issues](https://github.com/deckerweb/deckerweb-updater/issues) for component questions and reproducible bugs without sensitive details. For plugin-specific integration issues, contact the host maintainer. Read [security reporting](SECURITY.md) before sharing a suspected vulnerability.

[Ko-fi](https://ko-fi.com/deckerweb) · [Buy Me a Coffee](https://buymeacoffee.com/daveshine) · [PayPal](https://paypal.me/deckerweb)

## Copyright and license

Copyright © 2026 David Decker — DECKERWEB. GPL-2.0-or-later. [LICENSE](LICENSE) · [Assets](docs/ASSETS.md).
