<?php
/** Adapt this template inside the host; it is not a standalone plugin. */
defined( 'ABSPATH' ) || exit;
// The main plugin passes its own absolute __FILE__; do not use this adapter's __FILE__.
return static function ( string $main_file, string $includes_dir, string $repository, bool $private = false ): void {
    add_action( 'init', static function () use ( $main_file, $includes_dir, $repository, $private ): void {
        if ( ! class_exists( '\Deckerweb\GitHubReleaseUpdater\V2\Updater' ) ) {
            require_once $includes_dir . '/deckerweb-github-release-updater-v2.php';
        }
        if ( ! defined( '\Deckerweb\GitHubReleaseUpdater\V2\Updater::SUPPORTS_HOST_TRANSLATIONS' )
            || ( $private && ! defined( '\Deckerweb\GitHubReleaseUpdater\V2\Updater::SUPPORTS_PRIVATE_REPOSITORIES' ) ) ) {
            // Route this host-owned string to the host's actual authorized admin-notice workflow.
            // __( 'This plugin requires a newer deckerweb Updater integration.', 'example-host' );
            return;
        }
        $options = array( 'translate' => require $includes_dir . '/host-translations.php' );
        if ( $private ) {
            $options['private'] = true;
            $options['auth'] = new \Deckerweb\GitHubReleaseUpdater\V2\EnvironmentAuthProvider( $repository, 'DECKERWEB_UPDATER_TOKEN' );
        }
        $updater = new \Deckerweb\GitHubReleaseUpdater\V2\Updater(
            $main_file, $repository,
            __( 'Example Plugin', 'example-host' ),
            __( 'Example plugin description.', 'example-host' ),
            array(), // Replace with the host's existing bundled artwork maps.
            $options
        );
        $updater->register();
        // Register the host's full package validation at source_selection priority 30.
        // Keep its existing checks, activation lifecycle and safe configuration-error handling.
    } );
};
