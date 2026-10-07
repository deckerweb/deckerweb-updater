# Authentication and token lifecycle

[Deutsch](AUTHENTICATION-de.md)

## Public and private

Public mode makes unauthenticated GitHub requests. Private mode requires a repository-bound AuthProvider; EnvironmentAuthProvider reads the named installation variable at request time. A syntactically missing/invalid credential fails closed. Provider exceptions are not exposed.

Create a fine-grained GitHub PAT with only the necessary repositories, Contents: read and implicit Metadata: read. Choose an expiry, obtain any required organization approval and provision the secret securely for the PHP worker. Do not distribute the credential or put it in command arguments. The host supplies private=true, auth and its translate callback.

## Rotation and revocation

Create an equally restricted replacement, provision it, clear the updater and WordPress update caches, and verify an actual download. Then revoke the old token in GitHub and remove its retired installation secret. A revoked credential cannot use a still-valid metadata cache to download a private package. Already downloaded files are not retroactively invalidated.

## Downloads and providers

Private release assets use a repository-bound assets/ID API endpoint; archive fallback uses the release ref. Accept differs between asset and archive requests. API requests do not automatically follow redirects. Approved HTTPS downloadhosts receive no Authorization header. Signed URLs are resolved on demand and not persisted in update metadata.

A GitHub App provider can implement the same token contract. A future external update service requires an explicit transport integration; it is not present in version 2.1.
