<?php
// Isolated PHP process: no real credentials, network, filesystem updates or WordPress database.
define('ABSPATH',__DIR__.'/stub-wp/');define('MINUTE_IN_SECONDS',60);
$cache=[];$hooks=[];$requests=[];$checks=0;
function ck($yes,$label){global $checks;if(!$yes)throw new Exception($label);$checks++;}
function plugin_basename($p){return trim($p,'/');}
function wp_parse_url($p){return parse_url($p);}
function esc_url_raw($p,$schemes=[]){return $p;}
function esc_html($p){return htmlspecialchars($p,ENT_QUOTES,'UTF-8');}
function wpautop($p){return '<p>'.$p.'</p>';}
function add_filter($hook,$callback,$priority=10,$args=1){global $hooks;$hooks[$hook][]=[$callback,$priority,$args];}
function add_action(...$a){add_filter(...$a);}
function has_action($hook){global $hooks;return !empty($hooks[$hook]);}
function get_site_transient($key){global $cache;return $cache[$key]??false;}
function set_site_transient($key,$v,$ttl){global $cache;$cache[$key]=$v;}
function delete_site_transient($key){global $cache;unset($cache[$key]);}
function wp_remote_get($url,$args){global $requests;$requests[]=[$url,$args];$repo=preg_replace('~^https://api.github.com/repos/([^/]+/[^/]+).*~','$1',$url);if(isset($args['filename'])){file_put_contents($args['filename'],'package-bytes');return ['code'=>200,'body'=>''];}return ['code'=>200,'body'=>json_encode(['tag_name'=>'v3.0.0','assets'=>[['name'=>basename($repo).'.zip','state'=>'uploaded','browser_download_url'=>'https://github.com/'.$repo.'/releases/download/v3.0.0/plugin.zip','url'=>'https://api.github.com/repos/'.$repo.'/releases/assets/42']],'body'=>'notes','published_at'=>'2026-10-21'])];}
function wp_safe_remote_get($u,$a){return wp_remote_get($u,$a);}
function wp_remote_retrieve_response_code($r){return $r['code']??0;}
function wp_remote_retrieve_body($r){return $r['body']??'';}
function wp_remote_retrieve_header($r,$h){return $r['headers'][$h]??'';}
function is_wp_error($r){return $r instanceof WP_Error;}
function wp_tempnam($s){return tempnam(sys_get_temp_dir(),'dw221-');}
function wp_delete_file($p){unlink($p);}
function untrailingslashit($s){return rtrim($s,'/');}
function trailingslashit($s){return rtrim($s,'/').'/';}
class WP_Error{public function __construct(public $code,public $message='',public $data=null){}}
$cfg=json_decode(file_get_contents($argv[1]),true);$order=explode(',',$argv[2]);$instances=[];
foreach($order as $label){
 $p=$cfg[$label];
 if(str_starts_with($label,'new')||$label==='future'){
  $class=require $p;$again=require $p;ck($again===$class,'repeat include returns same class');ck(is_string($class)&&class_exists($class,false),'returned class usable');ck(version_compare($class::IMPLEMENTATION_VERSION,$label==='future'?'2.2.2':'2.2.1','=='),'pinned implementation selected regardless of legacy class');
 }else{if(!class_exists('Deckerweb\\GitHubReleaseUpdater\\V2\\Updater',false)){require $p;}$class='Deckerweb\\GitHubReleaseUpdater\\V2\\Updater';}
 $repo='https://github.com/deckerweb/'.$label;$file=$label.'/'.$label.'.php';$u=new $class('/'.$file,$repo,$label,'Description');$u->register();$instances[$label]=$u;
 if(str_starts_with($label,'new')||$label==='future'){$cache['ddw_ghru_'.substr(md5($repo),0,24)]=['version'=>'9.9.9','package'=>'https://foreign.invalid/old.zip','notes'=>'old-cache','published'=>''];}
 $offer=$u->update(false,['Version'=>'1.0.0','UpdateURI'=>$repo],$file,[]);ck(is_array($offer)&&$offer['version']==='3.0.0','host retains public update offer and new engine ignores incompatible legacy cache');
 foreach($instances as $other=>$unused){if($other!==$label)ck($u->update('foreign',['Version'=>'1.0.0','UpdateURI'=>'https://github.com/deckerweb/'.$other],$other.'/'.$other.'.php',[])==='foreign','foreign host untouched');}
 if(str_starts_with($label,'new')||$label==='future'){
  $providerClass='Deckerweb\\GitHubReleaseUpdater\\V2\\EnvironmentAuthProvider';putenv('DW221_SYNTHETIC=synthetic_only_not_a_real_token');$provider=new $providerClass($repo,'DW221_SYNTHETIC');$private=new $class('/'.$file,$repo,$label,'Description',[],['private'=>true,'auth'=>$provider,'translate'=>static fn($m)=>'localized '.$m]);
  $offer=$private->update(false,['Version'=>'1.0.0','UpdateURI'=>$repo],$file,[]);ck(str_contains($offer['package'],'/assets/42'),'new private mode works with stable auth interface in any order');ck($provider->token('https://github.com/deckerweb/foreign')===null,'credential repository boundary');ck(!str_contains(json_encode($cache),'synthetic_only'),'secret absent from metadata cache');putenv('DW221_SYNTHETIC');
  $url=$repo.'/releases/download/v3.1.0/plugin.zip';$tmp=tempnam(sys_get_temp_dir(),'dw221-');file_put_contents($tmp,'catalog-package');$release=['version'=>'3.1.0','package'=>$url,'sha256'=>hash_file('sha256',$tmp)];$calls=[];
  $cat=new $class('/'.$file,$repo,$label,'Description',[],['release_provider'=>static function($r,$f,$fresh)use(&$release,&$calls){$calls[]=$fresh;return $release;},'package_provider'=>static fn()=> $tmp]);
  $offer=$cat->update(false,['Version'=>'1.0.0','UpdateURI'=>$repo],$file,[]);ck($offer['version']==='3.1.0','new catalog provider retained');$context=['plugin'=>$file,'type'=>'plugin','action'=>'update'];ck($cat->download(false,$url,null,$context)===$tmp&&end($calls)===true,'fresh catalog and SHA validation');
  $release['sha256']=str_repeat('0',64);ck(is_wp_error($cat->download(false,$url,null,$context)),'tampered package refused');$release=null;ck(is_wp_error($cat->download(false,$url,null,$context)),'revoked catalog refuses without direct fallback');unlink($tmp);
 }
}
ck(count($hooks['update_plugins_github.com'])===count($order),'one update callback per host');ck(count($instances)===count($order),'all legacy-compatible hosts remain registered');ck(count($hooks['upgrader_source_selection'])===count($order),'one source callback per host');
echo json_encode(['php'=>PHP_VERSION,'order'=>$order,'checks'=>$checks,'passed'=>true]);
