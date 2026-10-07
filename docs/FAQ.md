# FAQ by topic

[Deutsch](FAQ-de.md)

## Getting started

### Do I install a separate updater plugin?

No. The host embeds a pinned version. Use the host plugin’s normal installation and update steps.

### Do public updates need a GitHub token?

No. Public repositories remain unauthenticated. Only configured private repositories need credentials.

## Updates

### Can I update from a private repository?

Yes. The host must enable private mode and provision a repository-bound provider. A fine-grained PAT with Contents read and Metadata read supports the tested release and archive endpoints.

### Does the updater enable automatic updates?

No. It supports the normal WordPress update engine. Automatic-update preferences remain with WordPress and the host.

## Integration

### Can several plugins embed V2?

Yes, with guarded loading. The first loaded class wins; check the private/translation capabilities and coordinate deployed versions.

## Localization

### Is there an extra textdomain?

No. Updater strings are merged into the host’s catalogs and translated through a host callback.

## Security

### What happens when a token is revoked?

Private downloads are rejected even if metadata is still cached. A rejected download does not replace the installed plugin; activation behavior remains the responsibility of the calling host workflow.

## Updates

### When does metadata refresh?

Successful metadata is cached for 30 minutes, failures for 10 minutes. WordPress schedules checks; manual refresh and the host’s cache invalidation can trigger another lookup.

### What if a release has no matching ZIP asset?

The engine uses the release’s repository source archive when valid. The host must still validate the archive as an installable plugin.

## Integration

### Who validates the actual package?

The engine checks the main file and normalizes directories. Full identity, offered version and platform checks are host responsibilities, including the special Core bulk context.

## Security

### Where is the token stored?

In protected installation configuration, read by the provider at request time. It must not be embedded in plugin files, public repositories, ZIPs, URLs or metadata.

## Library

### Does the Library replace the host updater?

No. Catalog discovery and installed-plugin updates are separate responsibilities. A Library catalog display cache must not dictate the host updater’s refresh interval.
