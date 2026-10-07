#!/usr/bin/env python3
"""Export public EN/DE documents from docs/content.json; never modify runtime code."""
from pathlib import Path
import json,re,shutil
ROOT=Path(__file__).resolve().parents[1]
c=json.loads((ROOT/'docs/content.json').read_text())
labels={'en':{'about':'About','contents':'Contents','glance':'At a Glance','installation':'Installation and first steps','features':'Main features','users':'Found the updater in your plugin?','developers':'For developers','faq':'FAQ','changelog':'Changelog','author':'Author and scope','support':'Questions and support','copyright':'Copyright and license'},'de':{'about':'Über den Updater','contents':'Inhalt','glance':'Auf einen Blick','installation':'Einbindung und erste Schritte','features':'Hauptfunktionen','users':'Den Updater in deinem Plugin entdeckt?','developers':'Für Entwickler','faq':'FAQ','changelog':'Changelog','author':'Autor und Umfang','support':'Fragen und Unterstützung','copyright':'Copyright und Lizenz'}}
cats={'en':{'New':'New','Improved':'Improved','Fixed':'Fixed','Misc':'Misc'},'de':{'New':'Neu','Improved':'Verbessert','Fixed':'Behoben','Misc':'Sonstiges'}}
def suffix(lang):return '-de' if lang=='de' else ''
def faq(lang,short=False):
 out=[];topic=None
 for f in c['faq']:
  if short and not f['readme']:continue
  if not short and topic!=f['topic'][lang]:topic=f['topic'][lang];out.append('## '+topic)
  out.append('### '+f['question'][lang]+'\n\n'+f['answer'][lang])
 return '\n\n'.join(out)
def changes(lang):
 out=[]
 for r in c['releases']:
  out.append('### '+r['version']+' · '+r['date'])
  for e in r['entries']:out.append('- **'+cats[lang][e['category']]+':** '+e['text'][lang])
 return '\n\n'.join(out)
def write(path,text):
 p=ROOT/path;p.parent.mkdir(parents=True,exist_ok=True);p.write_text(text.rstrip()+'\n')
for lang in ['en','de']:
 s=suffix(lang);l=labels[lang];other='[Deutsch](README-de.md)' if lang=='en' else '[English](README.md)'
 sections=['glance','installation','features','users','developers','faq','changelog','author','support']
 toc='\n'.join('- ['+l[k]+'](#'+k+')' for k in sections)
 version=f"**Version:** {c['version']} · PHP ≥ 8.1 · "+('WordPress requirements follow the host; tested on 6.7 and 7.1.2.' if lang=='en' else 'WordPress-Anforderungen richten sich nach dem Host; geprüft auf 6.7 und 7.1.2.')
 nav=f'[Documentation](docs/INTEGRATION{s}.md) · [FAQ](docs/FAQ{s}.md) · [Security](SECURITY{s}.md)' if lang=='en' else f'[Dokumentation](docs/INTEGRATION{s}.md) · [FAQ](docs/FAQ{s}.md) · [Sicherheit](SECURITY{s}.md)'
 text=f"# {c['project']}\n\n{other}\n\n![{c['project']}](assets-github/banner-{lang}.png)\n\n## {l['about']}\n\n{c['readme']['about'][lang]}\n\n{version}\n\n{nav}\n\n## {l['contents']}\n\n{toc}\n\n"
 for k in sections:
  body=faq(lang,True) if k=='faq' else changes(lang) if k=='changelog' else c['readme'][k][lang]
  if k=='faq':body+='\n\n'+('[All questions by topic](docs/FAQ.md).' if lang=='en' else '[Alle Fragen nach Themen](docs/FAQ-de.md).')
  text+=f'<a id="{k}"></a>\n## {l[k]}\n\n{body}\n\n'
 text+=f"## {l['copyright']}\n\nCopyright © 2026 David Decker — DECKERWEB. GPL-2.0-or-later. [LICENSE](LICENSE) · [Assets](docs/ASSETS{s}.md).\n"
 write(f'README{s}.md',text)
 write(f'docs/FAQ{s}.md',('# FAQ by topic\n\n[Deutsch](FAQ-de.md)' if lang=='en' else '# Fragen nach Themen\n\n[English](FAQ.md)')+'\n\n'+faq(lang))
 changelog='# Changelog\n\n'+('[Deutsch](CHANGELOG-de.md)' if lang=='en' else '[English](CHANGELOG.md)')+'\n\n'+changes(lang)
 write(f'CHANGELOG{s}.md',changelog);write(f'docs/CHANGELOG{s}.md',changelog)
 for stem,body in c['documents'].items():write(f'{stem}{s}.md' if stem=='SECURITY' else f'docs/{stem}{s}.md',body[lang])
# Wiki export is ready for a separately approved wiki publication, not an upload operation.
wiki=ROOT/'wiki';wiki.mkdir(exist_ok=True)
for lang in ['en','de']:
 s=suffix(lang)
 home=f"# {c['project']}\n\n![{c['project']}](assets/banner-{lang}.png)\n\n**{c['slogan'][lang]}**\n\n{c['readme']['about'][lang]}\n\n"
 pages={'INTEGRATION':'Integration','AUTHENTICATION':'Authentication','DATA':'Data','TESTING':'Testing','VALIDATION':'Validation','CONVENTIONS':'Conventions','ASSETS':'Assets','GLOSSARY':'Glossary','SECURITY':'Security'}
 for stem,name in pages.items():
  body=c['documents'][stem][lang]
  # Public repo mirror links remain valid and do not require invented wiki addresses.
  def links(m):
   dest=m.group(2)
   if dest.startswith(('http:','https:','#')):return m.group(0)
   clean=dest.removeprefix('../').removeprefix('docs/')
   targetstem=clean.removesuffix('.md').removesuffix('-de')
   loc='-de' if clean.endswith('-de.md') else ''
   if targetstem in pages:return '['+m.group(1)+']('+pages[targetstem]+loc+')'
   if targetstem=='FAQ':return '['+m.group(1)+'](FAQ'+loc+')'
   return '['+m.group(1)+'](https://github.com/deckerweb/deckerweb-updater/blob/main/'+dest.removeprefix('../')+')'
  body=re.sub(r'\[([^\]]+)\]\(([^)]+)\)',links,body)
  write(f'wiki/{name}{s}.md',body)
  home+=f'- [{name}]({name}{s})\n'
 home+=f'- [FAQ](FAQ{s})\n- [Changelog](Changelog{s})\n'
 write(f'wiki/Home{s}.md',home);write(f'wiki/FAQ{s}.md',('# FAQ by topic' if lang=='en' else '# Fragen nach Themen')+'\n\n'+faq(lang));write(f'wiki/Changelog{s}.md','# Changelog\n\n'+changes(lang))
 for asset in ['banner-'+lang+'.png','banner-'+lang+'.svg']:
  target=wiki/'assets';target.mkdir(exist_ok=True);shutil.copy(ROOT/'assets-github'/asset,target/asset)
write('wiki/_Sidebar.md','## English\n\n'+''.join(f'- [{n}]({n})\n' for n in ['Home','Integration','Authentication','Data','Testing','FAQ','Changelog','Security','Assets'])+'\n## Deutsch\n\n'+''.join(f'- [{n}]({n}-de)\n' for n in ['Home','Integration','Authentication','Data','Testing','FAQ','Changelog','Security','Assets']))
write('wiki/_Footer.md','[Repository](https://github.com/deckerweb/deckerweb-updater) · Copyright © 2026 David Decker — DECKERWEB · GPL-2.0-or-later')
print('Generated EN/DE readmes, documentation, FAQ, changelogs and wiki from one content source.')
