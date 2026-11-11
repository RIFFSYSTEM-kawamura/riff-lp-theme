<?php
/**
 * RIFF LP Theme - フロントページ / インデックス
 *
 * @package RiffLP
 */

get_header();

$sections     = riff_lp_default_sections();
$ordered_keys = riff_lp_get_section_order();

foreach ( $ordered_keys as $section_key ) :
    if ( ! isset( $sections[ $section_key ] ) ) continue;
    if ( ! riff_lp_is_section_enabled( $section_key ) ) continue;

    $tpl = RIFF_LP_DIR . "/template-parts/lp-{$section_key}.php";
    if ( file_exists( $tpl ) ) {
        include $tpl;
    }
endforeach;

get_footer();
