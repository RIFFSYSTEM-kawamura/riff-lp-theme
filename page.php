<?php
/**
 * RIFF LP Theme - 固定ページテンプレート
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
