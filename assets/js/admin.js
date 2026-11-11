/**
 * RIFF LP Theme - Admin JS
 * jQuery + jQuery UI Sortable
 */
(function ($) {
  'use strict';

  /* ============================================================
     セクション並び替え (Sortable)
     ============================================================ */
  var $list    = $('#riff-section-list');
  var $snsList = $('#riff-sns-list');

  if ($list.length) {
    $list.sortable({
      handle: '.drag-handle',
      axis: 'y',
      placeholder: 'riff-sortable-placeholder',
      tolerance: 'pointer',
      start: function (e, ui) {
        ui.placeholder.height(ui.helper.outerHeight());
      },
    });
    $list.disableSelection();
  }

  /* SNS並び替え */
  if ($snsList.length) {
    $snsList.sortable({
      handle: '.drag-handle',
      axis: 'y',
      placeholder: 'riff-sortable-placeholder',
      tolerance: 'pointer',
      start: function (e, ui) {
        ui.placeholder.height(ui.helper.outerHeight());
      },
    });
    $snsList.disableSelection();
  }

  /* ============================================================
     並び順保存ボタン
     ============================================================ */
  $('#riff-save-order').on('click', function () {
    var $btn = $(this);
    var order = [];

    $list.find('.riff-section-item').each(function () {
      order.push($(this).data('key'));
    });

    $btn.prop('disabled', true).text('⏳ 保存中...');

    $.ajax({
      url: riffLP.ajaxUrl,
      type: 'POST',
      data: {
        action: 'riff_lp_save_order',
        nonce: riffLP.nonce,
        order: order,
      },
      success: function (res) {
        if (res.success) {
          $btn.text('✅ 保存しました！');
          setTimeout(function () {
            $btn.prop('disabled', false).text('💾 並び順を保存');
          }, 2000);
        } else {
          alert('保存に失敗しました');
          $btn.prop('disabled', false).text('💾 並び順を保存');
        }
      },
      error: function () {
        alert('通信エラーが発生しました');
        $btn.prop('disabled', false).text('💾 並び順を保存');
      },
    });
  });

  /* ============================================================
     セクション表示/非表示トグル
     ============================================================ */
  $(document).on('change', '.section-toggle', function () {
    var $cb      = $(this);
    var section  = $cb.data('section');
    var enabled  = $cb.is(':checked');
    var $item    = $cb.closest('.riff-section-item');

    // UIフィードバック
    $item.toggleClass('is-disabled', !enabled);

    $.ajax({
      url: riffLP.ajaxUrl,
      type: 'POST',
      data: {
        action: 'riff_lp_toggle_section',
        nonce: riffLP.nonce,
        section: section,
        enabled: enabled ? 1 : 0,
      },
      success: function (res) {
        if (!res.success) {
          // 失敗時は元に戻す
          $cb.prop('checked', !enabled);
          $item.toggleClass('is-disabled', enabled);
        }
      },
    });
  });

  /* ============================================================
     メディアアップローダー（画像選択）
     ============================================================ */
  var mediaUploader;

  $(document).on('click', '.riff-upload-btn', function (e) {
    e.preventDefault();
    var $btn      = $(this);
    var targetId  = $btn.data('target');
    var previewId = $btn.data('preview');

    if (mediaUploader) {
      mediaUploader.open();
      return;
    }

    mediaUploader = wp.media({
      title: '画像を選択',
      button: { text: 'この画像を使用' },
      multiple: false,
      library: { type: 'image' },
    });

    mediaUploader.on('select', function () {
      var attachment = mediaUploader.state().get('selection').first().toJSON();
      var imgUrl     = attachment.sizes && attachment.sizes.thumbnail
        ? attachment.sizes.thumbnail.url
        : attachment.url;

      $('#' + targetId).val(attachment.id);
      $('#' + previewId).html('<img src="' + imgUrl + '" alt="">');

      // 削除ボタン追加
      if (!$btn.next('.riff-remove-img').length) {
        $btn.after(
          '<button type="button" class="button riff-remove-img" ' +
          'data-target="' + targetId + '" ' +
          'data-preview="' + previewId + '">🗑 削除</button>'
        );
      }

      mediaUploader = null;
    });

    mediaUploader.open();
  });

  /* 画像削除 */
  $(document).on('click', '.riff-remove-img', function (e) {
    e.preventDefault();
    var targetId  = $(this).data('target');
    var previewId = $(this).data('preview');
    $('#' + targetId).val('');
    $('#' + previewId).empty();
    $(this).remove();
  });

  /* ============================================================
     フィールドフォーカス時のハイライト
     ============================================================ */
  $(document).on('focus', '.riff-input, .riff-textarea', function () {
    $(this).closest('.riff-field-row').addClass('is-focused');
  }).on('blur', '.riff-input, .riff-textarea', function () {
    $(this).closest('.riff-field-row').removeClass('is-focused');
  });

  /* ============================================================
     ヘッダーメニュー管理
     ============================================================ */
  // トグル機能
  $(document).on('click', '.riff-menu-toggle', function () {
    var toggle = $(this);
    var targetSelector = toggle.data('toggle');
    var $target = $(targetSelector);
    var isOpen = $target.is(':visible');
    
    $target.slideToggle(200);
    toggle.find('span:first').text(isOpen ? '▶' : '▼');
  });

  // メニュー追加ボタン
  $('#riff-menu-add').on('click', function (e) {
    e.preventDefault();
    var $builder = $('#riff-menu-builder');
    var itemCount = $builder.find('.riff-menu-item').length;
    
    var $newItem = $(
      '<div class="riff-menu-item" data-index="' + itemCount + '">' +
      '<div class="riff-menu-item-inner">' +
      '<input type="text" class="riff-menu-label" placeholder="メニュー名">' +
      '<input type="text" class="riff-menu-url" placeholder="https://... または #section-id">' +
      '<button type="button" class="button riff-menu-remove">🗑</button>' +
      '</div>' +
      '</div>'
    );
    
    $builder.append($newItem);
    updateMenuRemoveButtons();
  });

  // メニュー削除ボタン
  $(document).on('click', '.riff-menu-remove', function (e) {
    e.preventDefault();
    $(this).closest('.riff-menu-item').fadeOut(200, function () {
      $(this).remove();
      updateMenuRemoveButtons();
    });
  });

  // メニュー削除ボタン表示/非表示の更新
  function updateMenuRemoveButtons() {
    var $items = $('#riff-menu-builder .riff-menu-item');
    $items.each(function () {
      var $this = $(this);
      var hasContent = $this.find('.riff-menu-label').val() || $this.find('.riff-menu-url').val();
      var isLastEmpty = $this.is(':last-child') && !hasContent;
      $this.find('.riff-menu-remove').toggle(!isLastEmpty);
    });
  }

  // フォーム送信時にメニューデータをJSON化 & SNS順序をシリアライズ
  $('#riff-hf-form').on('submit', function () {
    var items = [];
    $('#riff-menu-builder .riff-menu-item').each(function () {
      var $this = $(this);
      var label = $this.find('.riff-menu-label').val().trim();
      var url = $this.find('.riff-menu-url').val().trim();
      if (label) {
        items.push({ label: label, url: url });
      }
    });
    $('#riff_lp_menu_data').val(JSON.stringify(items));

    var snsOrder = [];
    $snsList.find('.riff-sns-item').each(function () {
      snsOrder.push($(this).data('key'));
    });
    $('#riff_lp_sns_order').val(snsOrder.join(','));
  });

  // 初期表示時に削除ボタン設定 & カラーピッカー初期化
  $(document).ready(function () {
    updateMenuRemoveButtons();

    if ($.fn.wpColorPicker) {
      $('.riff-color-picker').wpColorPicker();
    }
  });

  /* ============================================================
     カスタムフォント管理
     ============================================================ */
  // フォント追加ボタン
  $('#riff-custom-font-add').on('click', function (e) {
    e.preventDefault();
    var $builder = $('#riff-custom-fonts-builder');
    var itemCount = $builder.find('.riff-custom-font-item').length;
    
    var $newItem = $(
      '<div class="riff-custom-font-item" data-index="' + itemCount + '">' +
      '<div class="riff-custom-font-inner">' +
      '<div class="riff-custom-font-row">' +
      '<label>フォント名</label>' +
      '<input type="text" class="riff-custom-font-label" placeholder="例：Noto Sans JP">' +
      '</div>' +
      '<div class="riff-custom-font-row">' +
      '<label>Google Fonts URL</label>' +
      '<input type="url" class="riff-custom-font-url" placeholder="https://fonts.googleapis.com/css2?family=...">' +
      '</div>' +
      '<div class="riff-custom-font-row">' +
      '<label>フォントスタック</label>' +
      '<input type="text" class="riff-custom-font-stack" placeholder="\'Noto Sans JP\', \'Meiryo\', sans-serif">' +
      '</div>' +
      '<button type="button" class="button riff-custom-font-remove">🗑 削除</button>' +
      '</div>' +
      '</div>'
    );
    
    $builder.append($newItem);
  });

  // カスタムフォント削除ボタン
  $(document).on('click', '.riff-custom-font-remove', function (e) {
    e.preventDefault();
    $(this).closest('.riff-custom-font-item').fadeOut(200, function () {
      $(this).remove();
    });
  });

  // フォーム送信時にカスタムフォントデータをJSON化
  $('#riff-global-form').on('submit', function () {
    var items = [];
    $('#riff-custom-fonts-builder .riff-custom-font-item').each(function () {
      var $this = $(this);
      var label = $this.find('.riff-custom-font-label').val().trim();
      var url = $this.find('.riff-custom-font-url').val().trim();
      var stack = $this.find('.riff-custom-font-stack').val().trim();
      if (label && url && stack) {
        items.push({ label: label, url: url, stack: stack });
      }
    });
    $('#riff_lp_custom_fonts_data').val(JSON.stringify(items));
  });

})(jQuery);
