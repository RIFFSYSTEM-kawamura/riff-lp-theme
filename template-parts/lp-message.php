<?php
/**
 * Section: メッセージ
 */
$k       = 'message';
$label   = riff_lp_get_section_setting( $k, 'label' );
$title   = riff_lp_get_section_setting( $k, 'title' );
$body    = riff_lp_get_section_setting( $k, 'body' );
$sig     = riff_lp_get_section_setting( $k, 'sig' );
$photo   = riff_lp_get_image_url( $k, 'photo' );
?>
<!-- ============================================================
     メッセージセクション
     ============================================================ -->
<section class="message-section" id="message"<?php riff_lp_section_bg_style_attr( $section_key ); ?>>
    <div class="container">
        <div class="message-inner">
            <div class="message-photo-wrap" data-animate="fade-right">
                <div class="message-photo">
                    <?php if ( $photo ) : ?>
                    <img src="<?php echo esc_url( $photo ); ?>"
                         alt="代表写真"
                         loading="lazy">
                    <?php else : ?>
                    <div style="width:220px;height:280px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;">
                        <span style="font-size:5rem;">👤</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="message-content" data-animate="fade-left">
                <?php if ( $label ) : ?>
                <p class="message-label"><?php echo esc_html( $label ); ?></p>
                <?php endif; ?>
                <?php if ( $title ) : ?>
                <h2 class="message-title"><?php echo nl2br( esc_html( $title ) ); ?></h2>
                <?php endif; ?>
                <?php if ( $body ) : ?>
                <p class="message-body"><?php echo nl2br( esc_html( $body ) ); ?></p>
                <?php endif; ?>
                <?php if ( $sig ) : ?>
                <p class="message-sig"><?php echo esc_html( $sig ); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
