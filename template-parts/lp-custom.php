<?php
/**
 * カスタムセクション共通テンプレート
 * $section_key は呼び出し元（front-page.php / shortcode）からスコープで渡される
 *
 * @package RiffLP
 */

if ( empty( $section_key ) ) return;

$html       = riff_lp_get_section_setting( $section_key, 'html' );
$css        = riff_lp_get_section_setting( $section_key, 'css' );
$js         = riff_lp_get_section_setting( $section_key, 'js' );
$section_id = riff_lp_get_section_setting( $section_key, 'section_id' );

if ( ! $html && ! $css ) return;

if ( $css ) {
    echo '<style>' . $css . '</style>' . "\n";
}

$id_attr = $section_id ? ' id="' . esc_attr( $section_id ) . '"' : '';
echo '<section class="riff-custom-section"' . $id_attr . '>' . "\n";
echo $html . "\n";
echo '</section>' . "\n";

if ( $js ) {
    echo '<script>(function(){' . "\n" . $js . "\n" . '})();</script>' . "\n";
}
