# Changelog

## 2.1.0

- Extend the existing V2 engine with optional private GitHub repositories; keep one shared public/private update engine.
- Add repository-bound authentication providers and an environment-backed provider for installation-managed credentials.
- Support authenticated private release assets and source-archive fallback with controlled redirects and no credential forwarding to download hosts.
- Separate private metadata caching and provide explicit cache invalidation for credential rotation.
- Handle WordPress bulk plugin-update context for downloads and source normalization.
- Add per-host translation callbacks without an additional textdomain, extractable host strings and German Du/Sie templates.
- Preserve existing public constructor calls, icons, banners, escaped release changelogs and archive normalization.
- Document host package-validation responsibilities, version adoption and old/new class-loading guards.
- Validate private downloads, token rotation/revocation, Multisite, PHP 8.1 and MySQL 8.0; include 69 self-contained regression/localization checks.
