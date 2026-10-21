<?php
/** Call this guard at the start of the host's source-selection validator. */
defined( 'ABSPATH' ) || exit;
/**
 * Determine whether a source-selection call belongs to this host update.
 * @param string $plugin_file Exact host plugin basename.
 * @param mixed $upgrader Calling upgrader instance.
 * @param array $context Core hook metadata; reject explicit conflicting actions.
 * @return bool True for this host update, including native bulk per-item calls.
 */
return static function ( string $plugin_file, $upgrader, array $context ): bool {
    if ( ( $context['plugin'] ?? '' ) !== $plugin_file
        || ( isset( $context['type'] ) && 'plugin' !== $context['type'] )
        || ( isset( $context['action'] ) && 'update' !== $context['action'] ) ) {
        return false;
    }
    return isset( $context['type'], $context['action'] )
        || ( $upgrader instanceof \Plugin_Upgrader && true === $upgrader->bulk );
};
