<?php
/**
 * Section: グラフ・実績
 */
$k        = 'graph';
$tag      = riff_lp_get_section_setting( $k, 'tag' );
$headline = riff_lp_get_section_setting( $k, 'headline' );
$body     = riff_lp_get_section_setting( $k, 'body' );
$img_url  = riff_lp_get_image_url( $k, 'image' );
?>
<!-- ============================================================
     グラフ・実績セクション
     ============================================================ -->
<section class="lp-section graph-section" id="graph"<?php riff_lp_section_bg_style_attr( $section_key ); ?>>
    <div class="container">
        <div class="graph-inner">
            <?php if ( $img_url ) : ?>
            <div class="graph-img" data-animate="fade-right">
                <img src="<?php echo esc_url( $img_url ); ?>"
                     alt="電気代削減グラフ"
                     loading="lazy">
            </div>
            <?php else : ?>
            <!-- グラフプレースホルダー -->
            <div class="graph-img graph-placeholder" data-animate="fade-right">
                <?php riff_lp_inline_graph_placeholder(); ?>
            </div>
            <?php endif; ?>

            <div class="graph-text" data-animate="fade-left">
                <?php if ( $tag ) : ?>
                <span class="tag"><?php echo esc_html( $tag ); ?></span>
                <?php endif; ?>
                <?php if ( $headline ) : ?>
                <h2 class="graph-headline">
                    <?php echo nl2br( esc_html( $headline ) ); ?>
                </h2>
                <?php endif; ?>
                <?php if ( $body ) : ?>
                <p class="graph-body"><?php echo nl2br( esc_html( $body ) ); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php

// インラインSVGグラフ（画像未設定時の代替）
function riff_lp_inline_graph_placeholder() { ?>
<svg viewBox="0 0 460 280" xmlns="http://www.w3.org/2000/svg" width="100%" style="max-width:460px;">
  <rect width="460" height="280" rx="12" fill="#f0f4ff"/>
  <!-- 軸 -->
  <line x1="60" y1="220" x2="420" y2="220" stroke="#1565c0" stroke-width="2"/>
  <line x1="60" y1="40" x2="60" y2="220" stroke="#1565c0" stroke-width="2"/>
  <!-- グリッドライン -->
  <line x1="60" y1="160" x2="420" y2="160" stroke="#dde3f0" stroke-width="1" stroke-dasharray="4,4"/>
  <line x1="60" y1="100" x2="420" y2="100" stroke="#dde3f0" stroke-width="1" stroke-dasharray="4,4"/>
  <!-- 旧バー（高い） -->
  <rect x="100" y="70" width="80" height="150" rx="4" fill="#90caf9" opacity=".7"/>
  <text x="140" y="62" text-anchor="middle" font-size="11" fill="#555" font-family="sans-serif">切替前</text>
  <text x="140" y="86" text-anchor="middle" font-size="12" fill="#1565c0" font-weight="bold" font-family="sans-serif">100%</text>
  <!-- 新バー（低い） -->
  <rect x="280" y="140" width="80" height="80" rx="4" fill="#1565c0"/>
  <text x="320" y="132" text-anchor="middle" font-size="11" fill="#555" font-family="sans-serif">切替後</text>
  <text x="320" y="158" text-anchor="middle" font-size="12" fill="#fff" font-weight="bold" font-family="sans-serif">-30%</text>
  <!-- 矢印 -->
  <path d="M190,155 Q235,185 275,170" stroke="#ff6f00" stroke-width="2.5" fill="none" stroke-dasharray="6,3"/>
  <polygon points="272,163 279,173 266,170" fill="#ff6f00"/>
  <!-- ラベル -->
  <text x="230" y="265" text-anchor="middle" font-size="12" fill="#888" font-family="sans-serif">電気代比較イメージ（実際の削減幅は個別試算）</text>
</svg>
<?php }
