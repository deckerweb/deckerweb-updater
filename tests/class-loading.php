<?php
define('ABSPATH',__DIR__);
require $argv[1];
if(!class_exists('\Deckerweb\GitHubReleaseUpdater\V2\Updater'))require $argv[2];
$cap=defined('\Deckerweb\GitHubReleaseUpdater\V2\Updater::SUPPORTS_PRIVATE_REPOSITORIES');
if($cap!==($argv[3]==='private'))throw new Exception('Capability detection mismatch');
echo ($cap?'New V2 supports private mode':'Old V2: private integration must stop before provider construction')."\n";
