<?php
/** Call this guard at the start of the host's source-selection validator. */
defined( 'ABSPATH' ) || exit;
return static function ( string $plugin_file, $upgrader, array $context ): bool {
    if ( ( $context['plugin'] ?? '' ) !== $plugin_file
        || ( isset( $context['type'] ) && 'plugin' !== $context['type'] )
        || ( isset( $context['action'] ) && 'update' !== $context['action'] ) ) {
        return false;
    }
    return isset( $context['type'], $context['action'] )
        || ( $upgrader instanceof \Plugin_Upgrader && true === $upgrader->bulk );
};
