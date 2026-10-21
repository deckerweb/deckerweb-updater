<?php
/** Remove only host-owned updater metadata during a WordPress uninstall. */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) || ! defined( 'ABSPATH' ) ) { exit; }
$deckerweb_updater_class = require __DIR__ . '/deckerweb-github-release-updater-v2.php';
if ( is_string( $deckerweb_updater_class ) && method_exists( $deckerweb_updater_class, 'clear_repository_cache' ) ) {
    $deckerweb_updater_class::clear_repository_cache( 'https://github.com/deckerweb/example-host' );
}
