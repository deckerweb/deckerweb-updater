<?php
use Deckerweb\GitHubReleaseUpdater\V2\Updater;
require __DIR__.'/regression.php';
$u=new Updater('/sample/sample.php','https://github.com/deckerweb/sample','Name','Description',[],['translate'=>fn($m)=>'<img src=x onerror=alert(1)>']);
try {new Updater('/sample/sample.php','bad','Name','Description',[],['translate'=>fn($m)=>'<script>alert(1)</script>']);check(false,'invalid repo rejected');}catch(InvalidArgumentException $e){check(!str_contains($e->getMessage(),'<script>'),'exception translation escaped');}
check($u->select_source('/unchanged','',null,null)==='/unchanged','malformed source context ignored');
check($u->download(false,'url',null,'bad')===false,'malformed download context ignored');
check($u->update('old',null,'sample/sample.php',[])==='old','malformed plugin headers ignored');
foreach(["https://github.com/deckerweb/sample/releases/download/v1.2.3/a.zip\r\nX:bad",'https://github.com/deckerweb/sample/releases/download/v1/a.zip#fragment','https://github.com/deckerweb/sample/releases/download/v1/a.zip?token=x','https://github.com/other/sample/releases/download/v1/a.zip'] as $url){$cache=[];$queue=[['code'=>200,'body'=>json_encode(['tag_name'=>'v1.2.3','assets'=>[['name'=>'sample.zip','browser_download_url'=>$url]]])]];check($u->update(false,['Version'=>'1.0.0','UpdateURI'=>'https://github.com/deckerweb/sample'],'sample/sample.php',[])===false,'unsafe public URL rejected');}
$package='https://github.com/deckerweb/sample/releases/download/v1.2.3/sample.zip';
$tmp=tempnam(sys_get_temp_dir(),'ddw220');file_put_contents($tmp,'approved bytes');
$release=['version'=>'1.2.3','package'=>$package,'sha256'=>hash_file('sha256',$tmp)];$fresh=[];
$provider=function($repo,$file,$force)use(&$release,&$fresh){$fresh[]=$force;return $release;};
$catalog=new Updater('/sample/sample.php','https://github.com/deckerweb/sample','Name','Description',[],['release_provider'=>$provider,'package_provider'=>fn()=> $tmp]);
$offer=$catalog->update(false,['Version'=>'1.0.0','UpdateURI'=>'https://github.com/deckerweb/sample'],'sample/sample.php',[]);check($offer['version']==='1.2.3','catalog offer');
check($catalog->download(false,$package,null,$context)===$tmp && end($fresh)===true,'fresh approval and hash verified');
check($catalog->download(false,$package.'x',null,$context) instanceof WP_Error,'stale package blocked');
$release['sha256']=str_repeat('0',64);check($catalog->download(false,$package,null,$context) instanceof WP_Error,'wrong hash blocked');
$release=null;check($catalog->download(false,$package,null,$context) instanceof WP_Error,'revoked approval blocks without fallback');
$throws=new Updater('/sample/sample.php','https://github.com/deckerweb/sample','Name','Description',[],['release_provider'=>fn()=>throw new Exception('secret'),'package_provider'=>fn()=>false]);check($throws->download(false,$package,null,$context)->message==='The approved release could not be verified.','provider exception redacted');
try{new Updater('/sample/sample.php','https://github.com/deckerweb/sample','Name','Description',[],['private'=>true,'release_provider'=>$provider,'package_provider'=>fn()=>false]);check(false,'invalid providers rejected');}catch(InvalidArgumentException $e){check(true,'private/catalog isolated');}
unlink($tmp);
echo "Total regression/security/provider: $checks checks passed\n";
