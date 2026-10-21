# Host integration

## Pin and embed the component

Copy `runtime/deckerweb-github-release-updater-v2.php` into the host's includes directory. Keep the plugin installed under its stable slug. Set the main plugin's `Update URI` header to exactly `https://github.com/OWNER/REPOSITORY`, without a trailing slash. Register after host translations initialize on `init`.

The namespace stays `Deckerweb\GitHubReleaseUpdater\V2`. Several embedded copies can coexist only through guarded loading: `class_exists` selects the first loaded class. Check `SUPPORTS_PRIVATE_REPOSITORIES` and `SUPPORTS_HOST_TRANSLATIONS` before relying on the new options. An old copy must cause a clear, host-localized configuration notice and stop the new integration; do not redeclare or silently ignore capabilities. Coordinate pinned versions across deployed hosts.

The constructor accepts the absolute main plugin path, repository URL, name, description, optional artwork maps and optional options. See `examples/host-adapter.php`; replace its placeholders and incorporate it into the actual host lifecycle.

## Host-owned translations and artwork

Copy `examples/host-translations.php` into the adapter and replace its literal `example-host` textdomain with the host's own domain. Extract its literal `__()` calls into the host POT; merge the Du/Sie entries in `host-strings` into the host PO files and compile the host MO catalogs. The engine loads no separate domain or catalogs.

Pass the returned callable as `translate`. Calls happen at output time, using the current host locale. No translated UI text is stored in release caches. Names/descriptions are host inputs; release notes remain authored release content. Translator failures fall back to fixed English messages without exception details. Technical configuration exceptions must not be displayed raw in admin UI.

Provide bundled icon/banner URLs through `artwork`. Supported keys: icons `svg`, `1x`, `2x`, `default`; banners `low`, `high`. Language-specific selection remains in the host. Preserve the host footer changelog and documentation/wiki links.

## Private credentials

Use a GitHub fine-grained PAT limited to the necessary repository, with Contents: read and GitHub's implicit Metadata: read. No write permission is needed for the tested metadata/asset/source endpoints. Keep the credential in protected server-side secret configuration exposed to the PHP worker; shell configuration alone does not configure PHP-FPM.

The environment provider is bound to one exact repository and reads the named variable at request time. Provision a separate token/variable when permissions need separation. A later GitHub App provider can implement `AuthProvider::token(string $repository): ?string`; the transport remains GitHub-specific. A custom update service needs an explicit transport integration.

No credentials belong in package URLs, transients, plugin code, ZIPs or Git. This component does not log secrets, but external WordPress HTTP-debug hooks may inspect requests. Configure monitoring to redact Authorization and temporary signed URLs. Privileged local PHP code is not isolated from the secret.

After rotation, call `clear_cache()` on the registered updater, clear the WordPress plugin update cache and check again. Validate the replacement before revoking the old token. A cached offer cannot authorize the actual download with a revoked credential. Remove retired secrets from installation configuration after testing.

## Mandatory host package validation

The engine normalizes directories and checks for the expected main file. The host must additionally validate candidate plugin identity, exact Update URI, version equality with the offered update, newer version and actual WordPress/PHP requirements before replacing the installed plugin. Do not infer future requirements from installed headers alone. Apply filesystem/read limits suitable for the host.

Scope `upgrader_source_selection` to the exact plugin basename. Accept a complete plugin/update context or Core's Bulk variant: a real `Plugin_Upgrader` with `bulk === true`, matching plugin basename and no explicitly conflicting type/action. Bulk per-package contexts omit type/action; requiring them unconditionally skips host validation. The engine already handles this distinction; the host must do so as well. See `examples/host-update-context.php`.

Preserve existing error codes, checks, locale behavior and activation/uninstall lifecycle. Manual single-plugin updates can deactivate an active plugin; the invoking workflow must handle appropriate reactivation. The updater does not manage activation. Test installed-version preservation and activation behavior on failed packages and native single/bulk/automatic update flows.

## Release adoption

Use a pinned version or verified release asset. The repository ZIP is a source distribution, not an installable WordPress plugin. Updater adoption is a deliberate host release change; publishing this repository does not update existing embedded copies automatically. Public hosts require no token.


## Public catalog providers

Version 2.2.0 exposes SUPPORTS_CATALOG_PROVIDER. Set both release_provider and package_provider callables in the options only for public repositories. The release provider receives (repository URL, plugin basename, fresh boolean). Return false only when the catalog is explicitly disabled and direct GitHub mode should apply; return null on unavailable or denied approval. An approved array contains version (three numeric components), package (a release asset URL in the same repository), sha256 (64 lowercase hexadecimal characters), and optional notes, published, requires_wp and requires_php strings. Denial and provider exceptions never silently switch to a direct download.

The package provider receives (repository URL, plugin basename, package URL) and returns a temporary ZIP path or WP_Error. It owns safe downloading and cleanup of failed temporary files. Before the operation the updater asks for fresh approval, checks the offered URL has not changed, and independently verifies the returned file SHA-256. Provider errors become fixed messages without credentials or exception details. Preserve the host’s package identity and requirement checks. Private mode cannot use catalog callbacks.

## Git Updater coexistence

The updater locally detects active Git Updater and historical GitHub Updater identities. Authorized administrators see one shared warning on plugin, update and Library screens; it shares the Library’s dismissal marker. Dismissal is per user and detected version/activation scope. No network detection or automatic deactivation occurs. If an operation fails, temporarily deactivate Git Updater and retry, then reactivate it if other extensions need it. Leave it disabled permanently only when all affected plugins and themes have another reliable update provider. This notice does not guarantee compatibility or change foreign hooks.
