# Validation and release limits

Version 2.1.0 was validated in isolated disposable environments. No existing host production installation was changed. The published self-contained suite has 69 checks; the original V2 fixture passes its 22 public regression checks. Tests pass on PHP 8.4.5 and 8.1.29; old-first and new-first class loading were checked in separate processes.

Additional live acceptance performed during development:

- Private release assets and source archives through WordPress single, bulk and direct automatic-updater calls.
- Fine-grained PAT restricted to the private test repository, Contents read and Metadata read.
- Distinct replacement token accepted; genuinely revoked old token receives 401 despite an offer primed in valid metadata cache; installed code remains unchanged. Replacement token still succeeds.
- Credentials sent only to GitHub API; no Authorization on asset/codeload redirects.
- Eight real host MO-catalog checks across two independent domains, en_US/de_DE/de_DE_formal and locale changes.
- 66 actual Core package-path checks across Single-Site/SQLite, Multisite/SQLite and Single-Site/MySQL 8.0.35. Valid packages install; ten malformed/mismatched cases retain old installed code in both single and bulk paths. Some compatibility failures are rejected by Core before the host validator.
- WordPress 7.1.2/PHP 8.4.5 private Multisite asset updates, including network activation; bulk also on PHP 8.1.29. Manual single-update reactivation was explicitly handled by the test caller.
- Private MySQL 8.0.35 updates on PHP 8.4.5 and 8.1.29; source bulk/automatic paths additionally checked on WordPress 6.7.
- Public/private artwork and authentication stay separate; Multisite subsite shares network metadata cache.

The full host candidate checks remain host responsibilities. The private fixture and the host package-validation fixtures were separate; this is not an end-to-end acceptance of any production host plugin. Direct automatic-updater calls do not establish real cron scheduling or active-plugin fatal-error rollback. Concrete host integrations, combined MySQL-Multisite and complete lifecycle coverage still require their own acceptance. MySQL 8.4 was not accepted because the locally bundled runtime failed initialization; 8.0 tests passed.

Private repository fixtures, credentials, operational wrappers, databases and internal handoffs are not distributed. Published mock tests can run offline. Version 2.1.0 is the official release.
