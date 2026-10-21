# Changelog

[Deutsch](CHANGELOG-de.md)

### 2.2.1 · 2026-10-21

- **Fixed:** Updated host integrations use their pinned updater even when another plugin has already loaded an older V2 implementation.

- **Improved:** Release metadata caches are separated by implementation version, repository and public/private mode.

### 2.2.0 · 2026-10-21

- **New:** Optional public catalog providers verify fresh release approval and the downloaded package checksum.

- **Improved:** A shared, dismissible administrator notice helps identify possible conflicts with active Git Updater installations.

- **Fixed:** Configuration error messages are escaped, malformed update contexts are ignored, and public package URLs are validated more strictly.

- **Misc:** Expanded inline API documentation and host translation templates.

### 2.1.0 · 2026-10-06

- **New:** Private GitHub updates through a repository-bound authentication provider.

- **New:** Updater messages use the host plugin’s existing textdomain, including German Du/Sie catalogs.

- **Improved:** Authenticated release assets and source archives share the native WordPress update workflow.

- **Improved:** Token rotation supports explicit metadata-cache invalidation.

- **Fixed:** Private downloads and archive normalization handle Core’s per-package bulk context.

- **Misc:** Existing public calls, local artwork and release information remain supported.
