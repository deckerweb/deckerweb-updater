<?php
const ABSPATH = __DIR__; const MINUTE_IN_SECONDS = 60;
$cache=[]; $requests=[]; $queue=[]; $filters=[];
function plugin_basename($f){return 'sample/sample.php';}
function wp_parse_url($u){return parse_url($u);}
function esc_url_raw($u,$p=[]){return $u;}
function add_filter(...$args){global $filters;$filters[]=$args;}
function get_site_transient($k){global $cache;return $cache[$k]??false;}
function set_site_transient($k,$v,$ttl){global $cache;$cache[$k]=$v;}
function delete_site_transient($k){global $cache;unset($cache[$k]);}
function wp_remote_get($u,$a){global $requests,$queue;$requests[]=[$u,$a];$r=array_shift($queue);if(!$r)throw new Exception('Unexpected request');if($r instanceof WP_Error)return $r;if(isset($a['filename']) && isset($r['binary']))file_put_contents($a['filename'],$r['binary']);return $r;}
function wp_safe_remote_get($u,$a){return wp_remote_get($u,$a);}
function wp_remote_retrieve_response_code($r){return $r['code']??0;}
function wp_remote_retrieve_body($r){return $r['body']??'';}
function wp_remote_retrieve_header($r,$h){return $r['headers'][$h]??'';}
function is_wp_error($e){return $e instanceof WP_Error;}
function esc_html($s){return htmlspecialchars($s);}
function wpautop($s){return '<p>'.$s.'</p>';}
function untrailingslashit($s){return rtrim($s,'/');}
function trailingslashit($s){return rtrim($s,'/').'/';}
function wp_tempnam($s){return tempnam(sys_get_temp_dir(),'ddw');}
function wp_delete_file($p){unlink($p);}
class WP_Error {public function __construct(public $code,public $message='',public $data=null){} }
function check($v,$m){if(!$v)throw new Exception($m);global $checks;$checks++;}
function fixture($changes=[]){return array_replace(['tag_name'=>'v1.2.0','assets'=>[['name'=>'sample.zip','state'=>'uploaded','browser_download_url'=>'https://github.com/deckerweb/sample/releases/download/v1.2.0/sample.zip','url'=>'https://api.github.com/repos/deckerweb/sample/releases/assets/42']],'zipball_url'=>'https://api.github.com/repos/deckerweb/sample/zipball/v1.2.0','body'=>'<script>notes</script>','published_at'=>'2026-10-06'],$changes);}
function enqueue($d){global $queue;$queue[]=['code'=>200,'body'=>json_encode($d)];}
require $argv[1];
use Deckerweb\GitHubReleaseUpdater\V2\Updater;
$art=['icons'=>['svg'=>'https://local.test/icon.svg','bad'=>'https://bad.test/x','1x'=>'https://user:pass@bad.test/x'],'banners'=>['low'=>'https://local.test/banner.png']];
$u=new Updater('/sample/sample.php','https://github.com/deckerweb/sample','Sample','Description',$art);
$headers=['UpdateURI'=>'https://github.com/deckerweb/sample','Version'=>'1.0.0'];
enqueue(fixture());$offer=$u->update(false,$headers,'sample/sample.php',[]);
check($offer['version']==='1.2.0','version');check(str_contains($offer['package'],'/releases/download/'),'public asset');check(count($requests)===1,'request');
check($u->update(false,$headers,'other/other.php',[])===false,'unrelated plugin');
check($u->update(false,array_replace($headers,['Version'=>'2.0.0']),'sample/sample.php',[])===false,'downgrade');
$info=$u->information(false,'plugin_information',(object)['slug'=>'sample']);check(str_contains($info->sections['changelog'],'&lt;script&gt;'),'escaped changelog');check($info->banners===$art['banners'],'banners');check(count($info->icons)===1,'art validation');check(count($requests)===1,'cache');
$t=(object)['response'=>['sample/sample.php'=>(object)['package'=>'unchanged'],'other'=>(object)['version'=>'5']],'last_checked'=>123];$copy=$u->cached_icons($t);check($copy!==$t && !isset($t->response['sample/sample.php']->icons),'clone');check($copy->last_checked===123 && $copy->response['other']===$t->response['other'],'preserve other offers');
$cache=[];enqueue(fixture(['assets'=>[]]));check(str_contains($u->update(false,$headers,'sample/sample.php',[])['package'],'/zipball/'),'fallback');
foreach([['draft'=>true],['prerelease'=>true],['tag_name'=>'oops'],['assets'=>[],'zipball_url'=>'https://evil.test/x']] as $bad){$cache=[];enqueue(fixture($bad));check($u->update(false,$headers,'sample/sample.php',[])===false,'reject invalid release');$n=count($requests);check($u->update(false,$headers,'sample/sample.php',[])===false && count($requests)===$n,'negative cache');}
$wp_filesystem=new class {function is_file($p){return str_ends_with($p,'/sample.php');}function exists($p){return false;}function move(...$a){return true;}};
check($u->select_source('/tmp/extracted','',null,['plugin'=>'other','type'=>'plugin','action'=>'update'])==='/tmp/extracted','source scoping');
$u->register();check(count($filters)===4,'public hooks');
echo "Public regression: $checks checks passed\n";
if(!defined(Updater::class.'::SUPPORTS_PRIVATE_REPOSITORIES'))exit;
use Deckerweb\GitHubReleaseUpdater\V2\EnvironmentAuthProvider;
putenv('DDW_TEST_TOKEN=synthetic_test_token');
$provider=new EnvironmentAuthProvider('https://github.com/deckerweb/sample','DDW_TEST_TOKEN');
$cache=[];$u=new Updater('/sample/sample.php','https://github.com/deckerweb/sample','Sample','Description',$art,['private'=>true,'auth'=>$provider]);
enqueue(fixture());$offer=$u->update(false,$headers,'sample/sample.php',[]);check(str_ends_with($offer['package'],'/assets/42'),'private asset API');check(end($requests)[1]['headers']['Authorization']==='Bearer synthetic_test_token','private metadata auth');check(end($requests)[1]['redirection']===0,'metadata no redirects');check(!str_contains(json_encode($cache),'synthetic_test_token'),'cache has no token');
$context=['plugin'=>'sample/sample.php','type'=>'plugin','action'=>'update'];
$queue[]=['code'=>200,'binary'=>'ZIP'];$path=$u->download(false,$offer['package'],null,$context);check(is_string($path) && file_get_contents($path)==='ZIP','direct binary');unlink($path);
$queue[]=['code'=>302,'headers'=>['location'=>'https://release-assets.githubusercontent.com/github-production-release-asset/x?signature=temporary']];$queue[]=['code'=>200,'binary'=>'ZIP'];$path=$u->download(false,$offer['package'],null,$context);check(is_string($path),'redirect download');check(!isset(end($requests)[1]['headers']['Authorization']),'redirect strips token');check(!str_contains(json_encode($cache),'signature'),'no temporary URL cache');unlink($path);
$queue[]=['code'=>302,'headers'=>['location'=>'https://evil.test/x']];$n=count($requests);check(is_wp_error($u->download(false,$offer['package'],null,$context)) && count($requests)===$n+1,'reject foreign redirect');
$n=count($requests);check($u->download(false,$offer['package'],null,['plugin'=>'other'])===false && count($requests)===$n,'private scoping');check($u->download('handled',$offer['package'],null,$context)==='handled','respect previous handler');check(is_wp_error($u->download(false,'https://evil.test/x',null,$context)) && count($requests)===$n,'reject package mismatch');
putenv('DDW_TEST_TOKEN');check($u->update(false,$headers,'sample/sample.php',[])===false,'missing token ignores positive cache');check(is_wp_error($u->download(false,$offer['package'],null,$context)),'missing token download');
putenv('DDW_TEST_TOKEN=synthetic_test_token');$u->clear_cache();enqueue(fixture(['assets'=>[]]));$offer=$u->update(false,$headers,'sample/sample.php',[]);check(str_ends_with($offer['package'],'/zipball/v1.2.0'),'private source fallback');
$queue[]=['code'=>302,'headers'=>['location'=>'https://codeload.github.com/deckerweb/sample/legacy.zip/v1.2.0']];$queue[]=['code'=>200,'binary'=>'ZIP'];$path=$u->download(false,$offer['package'],null,$context);check(is_string($path) && !isset(end($requests)[1]['headers']['Authorization']),'source redirect');unlink($path);
$queue[]=['code'=>401,'body'=>'synthetic_test_token'];$e=$u->download(false,$offer['package'],null,$context);check(is_wp_error($e) && !str_contains(json_encode($e),'synthetic_test_token'),'sanitized HTTP error');
check($provider->token('https://github.com/deckerweb/other')===null,'provider repository scope');
putenv('DDW_TEST_TOKEN=bad token');check($u->update(false,$headers,'sample/sample.php',[])===false,'invalid token');putenv('DDW_TEST_TOKEN');
echo "Combined regression/security: $checks checks passed\n";
class Plugin_Upgrader { public bool $bulk=true; }
putenv('DDW_TEST_TOKEN=synthetic_test_token');$cache=[];enqueue(fixture());$offer=$u->update(false,$headers,'sample/sample.php',[]);
$bulk=new Plugin_Upgrader();$queue[]=['code'=>200,'binary'=>'ZIP'];$path=$u->download(false,$offer['package'],$bulk,['plugin'=>'sample/sample.php']);check(is_string($path),'Core bulk context');check(end($requests)[1]['headers']['Accept']==='application/octet-stream','asset accept');unlink($path);
check($u->download(false,$offer['package'],null,['plugin'=>'sample/sample.php'])===false,'no ambiguous context');check($u->download(false,$offer['package'],$bulk,['plugin'=>'sample/sample.php','action'=>'install'])===false,'no install interception');
$wp_filesystem=new class {function is_file($p){return $p==='/tmp/source/sample.php';}function exists($p){return false;}function move($a,$b,$c){return $a==='/tmp/source' && $b==='/tmp/sample';}};
check($u->select_source('/tmp/source','',$bulk,['plugin'=>'sample/sample.php'])==='/tmp/sample/','bulk source normalization');
$u->clear_cache();enqueue(fixture(['assets'=>[]]));$offer=$u->update(false,$headers,'sample/sample.php',[]);$queue[]=['code'=>200,'binary'=>'ZIP'];$path=$u->download(false,$offer['package'],null,$context);check(end($requests)[1]['headers']['Accept']==='application/vnd.github+json','archive accept');unlink($path);
foreach(['http://codeload.github.com/x','https://user:pass@codeload.github.com/x','https://codeload.github.com:443/x','https://codeload.github.com.evil.test/x'] as $url){$queue[]=['code'=>302,'headers'=>['location'=>$url]];$n=count($requests);$before=glob(sys_get_temp_dir().'/ddw*');$e=$u->download(false,$offer['package'],null,$context);check(is_wp_error($e) && count($requests)===$n+1,'unsafe redirect rejected');check(glob(sys_get_temp_dir().'/ddw*')===$before,'failed temp file removed');}
$queue[]=['code'=>200,'binary'=>''];check(is_wp_error($u->download(false,$offer['package'],null,$context)),'empty binary rejected');
$queue[]=new WP_Error('bad','synthetic_test_token',['token'=>'synthetic_test_token']);$e=$u->download(false,$offer['package'],null,$context);check(!str_contains(json_encode($e),'synthetic_test_token'),'HTTP error data removed');
putenv('DDW_TEST_TOKEN');echo "Expanded regression/security: $checks checks passed\n";
// Host translation is per instance and evaluated in the current locale, not in metadata cache.
$language='en';$calls=[];$a=new Updater('/sample/sample.php','https://github.com/deckerweb/sample','Host A','Description',[],['private'=>true,'translate'=>function($m)use(&$language,&$calls){$calls[]=$m;return 'host-a/'.$language.'/'.$m;}]);
$b=new Updater('/sample/sample.php','https://github.com/deckerweb/sample','Host B','Description',[],['private'=>true,'translate'=>function($m)use(&$language){return 'host-b/'.$language.'/'.$m;}]);
$e=$a->download(false,'not-offered',null,$context);check($e->code==='ddw_ghru_private' && str_starts_with($e->message,'host-a/en/'),'localized private error preserves code');
$language='de_DE';check(str_starts_with($a->download(false,'not-offered',null,$context)->message,'host-a/de_DE/'),'locale evaluated lazily');check(str_starts_with($b->download(false,'not-offered',null,$context)->message,'host-b/de_DE/'),'separate host translator');
$language='de_DE_formal';check(str_starts_with($a->download(false,'not-offered',null,$context)->message,'host-a/de_DE_formal/'),'formal locale');
$broken=new Updater('/sample/sample.php','https://github.com/deckerweb/sample','Host','Description',[],['private'=>true,'translate'=>function($m){throw new Exception('synthetic_secret');}]);$e=$broken->download(false,'x',null,$context);check(!str_contains($e->message,'synthetic_secret') && str_starts_with($e->message,'The private update'),'translator exception sanitized');
$invalid=new Updater('/sample/sample.php','https://github.com/deckerweb/sample','Host','Description',[],['private'=>true,'translate'=>fn($m)=>[]]);check(str_starts_with($invalid->download(false,'x',null,$context)->message,'The private update'),'invalid translated value fallback');
$cache=[];enqueue(fixture(['body'=>'']));$u=new Updater('/sample/sample.php','https://github.com/deckerweb/sample','Host','Host description',[],['translate'=>function($m)use(&$language){return $language.'/'.$m;}]);$info=$u->information(false,'plugin_information',(object)['slug'=>'sample']);check(str_contains($info->sections['changelog'],'de_DE_formal/See the release'),'localized changelog fallback');$language='en';$n=count($requests);check(str_contains($u->information(false,'plugin_information',(object)['slug'=>'sample'])->sections['changelog'],'en/See the release') && count($requests)===$n,'locale change reuses raw release cache');
$cache=[];enqueue(fixture(['body'=>'Release author text']));check($u->information(false,'plugin_information',(object)['slug'=>'sample'])->sections['changelog']==='<p>Release author text</p>','release content is not a UI translation key');
try {new Updater('/sample/sample.php','https://evil.test/repo','Host','Description',[],['translate'=>fn($m)=>'localized/'.$m]);check(false,'invalid repo throws');}catch(InvalidArgumentException $e){check(str_starts_with($e->getMessage(),'localized/'),'constructor diagnostic localized');}
$wp_filesystem=null;check(str_starts_with($a->select_source('/tmp/a','',null,$context)->message,'host-a/en/'),'filesystem error localized');
$wp_filesystem=new class {function is_file($p){return false;}};check(str_starts_with($a->select_source('/tmp/a','',null,$context)->message,'host-a/en/'),'archive error localized');
echo "Host localization and regression: $checks checks passed\n";
