# Data and external requests

[Deutsch](DATA-de.md)

## Metadata and cache

Latest stable GitHub releases provide version, stable package endpoint, authored notes and publication date. Repository-specific site transients retain successful metadata for 30 minutes and failures for 10 minutes. Private mode uses a separate key suffix. On Multisite the site-transient scope is the current network; this is not isolation of credentials per subsite. Translated UI text, tokens and temporary signed download URLs are not cached.

## Connections and secrets

Update checks contact api.github.com. Package downloads may redirect to approved GitHub download hosts; these endpoints see the requester’s IP address. Private API requests carry the provider credential. The engine does not forward it to redirect hosts, add analytics or require credentials for public repositories. Core and host requests are separate.

Protected installation configuration owns the secret. Other privileged PHP code and HTTP-debug hooks can inspect outgoing requests; monitoring must redact Authorization and signed URLs.

## Activation and uninstall

The host owns activation, deactivation, reactivation and uninstall. This embedded component adds no settings screen or dedicated database tables. Use clear_cache() for its repository metadata; secret removal happens in installation configuration. Do not delete shared cache entries while another host intentionally uses them without coordinating ownership. Uninstalling a host does not revoke a GitHub token; revocation is a separate operation.
