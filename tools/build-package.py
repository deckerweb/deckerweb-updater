#!/usr/bin/env python3
"""Build the embeddable component without tests, build tools or marketing assets."""
from pathlib import Path
import hashlib,sys,zipfile
root=Path(__file__).resolve().parents[1]
dest=Path(sys.argv[1]) if len(sys.argv)>1 else root.parent/(root.name+'.zip')
roots={'LICENSE','README.md','README-de.md','SECURITY.md','SECURITY-de.md','CHANGELOG.md','CHANGELOG-de.md','RELEASE-NOTES.md'}
with zipfile.ZipFile(dest,'w',zipfile.ZIP_DEFLATED) as archive:
 for path in sorted(root.rglob('*')):
  if not path.is_file():continue
  relative=path.relative_to(root)
  if str(relative) not in roots and relative.parts[0] not in {'runtime','examples','host-strings','docs'}:continue
  if path.name=='content.json' or path.name.startswith('.'):continue
  content=path.read_bytes()
  if relative.name in {'README.md','README-de.md'} and len(relative.parts)==1:
   content=content.replace(b'](assets-github/',b'](https://raw.githubusercontent.com/deckerweb/deckerweb-updater/main/assets-github/')
  info=zipfile.ZipInfo('deckerweb-updater/'+str(relative),(2026,10,21,0,0,0))
  info.compress_type=zipfile.ZIP_DEFLATED;info.external_attr=0o100644<<16
  archive.writestr(info,content)
checksum=hashlib.sha256(dest.read_bytes()).hexdigest()
Path(str(dest)+'.sha256').write_text(checksum+'  '+dest.name+'\n')
print(checksum+'  '+dest.name)
