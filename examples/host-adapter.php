<?php
/** Adapt this template inside the host; it is not a standalone plugin. */
defined( 'ABSPATH' ) || exit;
// The main plugin passes its own absolute __FILE__; do not use this adapter's __FILE__.
/**
 * Schedule one host integration after translation initialization.
 * @param string $main_file Absolute host main file.
 * @param string $includes_dir Directory containing the updater and host translator.
 * @param string $repository Exact GitHub repository URL.
 * @param bool $private Whether repository-bound authentication is required.
 * @return void Registers an init callback; does not update or activate a plugin.
 */
return static function ( string $main_file, string $includes_dir, string $repository, bool $private = false ): void {
    /**
     * Register the shared engine and this host's update identity.
     * @return void Adds update hooks after checking available capabilities.
     */
    add_action( 'init', static function () use ( $main_file, $includes_dir, $repository, $private ): void {
        // Always load this host's pinned copy. The returned class is independent
        // of an older legacy V2 class selected by another plugin's load order.
        $updater_class = require $includes_dir . '/deckerweb-github-release-updater-v2.php';
        if ( ! is_string( $updater_class ) || ! class_exists( $updater_class, false )
            || ! defined( $updater_class . '::SUPPORTS_HOST_TRANSLATIONS' )
            || ! constant( $updater_class . '::SUPPORTS_HOST_TRANSLATIONS' )
            || ( $private && ( ! defined( $updater_class . '::SUPPORTS_PRIVATE_REPOSITORIES' )
                || ! constant( $updater_class . '::SUPPORTS_PRIVATE_REPOSITORIES' ) ) ) ) {
            // Keep the host's existing localized configuration notice and safe pause.
            return;
        }
        $options = array( 'translate' => require $includes_dir . '/host-translations.php' );
        if ( $private ) {
            $options['private'] = true;
            $options['auth'] = new \Deckerweb\GitHubReleaseUpdater\V2\EnvironmentAuthProvider( $repository, 'DECKERWEB_UPDATER_TOKEN' );
        }
        $updater = new $updater_class(
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
