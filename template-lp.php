<?php
/**
 * Template Name: LP テンプレート
 * Template Post Type: page
 *
 * 固定ページでLPセクションとShortcoderを混在させる場合に使用します。
 *
 * 使い方:
 *   固定ページの編集で「テンプレート: LP テンプレート」を選択し、
 *   ページ本文にショートコードを並べるだけで表示順を自由に制御できます。
 *
 *   例:
 *   [riff_lp_section key="hero"]
 *   [shortcoder id=my_custom_block]
 *   [riff_lp_section key="trust"]
 *
 * @package RiffLP
 */

get_header();

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
        the_content();
    endwhile;
endif;

get_footer();
