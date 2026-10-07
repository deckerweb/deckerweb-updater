# Release and maintenance conventions

[Deutsch](Conventions-de)

Use semantic three-part versions. Compatible features increase the minor version, fixes the patch version, and breaking API/transport changes require an explicit compatibility plan. Publish only a reviewed code/documentation/artwork set. Adoption by a host is a separate, pinned integration change.

Maintain public text centrally in docs/content.json. tools/build-docs.py generates synchronized EN/DE readmes, FAQ, changelogs and wiki pages. Edit the source, rerun the exporter and review generated links and images. Do not hand-edit generated files. New, Improved, Fixed, Misc are the English changelog categories; German uses Neu, Verbessert, Behoben, Sonstiges. Describe user-visible release behavior without internal working notes.

Runtime doc-blocks follow the project’s WordPress documentation conventions: class responsibility, method parameters in order, return values and relevant side effects. Own developer hooks include purpose, parameters and introduction version. Documentation completeness is part of the next code/release check; unnecessary inline restatements are avoided.

Keep host/updater versions, capability guards, package validation, translation catalogs and lifecycle aligned. Check the actual distributable for secrets, stale assets and unnecessary development files. Security changes and minimum-version changes need explicit review. Preserve origin and GPL licensing.
