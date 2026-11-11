/**
 * RIFF LP Theme - Frontend JS
 */
(function () {
  'use strict';

  /* ============================================================
     FAQ アコーディオン
     ============================================================ */
  function initFaq() {
    document.querySelectorAll('.faq-q').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var item = btn.closest('.faq-item');
        var answer = item.querySelector('.faq-a');
        var isOpen = item.classList.contains('is-open');

        // 他を閉じる
        document.querySelectorAll('.faq-item.is-open').forEach(function (el) {
          el.classList.remove('is-open');
          var a = el.querySelector('.faq-a');
          if (a) { a.hidden = true; }
          var b = el.querySelector('.faq-q');
          if (b) { b.setAttribute('aria-expanded', 'false'); }
        });

        if (!isOpen) {
          item.classList.add('is-open');
          answer.hidden = false;
          btn.setAttribute('aria-expanded', 'true');
        }
      });

      // キーボード操作
      btn.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          btn.click();
        }
      });
    });
  }

  /* ============================================================
     スクロールトップボタン
     ============================================================ */
  function initScrollTop() {
    var btn = document.getElementById('scrollTop');
    if (!btn) return;

    window.addEventListener('scroll', function () {
      if (window.scrollY > 400) {
        btn.classList.add('visible');
      } else {
        btn.classList.remove('visible');
      }
    }, { passive: true });

    btn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ============================================================
     カウントアップアニメーション
     ============================================================ */
  function countUp(el, target, duration) {
    var start = 0;
    var startTime = null;

    function step(timestamp) {
      if (!startTime) startTime = timestamp;
      var progress = Math.min((timestamp - startTime) / duration, 1);
      var eased = 1 - Math.pow(1 - progress, 3); // ease-out-cubic
      var current = Math.floor(eased * target);
      el.textContent = current;
      if (progress < 1) {
        requestAnimationFrame(step);
      } else {
        el.textContent = target;
      }
    }
    requestAnimationFrame(step);
  }

  function initCountUp() {
    var els = document.querySelectorAll('.js-count');
    if (!els.length) return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var el = entry.target;
          var target = parseInt(el.dataset.target, 10) || 0;
          countUp(el, target, 1800);
          observer.unobserve(el);
        }
      });
    }, { threshold: 0.3 });

    els.forEach(function (el) { observer.observe(el); });
  }

  /* ============================================================
     スクロールアニメーション (data-animate)
     ============================================================ */
  function initScrollAnimate() {
    var style = document.createElement('style');
    style.textContent = [
      '[data-animate]{opacity:0;transition:opacity .6s ease,transform .6s ease;}',
      '[data-animate="fade-up"]{transform:translateY(30px);}',
      '[data-animate="fade-right"]{transform:translateX(-30px);}',
      '[data-animate="fade-left"]{transform:translateX(30px);}',
      '[data-animate].is-visible{opacity:1;transform:none;}',
    ].join('');
    document.head.appendChild(style);

    var items = document.querySelectorAll('[data-animate]');
    if (!items.length) return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry, i) {
        if (entry.isIntersecting) {
          setTimeout(function () {
            entry.target.classList.add('is-visible');
          }, i * 80);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    items.forEach(function (el) { observer.observe(el); });
  }

  /* ============================================================
     スムーススクロール（アンカーリンク）
     ============================================================ */
  function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(function (a) {
      a.addEventListener('click', function (e) {
        var id = a.getAttribute('href').slice(1);
        if (!id) return;
        var target = document.getElementById(id);
        if (!target) return;
        e.preventDefault();
        var headerH = document.querySelector('.site-header')
          ? document.querySelector('.site-header').offsetHeight
          : 0;
        var top = target.getBoundingClientRect().top + window.scrollY - headerH - 16;
        window.scrollTo({ top: top, behavior: 'smooth' });
      });
    });
  }

  /* ============================================================
     ヘッダーシャドウ（スクロール追従）
     ============================================================ */
  function initHeaderShadow() {
    var header = document.querySelector('.site-header');
    if (!header) return;
    window.addEventListener('scroll', function () {
      if (window.scrollY > 10) {
        header.style.boxShadow = '0 4px 24px rgba(0,0,0,.15)';
      } else {
        header.style.boxShadow = '';
      }
    }, { passive: true });
  }

  /* ============================================================
     ハンバーガーメニュー
     ============================================================ */
  function initHamburger() {
    var btn  = document.getElementById('hamburger-btn');
    var menu = document.getElementById('mobile-menu');
    if (!btn || !menu) return;

    function openMenu() {
      btn.classList.add('is-open');
      btn.setAttribute('aria-expanded', 'true');
      menu.classList.add('is-open');
      menu.setAttribute('aria-hidden', 'false');
    }

    function closeMenu() {
      btn.classList.remove('is-open');
      btn.setAttribute('aria-expanded', 'false');
      menu.classList.remove('is-open');
      menu.setAttribute('aria-hidden', 'true');
    }

    btn.addEventListener('click', function () {
      btn.classList.contains('is-open') ? closeMenu() : openMenu();
    });

    // メニューリンク・CTAクリックで閉じる
    menu.addEventListener('click', function (e) {
      if (e.target.tagName === 'A') {
        closeMenu();
      }
    });

    // 外側クリックで閉じる
    document.addEventListener('click', function (e) {
      if (!btn.contains(e.target) && !menu.contains(e.target)) {
        closeMenu();
      }
    });
  }

  /* ============================================================
     初期化
     ============================================================ */
  document.addEventListener('DOMContentLoaded', function () {
    initFaq();
    initScrollTop();
    initCountUp();
    initScrollAnimate();
    initSmoothScroll();
    initHeaderShadow();
    initHamburger();
  });
})();
