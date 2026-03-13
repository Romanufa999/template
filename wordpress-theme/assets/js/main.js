/**
 * СтройМСК — Главный JavaScript файл темы WordPress
 * Заменяет всю React-интерактивность из оригинального Next.js сайта
 * Чистый vanilla JS, без зависимостей
 */

document.addEventListener('DOMContentLoaded', () => {
  'use strict';

  /* ==========================================================================
     УТИЛИТЫ
     ========================================================================== */

  /**
   * Безопасный querySelector с проверкой существования
   */
  const qs = (selector, parent = document) => parent.querySelector(selector);
  const qsa = (selector, parent = document) => [...parent.querySelectorAll(selector)];

  /**
   * Debounce — ограничение частоты вызова функции
   */
  function debounce(fn, delay = 100) {
    let timer;
    return (...args) => {
      clearTimeout(timer);
      timer = setTimeout(() => fn.apply(null, args), delay);
    };
  }

  /**
   * Throttle — вызов функции не чаще чем раз в interval мс
   */
  function throttle(fn, interval = 100) {
    let lastTime = 0;
    return (...args) => {
      const now = Date.now();
      if (now - lastTime >= interval) {
        lastTime = now;
        fn.apply(null, args);
      }
    };
  }

  /**
   * Закрытие любого модала/попапа по Escape
   * Регистрируется один раз, вызывает все зарегистрированные обработчики
   */
  const escapeHandlers = [];
  function onEscape(handler) {
    escapeHandlers.push(handler);
  }
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      escapeHandlers.forEach((handler) => handler(e));
    }
  });

  /* ==========================================================================
     1. ЭФФЕКТ ШАПКИ ПРИ СКРОЛЛЕ
     ========================================================================== */

  function initHeaderScroll() {
    const header = qs('.site-header');
    if (!header) return;

    const SCROLL_THRESHOLD = 50;

    const handleScroll = throttle(() => {
      if (window.scrollY > SCROLL_THRESHOLD) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    }, 50);

    window.addEventListener('scroll', handleScroll, { passive: true });
    // Проверяем начальное состояние
    handleScroll();
  }

  /* ==========================================================================
     2. МОБИЛЬНОЕ МЕНЮ
     ========================================================================== */

  function initMobileMenu() {
    const burger = qs('.burger-button, .mobile-menu-toggle, #menu-toggle');
    const drawer = qs('.mobile-drawer, .mobile-menu, #main-nav');
    const overlay = qs('.mobile-overlay, .menu-overlay');
    const body = document.body;

    if (!burger || !drawer) return;

    function openMenu() {
      drawer.classList.add('active', 'open');
      burger.classList.add('active');
      body.classList.add('menu-open', 'no-scroll');
      if (overlay) overlay.classList.add('active', 'visible');
      burger.setAttribute('aria-expanded', 'true');
    }

    function closeMenu() {
      drawer.classList.remove('active', 'open');
      burger.classList.remove('active');
      body.classList.remove('menu-open', 'no-scroll');
      if (overlay) overlay.classList.remove('active', 'visible');
      burger.setAttribute('aria-expanded', 'false');
    }

    burger.addEventListener('click', (e) => {
      e.preventDefault();
      if (drawer.classList.contains('active')) {
        closeMenu();
      } else {
        openMenu();
      }
    });

    // Закрытие по клику на оверлей
    if (overlay) {
      overlay.addEventListener('click', closeMenu);
    }

    // Закрытие при клике на ссылку внутри меню
    drawer.addEventListener('click', (e) => {
      const link = e.target.closest('a');
      if (link) {
        closeMenu();
      }
    });

    // Закрытие по Escape
    onEscape(closeMenu);

    // Закрытие при ресайзе на десктоп
    window.addEventListener('resize', debounce(() => {
      if (window.innerWidth > 1024 && drawer.classList.contains('active')) {
        closeMenu();
      }
    }, 200));
  }

  /* ==========================================================================
     3. ПЛАВНАЯ ПРОКРУТКА К ЯКОРЯМ
     ========================================================================== */

  function initSmoothScroll() {
    // Высота шапки для смещения
    function getHeaderOffset() {
      const header = qs('.site-header');
      return header ? header.offsetHeight + 10 : 80;
    }

    document.addEventListener('click', (e) => {
      const link = e.target.closest('a[href*="#"]');
      if (!link) return;

      const href = link.getAttribute('href');
      // Пропускаем пустые хеши и спецссылки
      if (!href || href === '#' || href === '#!') return;

      // Извлекаем ID якоря
      let targetId;
      try {
        const url = new URL(href, window.location.origin);
        // Если ссылка на другую страницу — не перехватываем
        if (url.pathname !== window.location.pathname && !href.startsWith('#')) return;
        targetId = url.hash;
      } catch {
        targetId = href.startsWith('#') ? href : null;
      }

      if (!targetId || targetId === '#') return;

      const target = qs(targetId);
      if (!target) return;

      e.preventDefault();

      const offset = getHeaderOffset();
      const targetPosition = target.getBoundingClientRect().top + window.scrollY - offset;

      window.scrollTo({
        top: targetPosition,
        behavior: 'smooth',
      });

      // Обновляем хеш без прыжка
      history.pushState(null, '', targetId);
    });
  }

  /* ==========================================================================
     4. МАСКА ТЕЛЕФОНА +7 (XXX) XXX-XX-XX
     ========================================================================== */

  function initPhoneMask() {
    /** Селектор всех телефонных полей */
    const PHONE_SELECTOR = 'input[type="tel"], input[data-phone-mask], input.phone-input';

    /**
     * Применяет маску к значению телефона
     */
    function applyPhoneMask(value) {
      // Удаляем всё кроме цифр
      let digits = value.replace(/\D/g, '');

      // Если начинается с 8, заменяем на 7
      if (digits.startsWith('8')) {
        digits = '7' + digits.slice(1);
      }
      // Если не начинается с 7, добавляем
      if (!digits.startsWith('7') && digits.length > 0) {
        digits = '7' + digits;
      }

      // Ограничиваем 11 цифрами
      digits = digits.slice(0, 11);

      // Форматируем по шаблону +7 (XXX) XXX-XX-XX
      let result = '';
      if (digits.length > 0) result = '+' + digits[0];
      if (digits.length > 1) result += ' (' + digits.slice(1, 4);
      if (digits.length >= 4) result += ')';
      if (digits.length > 4) result += ' ' + digits.slice(4, 7);
      if (digits.length > 7) result += '-' + digits.slice(7, 9);
      if (digits.length > 9) result += '-' + digits.slice(9, 11);

      return result;
    }

    /**
     * Проверяет, полностью ли заполнен телефон (11 цифр)
     */
    function isPhoneComplete(value) {
      const digits = value.replace(/\D/g, '');
      return digits.length === 11;
    }

    // Делегирование — ввод символов
    document.addEventListener('input', (e) => {
      const input = e.target;
      if (!input.matches(PHONE_SELECTOR)) return;

      const cursorPos = input.selectionStart;
      const oldLength = input.value.length;
      input.value = applyPhoneMask(input.value);
      const newLength = input.value.length;

      // Корректируем позицию курсора
      const newPos = cursorPos + (newLength - oldLength);
      input.setSelectionRange(newPos, newPos);
    });

    // При фокусе — ставим +7 если пусто
    document.addEventListener('focus', (e) => {
      const input = e.target;
      if (!input.matches(PHONE_SELECTOR)) return;

      if (!input.value) {
        input.value = '+7';
      }
    }, true);

    // При потере фокуса — убираем если только +7
    document.addEventListener('blur', (e) => {
      const input = e.target;
      if (!input.matches(PHONE_SELECTOR)) return;

      if (input.value === '+7' || input.value === '+7 (' || input.value === '+') {
        input.value = '';
      }
    }, true);

    // Разрешаем только цифры и управляющие клавиши
    document.addEventListener('keydown', (e) => {
      const input = e.target;
      if (!input.matches(PHONE_SELECTOR)) return;

      const allowed = [
        'Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight',
        'Home', 'End', 'Enter',
      ];
      if (allowed.includes(e.key)) return;
      if (e.ctrlKey || e.metaKey) return; // Ctrl+C, Ctrl+V и т.д.
      if (!/\d/.test(e.key)) {
        e.preventDefault();
      }
    });

    // Обработка вставки (paste)
    document.addEventListener('paste', (e) => {
      const input = e.target;
      if (!input.matches(PHONE_SELECTOR)) return;

      e.preventDefault();
      const pasted = (e.clipboardData || window.clipboardData).getData('text');
      input.value = applyPhoneMask(pasted);
    });

    // Экспортируем для использования в других модулях
    window.stroymskPhoneMask = { applyPhoneMask, isPhoneComplete };
  }

  /* ==========================================================================
     5. АНИМАЦИЯ ПОЯВЛЕНИЯ ПРИ СКРОЛЛЕ (Scroll Reveal)
     ========================================================================== */

  function initScrollReveal() {
    const revealElements = qsa('.scroll-reveal');
    if (!revealElements.length) return;

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('revealed');
            // Отключаем наблюдение после появления (анимация одноразовая)
            observer.unobserve(entry.target);
          }
        });
      },
      {
        threshold: 0.1,
        rootMargin: '0px 0px -30px 0px',
      }
    );

    revealElements.forEach((el) => observer.observe(el));
  }

  /* ==========================================================================
     6. FAQ АККОРДЕОН
     ========================================================================== */

  function initFaqAccordion() {
    const faqContainer = qs('.faq-section, .faq-accordion, .faq-list');
    if (!faqContainer) return;

    faqContainer.addEventListener('click', (e) => {
      const question = e.target.closest(
        '.faq-question, .faq-item__question, .accordion-header'
      );
      if (!question) return;

      const item = question.closest('.faq-item, .accordion-item');
      if (!item) return;

      const answer = qs('.faq-answer, .faq-item__answer, .accordion-body', item);
      if (!answer) return;

      const isOpen = item.classList.contains('active');

      // Закрываем все остальные (только один открыт одновременно)
      qsa('.faq-item.active, .accordion-item.active', faqContainer).forEach((openItem) => {
        if (openItem !== item) {
          openItem.classList.remove('active');
          const openAnswer = qs('.faq-answer, .faq-item__answer, .accordion-body', openItem);
          if (openAnswer) {
            openAnswer.style.maxHeight = null;
            openAnswer.style.opacity = '0';
          }
          const openIcon = qs('.faq-icon, .accordion-icon', openItem);
          if (openIcon) openIcon.classList.remove('rotated');
        }
      });

      // Переключаем текущий
      if (isOpen) {
        item.classList.remove('active');
        answer.style.maxHeight = null;
        answer.style.opacity = '0';
      } else {
        item.classList.add('active');
        answer.style.maxHeight = answer.scrollHeight + 'px';
        answer.style.opacity = '1';
      }

      // Иконка раскрытия
      const icon = qs('.faq-icon, .accordion-icon', item);
      if (icon) icon.classList.toggle('rotated', !isOpen);
    });

    // Поддержка клавиатуры (Enter и Пробел)
    faqContainer.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        const question = e.target.closest(
          '.faq-question, .faq-item__question, .accordion-header'
        );
        if (question) {
          e.preventDefault();
          question.click();
        }
      }
    });
  }

  /* ==========================================================================
     7. СЛАЙДШОУ ГЕРОЯ (автопереключение фоновых изображений)
     ========================================================================== */

  function initHeroSlideshow() {
    const hero = qs('.hero-section, .hero');
    if (!hero) return;

    const slides = qsa('.hero-slide, .hero-bg-slide', hero);
    const projectCards = qsa('.hero-project-card, .project-card', hero);
    if (slides.length <= 1 && projectCards.length <= 1) return;

    let currentIndex = 0;
    const totalSlides = Math.max(slides.length, projectCards.length);
    let autoPlayInterval = null;
    let isPaused = false;

    function goToSlide(index) {
      // Нормализуем индекс (зацикливание)
      currentIndex = ((index % totalSlides) + totalSlides) % totalSlides;

      // Переключаем слайды фона
      slides.forEach((slide, i) => {
        slide.classList.toggle('active', i === currentIndex);
      });

      // Обновляем активную карточку проекта
      projectCards.forEach((card, i) => {
        card.classList.toggle('active', i === currentIndex);
      });

      // Обновляем индикаторы-точки если есть
      qsa('.hero-dot, .slide-dot', hero).forEach((dot, i) => {
        dot.classList.toggle('active', i === currentIndex);
      });
    }

    function nextSlide() {
      goToSlide(currentIndex + 1);
    }

    function prevSlide() {
      goToSlide(currentIndex - 1);
    }

    function startAutoPlay() {
      stopAutoPlay();
      autoPlayInterval = setInterval(() => {
        if (!isPaused) nextSlide();
      }, 6000); // 6 секунд между слайдами
    }

    function stopAutoPlay() {
      if (autoPlayInterval) {
        clearInterval(autoPlayInterval);
        autoPlayInterval = null;
      }
    }

    // Пауза автоплея при наведении мыши
    hero.addEventListener('mouseenter', () => { isPaused = true; });
    hero.addEventListener('mouseleave', () => { isPaused = false; });

    // Клик по индикаторам-точкам
    hero.addEventListener('click', (e) => {
      const dot = e.target.closest('.hero-dot, .slide-dot');
      if (dot) {
        const idx = parseInt(dot.dataset.index, 10);
        if (!isNaN(idx)) {
          goToSlide(idx);
          startAutoPlay(); // Перезапускаем таймер
        }
      }
    });

    // Инициализация
    goToSlide(0);
    startAutoPlay();

    // Экспортируем для слайдера проектов (п.8)
    window.stroymskHeroSlideshow = {
      goToSlide, nextSlide, prevSlide,
      getCurrentIndex: () => currentIndex,
      getTotal: () => totalSlides,
    };
  }

  /* ==========================================================================
     8. СЛАЙДЕР КАРТОЧЕК ПРОЕКТОВ В ГЕРОЕ
     ========================================================================== */

  function initProjectCardSlider() {
    const slider = qs('.hero-projects-slider, .projects-slider, .hero-project-cards');
    if (!slider) return;

    const prevBtn = qs('.slider-prev, .projects-prev', slider.parentElement);
    const nextBtn = qs('.slider-next, .projects-next', slider.parentElement);

    if (!prevBtn && !nextBtn) return;

    // Используем слайдшоу героя если доступно (синхронизация)
    if (window.stroymskHeroSlideshow) {
      if (prevBtn) prevBtn.addEventListener('click', () => window.stroymskHeroSlideshow.prevSlide());
      if (nextBtn) nextBtn.addEventListener('click', () => window.stroymskHeroSlideshow.nextSlide());
      return;
    }

    // Автономный режим слайдера
    const cards = qsa('.project-card, .hero-project-card', slider);
    if (cards.length <= 1) return;

    let currentCard = 0;

    function showCard(index) {
      currentCard = ((index % cards.length) + cards.length) % cards.length;
      cards.forEach((card, i) => {
        card.classList.toggle('active', i === currentCard);
        card.style.display = i === currentCard ? '' : 'none';
      });
    }

    if (prevBtn) prevBtn.addEventListener('click', () => showCard(currentCard - 1));
    if (nextBtn) nextBtn.addEventListener('click', () => showCard(currentCard + 1));

    showCard(0);
  }

  /* ==========================================================================
     9–10. ПАУЗА БЕГУЩЕЙ СТРОКИ (Marquee — партнёры, технологии)
     ========================================================================== */

  function initMarqueePause() {
    const marquees = qsa('.marquee, .partners-marquee, .tech-marquee, .marquee-track');
    if (!marquees.length) return;

    marquees.forEach((marquee) => {
      // Находим анимированные дочерние элементы
      const track = qs('.marquee-inner, .marquee-track, .marquee-content', marquee) || marquee;

      marquee.addEventListener('mouseenter', () => {
        track.style.animationPlayState = 'paused';
        // Также для всех дочерних анимированных элементов
        qsa('[class*="marquee"]', track).forEach((child) => {
          child.style.animationPlayState = 'paused';
        });
      });

      marquee.addEventListener('mouseleave', () => {
        track.style.animationPlayState = 'running';
        qsa('[class*="marquee"]', track).forEach((child) => {
          child.style.animationPlayState = 'running';
        });
      });
    });
  }

  /* ==========================================================================
     11. ЛАЙТБОКС ДЛЯ КЕЙСОВ
     ========================================================================== */

  function initCasesLightbox() {
    // Создаём лайтбокс динамически если его нет в разметке
    let lightbox = qs('.cases-lightbox');
    if (!lightbox) {
      lightbox = document.createElement('div');
      lightbox.className = 'cases-lightbox';
      lightbox.setAttribute('role', 'dialog');
      lightbox.setAttribute('aria-modal', 'true');
      lightbox.setAttribute('aria-label', 'Просмотр изображения');
      lightbox.innerHTML = `
        <div class="lightbox-overlay"></div>
        <div class="lightbox-content">
          <button class="lightbox-close" aria-label="Закрыть">&times;</button>
          <img class="lightbox-image" src="" alt="" />
          <div class="lightbox-caption"></div>
        </div>
      `;
      document.body.appendChild(lightbox);
    }

    const lightboxImage = qs('.lightbox-image', lightbox);
    const lightboxCaption = qs('.lightbox-caption', lightbox);
    const closeBtn = qs('.lightbox-close', lightbox);
    const overlayEl = qs('.lightbox-overlay', lightbox);

    function openLightbox(imageSrc, caption) {
      if (!imageSrc) return;
      lightboxImage.src = imageSrc;
      lightboxImage.alt = caption || '';
      if (lightboxCaption) lightboxCaption.textContent = caption || '';
      lightbox.classList.add('active', 'open');
      document.body.classList.add('no-scroll');
      // Фокус на кнопку закрытия для доступности
      if (closeBtn) closeBtn.focus();
    }

    function closeLightbox() {
      lightbox.classList.remove('active', 'open');
      document.body.classList.remove('no-scroll');
      lightboxImage.src = '';
    }

    // Делегирование — клик по карточке кейса
    document.addEventListener('click', (e) => {
      const caseCard = e.target.closest('.case-card, .cases-card, .case-item');
      if (!caseCard) return;

      // Не открываем если клик по интерактивному элементу внутри карточки
      if (e.target.closest('a, button')) return;

      const img = qs('img', caseCard);
      const imageSrc =
        caseCard.dataset.fullImage ||
        caseCard.dataset.image ||
        (img ? (img.dataset.src || img.src) : null);
      const caption =
        caseCard.dataset.caption ||
        (qs('.case-title, .case-card__title', caseCard)?.textContent) || '';

      openLightbox(imageSrc, caption);
    });

    // Закрытие
    if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
    if (overlayEl) overlayEl.addEventListener('click', closeLightbox);
    onEscape(closeLightbox);

    // Клик по контенту не закрывает (остановка всплытия)
    qs('.lightbox-content', lightbox)?.addEventListener('click', (e) => e.stopPropagation());
  }

  /* ==========================================================================
     12. ВИДЕО ПОПАП (Rutube iframe)
     ========================================================================== */

  function initVideoPopup() {
    // Создаём попап динамически если его нет в разметке
    let popup = qs('.video-popup');
    if (!popup) {
      popup = document.createElement('div');
      popup.className = 'video-popup';
      popup.setAttribute('role', 'dialog');
      popup.setAttribute('aria-modal', 'true');
      popup.setAttribute('aria-label', 'Видео');
      popup.innerHTML = `
        <div class="video-popup__overlay"></div>
        <div class="video-popup__content">
          <button class="video-popup__close" aria-label="Закрыть">&times;</button>
          <div class="video-popup__iframe-wrapper">
            <iframe class="video-popup__iframe" src="" frameborder="0"
              allow="autoplay; fullscreen" allowfullscreen></iframe>
          </div>
        </div>
      `;
      document.body.appendChild(popup);
    }

    const iframe = qs('.video-popup__iframe', popup);
    const closeBtn = qs('.video-popup__close', popup);
    const overlayEl = qs('.video-popup__overlay', popup);

    function openVideoPopup(videoUrl) {
      if (!videoUrl || !iframe) return;

      // Формируем URL для embed если нужно
      let embedUrl = videoUrl;
      if (videoUrl.includes('rutube.ru/video/') && !videoUrl.includes('/embed/')) {
        // rutube.ru/video/XXXXX/ -> rutube.ru/play/embed/XXXXX/
        const videoId = videoUrl.split('/video/')[1]?.replace(/\/$/, '');
        if (videoId) embedUrl = `https://rutube.ru/play/embed/${videoId}/`;
      }

      iframe.src = embedUrl;
      popup.classList.add('active', 'open');
      document.body.classList.add('no-scroll');
      if (closeBtn) closeBtn.focus();
    }

    function closeVideoPopup() {
      popup.classList.remove('active', 'open');
      document.body.classList.remove('no-scroll');
      iframe.src = ''; // Останавливаем видео при закрытии
    }

    // Делегирование — клик по видео-карточке или триггеру
    document.addEventListener('click', (e) => {
      const videoTrigger = e.target.closest(
        '.video-card, .video-trigger, [data-video-url], [data-rutube-id]'
      );
      if (!videoTrigger) return;

      e.preventDefault();

      let videoUrl = '';
      if (videoTrigger.dataset.videoUrl) {
        videoUrl = videoTrigger.dataset.videoUrl;
      } else if (videoTrigger.dataset.rutubeId) {
        videoUrl = `https://rutube.ru/play/embed/${videoTrigger.dataset.rutubeId}/`;
      } else if (videoTrigger.getAttribute('href')) {
        videoUrl = videoTrigger.getAttribute('href');
      }

      openVideoPopup(videoUrl);
    });

    // Закрытие
    if (closeBtn) closeBtn.addEventListener('click', closeVideoPopup);
    if (overlayEl) overlayEl.addEventListener('click', closeVideoPopup);
    onEscape(closeVideoPopup);
  }

  /* ==========================================================================
     13. ВКЛАДКИ КАБИНЕТА (Фото, Чеклисты, Документы)
     ========================================================================== */

  function initCabinetTabs() {
    const tabContainers = qsa('.cabinet-tabs, .tabs-container');

    tabContainers.forEach((container) => {
      const tabs = qsa('.tab-button, .cabinet-tab, [data-tab]', container);
      const panelParent = container.closest('.cabinet-section, .cabinet') || container.parentElement;
      const panels = qsa('.tab-panel, .cabinet-panel, .tab-content, [data-tab-panel]', panelParent);

      if (!tabs.length || !panels.length) return;

      tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
          const targetTab = tab.dataset.tab || tab.dataset.target;

          // Деактивируем все вкладки
          tabs.forEach((t) => {
            t.classList.remove('active');
            t.setAttribute('aria-selected', 'false');
          });
          panels.forEach((p) => {
            p.classList.remove('active');
            p.setAttribute('hidden', '');
          });

          // Активируем текущую
          tab.classList.add('active');
          tab.setAttribute('aria-selected', 'true');

          const targetPanel = panels.find(
            (p) =>
              p.dataset.tabPanel === targetTab ||
              p.dataset.tab === targetTab ||
              p.id === targetTab
          );
          if (targetPanel) {
            targetPanel.classList.add('active');
            targetPanel.removeAttribute('hidden');
          }
        });
      });

      // Навигация клавиатурой (стрелки лево/право между вкладками)
      container.addEventListener('keydown', (e) => {
        if (!['ArrowLeft', 'ArrowRight'].includes(e.key)) return;
        const currentTab = document.activeElement;
        const currentIndex = tabs.indexOf(currentTab);
        if (currentIndex === -1) return;

        let nextIndex;
        if (e.key === 'ArrowRight') {
          nextIndex = (currentIndex + 1) % tabs.length;
        } else {
          nextIndex = (currentIndex - 1 + tabs.length) % tabs.length;
        }

        tabs[nextIndex].focus();
        tabs[nextIndex].click();
      });
    });
  }

  /* ==========================================================================
     14. ДЕМО-ПОПАП КАБИНЕТА
     ========================================================================== */

  function initCabinetDemoPopup() {
    const popup = qs('.cabinet-demo-popup, .demo-popup');
    if (!popup) return;

    const openBtns = qsa('[data-open-demo], .open-demo-btn, .cabinet-demo-trigger');
    const closeBtn = qs('.demo-popup__close, .popup-close', popup);
    const overlayEl = qs('.demo-popup__overlay, .popup-overlay', popup);

    function openPopup() {
      popup.classList.add('active', 'open');
      document.body.classList.add('no-scroll');
      if (closeBtn) closeBtn.focus();
    }

    function closePopup() {
      popup.classList.remove('active', 'open');
      document.body.classList.remove('no-scroll');
    }

    openBtns.forEach((btn) => btn.addEventListener('click', (e) => {
      e.preventDefault();
      openPopup();
    }));

    if (closeBtn) closeBtn.addEventListener('click', closePopup);
    if (overlayEl) overlayEl.addEventListener('click', closePopup);
    onEscape(closePopup);
  }

  /* ==========================================================================
     15. КАРУСЕЛЬ КОМАНДЫ
     ========================================================================== */

  function initTeamCarousel() {
    const section = qs('.team-section, .team-carousel');
    if (!section) return;

    const members = qsa('.team-member', section);
    const photo = qs('.team-photo, .team-member-photo img', section);
    const nameEl = qs('.team-name, .team-member-name', section);
    const roleEl = qs('.team-role, .team-member-role', section);
    const bioEl = qs('.team-bio, .team-member-bio', section);
    const prevBtn = qs('.team-prev, .carousel-prev', section);
    const nextBtn = qs('.team-next, .carousel-next', section);
    const counter = qs('.team-counter, .carousel-counter', section);

    // Карточки (альтернативная разметка)
    const cards = qsa('.team-card', section);
    const hasDetailView = !!(photo || nameEl);

    let currentMember = 0;
    const total = members.length || cards.length;

    if (total === 0) return;

    function showMember(index) {
      currentMember = ((index % total) + total) % total;

      if (hasDetailView && members.length) {
        // Режим с детальным отображением (фото + имя + роль + био)
        const member = members[currentMember];
        if (photo) {
          photo.src = member.dataset.photo || '';
          photo.alt = member.dataset.name || '';
        }
        if (nameEl) nameEl.textContent = member.dataset.name || '';
        if (roleEl) roleEl.textContent = member.dataset.role || '';
        if (bioEl) bioEl.textContent = member.dataset.bio || '';
      }

      // Обновляем активную карточку
      cards.forEach((card, i) => {
        card.classList.toggle('active', i === currentMember);
      });

      // Обновляем счётчик
      if (counter) {
        counter.textContent = `${currentMember + 1} / ${total}`;
      }

      // Обновляем индикаторы
      qsa('.team-dot, .carousel-dot', section).forEach((dot, i) => {
        dot.classList.toggle('active', i === currentMember);
      });
    }

    if (prevBtn) prevBtn.addEventListener('click', () => showMember(currentMember - 1));
    if (nextBtn) nextBtn.addEventListener('click', () => showMember(currentMember + 1));

    // Клик по индикаторам
    section.addEventListener('click', (e) => {
      const dot = e.target.closest('.team-dot, .carousel-dot');
      if (dot) {
        const idx = parseInt(dot.dataset.index, 10);
        if (!isNaN(idx)) showMember(idx);
      }
    });

    // Поддержка свайпа на мобильных
    let touchStartX = 0;
    section.addEventListener('touchstart', (e) => {
      touchStartX = e.touches[0].clientX;
    }, { passive: true });

    section.addEventListener('touchend', (e) => {
      const diff = touchStartX - e.changedTouches[0].clientX;
      if (Math.abs(diff) > 50) {
        if (diff > 0) showMember(currentMember + 1);  // свайп влево — следующий
        else showMember(currentMember - 1);            // свайп вправо — предыдущий
      }
    }, { passive: true });

    showMember(0);
  }

  /* ==========================================================================
     16. ПЕРИОДИЧЕСКАЯ ТАБЛИЦА УСЛУГ
     ========================================================================== */

  function initServicePeriodicTable() {
    const section = qs('.periodic-table, .services-table, .service-periodic');
    if (!section) return;

    const elements = qsa('.periodic-element, .service-element, .table-element', section);
    const detailPhoto = qs('.service-detail-photo, .element-photo img', section);
    const detailTitle = qs('.service-detail-title, .element-title', section);
    const detailDesc = qs('.service-detail-description, .element-desc', section);
    const detailInfo = qs('.service-detail-info, .element-info', section);

    if (!elements.length) return;

    function activateElement(element) {
      // Убираем активность со всех элементов
      elements.forEach((el) => el.classList.remove('active'));

      // Активируем текущий
      element.classList.add('active');

      // Обновляем детальную информацию справа
      if (detailPhoto) {
        detailPhoto.src = element.dataset.photo || '';
        detailPhoto.alt = element.dataset.title || '';
      }
      if (detailTitle) detailTitle.textContent = element.dataset.title || '';
      if (detailDesc) detailDesc.textContent = element.dataset.description || '';
      if (detailInfo) detailInfo.innerHTML = element.dataset.info || '';
    }

    // Делегирование кликов
    section.addEventListener('click', (e) => {
      const element = e.target.closest('.periodic-element, .service-element, .table-element');
      if (element) activateElement(element);
    });

    // Навигация клавиатурой
    section.addEventListener('keydown', (e) => {
      const element = e.target.closest('.periodic-element, .service-element, .table-element');
      if (!element) return;

      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        activateElement(element);
      }
    });

    // Активируем первый элемент по умолчанию
    if (elements[0]) activateElement(elements[0]);
  }

  /* ==========================================================================
     17. ФОРМА КОНСУЛЬТАЦИИ (мульти-выбор услуг)
     ========================================================================== */

  function initConsultationForm() {
    const form = qs('.consultation-form, .consult-form');
    if (!form) return;

    // Мульти-выбор услуг (toggle-кнопки)
    const serviceOptions = qsa('.service-option, .consult-service-btn', form);
    serviceOptions.forEach((option) => {
      option.addEventListener('click', () => {
        option.classList.toggle('selected');
        option.classList.toggle('active');

        // Обновляем скрытое поле формы
        const selectedServices = qsa('.service-option.selected, .consult-service-btn.active', form)
          .map((opt) => opt.dataset.service || opt.textContent.trim());

        const hiddenInput = qs('input[name="services"], input[name="selected_services"]', form);
        if (hiddenInput) {
          hiddenInput.value = selectedServices.join(', ');
        }
      });
    });

    // Отправка формы обрабатывается в общем обработчике (п.25)
  }

  /* ==========================================================================
     18. КВИЗ-ДВИЖОК (7 шагов)
     ========================================================================== */

  function initQuizEngine() {
    const quiz = qs('.quiz, .quiz-section, .quiz-container');
    if (!quiz) return;

    const steps = qsa('.quiz-step', quiz);
    const progressBar = qs('.quiz-progress-bar, .quiz-progress__fill', quiz);
    const progressText = qs('.quiz-progress-text, .quiz-step-counter', quiz);
    const prevBtn = qs('.quiz-prev, .quiz-back', quiz);
    const nextBtn = qs('.quiz-next, .quiz-forward', quiz);
    const submitBtn = qs('.quiz-submit', quiz);

    const TOTAL_STEPS = steps.length || 7;
    let currentStep = 0;
    const answers = {};

    function updateProgress() {
      const percent = ((currentStep + 1) / TOTAL_STEPS) * 100;
      if (progressBar) progressBar.style.width = percent + '%';
      if (progressText) progressText.textContent = `Шаг ${currentStep + 1} из ${TOTAL_STEPS}`;
    }

    function showStep(index) {
      if (index < 0 || index >= TOTAL_STEPS) return;
      currentStep = index;

      steps.forEach((step, i) => {
        step.classList.toggle('active', i === currentStep);
        step.style.display = i === currentStep ? '' : 'none';
      });

      // Управление кнопками навигации
      if (prevBtn) prevBtn.style.display = currentStep === 0 ? 'none' : '';
      if (nextBtn) {
        // На последнем шаге (контактная форма) — скрываем кнопку «Далее»
        const isLastStep = currentStep === TOTAL_STEPS - 1;
        nextBtn.style.display = isLastStep ? 'none' : '';
      }
      if (submitBtn) {
        const isContactStep = currentStep === TOTAL_STEPS - 1;
        submitBtn.style.display = isContactStep ? '' : 'none';
      }

      updateProgress();
    }

    function goNext() {
      if (currentStep < TOTAL_STEPS - 1) showStep(currentStep + 1);
    }

    function goPrev() {
      if (currentStep > 0) showStep(currentStep - 1);
    }

    function saveAnswer(stepIndex, value) {
      answers[stepIndex] = value;
    }

    // Кнопки навигации
    if (prevBtn) prevBtn.addEventListener('click', goPrev);
    if (nextBtn) nextBtn.addEventListener('click', goNext);

    // Клик по варианту ответа
    quiz.addEventListener('click', (e) => {
      const option = e.target.closest('.quiz-option, .quiz-answer');
      if (!option) return;

      const step = option.closest('.quiz-step');
      if (!step) return;

      const stepIndex = steps.indexOf(step);
      // Тип выбора: single (по умолчанию) или multi
      const isSingleSelect =
        step.dataset.type === 'single' ||
        !step.dataset.type ||
        step.classList.contains('quiz-step--single');

      if (isSingleSelect) {
        // Одиночный выбор — снимаем предыдущий
        qsa('.quiz-option.selected, .quiz-answer.selected', step).forEach((opt) => {
          opt.classList.remove('selected');
        });
      }

      option.classList.toggle('selected');

      // Сохраняем ответ
      const selectedOptions = qsa('.quiz-option.selected, .quiz-answer.selected', step)
        .map((opt) => opt.dataset.value || opt.textContent.trim());
      saveAnswer(stepIndex, selectedOptions);

      // Авто-переход к следующему шагу при одиночном выборе (с задержкой для визуального отклика)
      if (isSingleSelect && option.classList.contains('selected')) {
        setTimeout(goNext, 350);
      }
    });

    // Валидация телефона на контактном шаге
    function validateContactStep() {
      const contactStep = steps[TOTAL_STEPS - 1];
      if (!contactStep) return true;

      const phoneInput = qs('input[type="tel"], input.phone-input', contactStep);
      if (!phoneInput) return true;

      if (window.stroymskPhoneMask && !window.stroymskPhoneMask.isPhoneComplete(phoneInput.value)) {
        phoneInput.classList.add('error', 'invalid');
        const errorMsg = qs('.phone-error, .field-error', phoneInput.parentElement);
        if (errorMsg) errorMsg.textContent = 'Введите корректный номер телефона';
        phoneInput.focus();
        return false;
      }

      phoneInput.classList.remove('error', 'invalid');
      return true;
    }

    /**
     * Конфетти при успешной отправке квиза
     */
    function showConfetti() {
      const canvas = document.createElement('canvas');
      canvas.className = 'confetti-canvas';
      canvas.style.cssText =
        'position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:10000;';
      document.body.appendChild(canvas);

      const ctx = canvas.getContext('2d');
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;

      const particles = [];
      const colors = ['#FFD700', '#FF6B35', '#4ECDC4', '#44A8B3', '#2E86AB', '#A23B72'];
      const PARTICLE_COUNT = 150;

      for (let i = 0; i < PARTICLE_COUNT; i++) {
        particles.push({
          x: Math.random() * canvas.width,
          y: Math.random() * canvas.height - canvas.height,
          vx: (Math.random() - 0.5) * 6,
          vy: Math.random() * 4 + 2,
          size: Math.random() * 8 + 3,
          color: colors[Math.floor(Math.random() * colors.length)],
          rotation: Math.random() * 360,
          rotationSpeed: (Math.random() - 0.5) * 10,
          opacity: 1,
        });
      }

      let frame = 0;
      const MAX_FRAMES = 180; // ~3 секунды при 60fps

      function animate() {
        frame++;
        if (frame > MAX_FRAMES) {
          canvas.remove();
          return;
        }

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        particles.forEach((p) => {
          p.x += p.vx;
          p.y += p.vy;
          p.vy += 0.1; // гравитация
          p.rotation += p.rotationSpeed;
          p.opacity = Math.max(0, 1 - frame / MAX_FRAMES);

          ctx.save();
          ctx.translate(p.x, p.y);
          ctx.rotate((p.rotation * Math.PI) / 180);
          ctx.globalAlpha = p.opacity;
          ctx.fillStyle = p.color;
          ctx.fillRect(-p.size / 2, -p.size / 2, p.size, p.size / 2);
          ctx.restore();
        });

        requestAnimationFrame(animate);
      }

      animate();
    }

    // Показ состояния успеха
    function showSuccess() {
      showConfetti();

      const successState = qs('.quiz-success, .quiz-result', quiz);
      if (successState) {
        steps.forEach((s) => (s.style.display = 'none'));
        successState.classList.add('active');
        successState.style.display = '';
      }

      // Скрываем навигацию
      if (prevBtn) prevBtn.style.display = 'none';
      if (nextBtn) nextBtn.style.display = 'none';
      if (submitBtn) submitBtn.style.display = 'none';
      if (progressBar) progressBar.style.width = '100%';
    }

    // Отправка квиза
    if (submitBtn) {
      submitBtn.addEventListener('click', async (e) => {
        e.preventDefault();

        if (!validateContactStep()) return;

        submitBtn.disabled = true;
        submitBtn.textContent = 'Отправка...';

        // Собираем все данные
        const formData = new FormData();
        formData.append('action', 'stroymsk_quiz');

        // Ответы на каждый шаг
        Object.entries(answers).forEach(([step, value]) => {
          formData.append(`step_${step}`, Array.isArray(value) ? value.join(', ') : value);
        });

        // Контактные данные с последнего шага
        const contactStep = steps[TOTAL_STEPS - 1];
        if (contactStep) {
          qsa('input, textarea, select', contactStep).forEach((input) => {
            if (input.name) formData.append(input.name, input.value);
          });
        }

        try {
          const ajaxUrl =
            (typeof stroymskData !== 'undefined' && stroymskData.ajaxUrl) ||
            '/wp-admin/admin-post.php';

          const response = await fetch(ajaxUrl, {
            method: 'POST',
            body: formData,
          });

          if (response.ok) {
            showSuccess();
          } else {
            throw new Error('Ошибка сервера');
          }
        } catch (error) {
          console.error('Ошибка отправки квиза:', error);
          submitBtn.disabled = false;
          submitBtn.textContent = 'Попробовать снова';

          const errorEl = qs('.quiz-error', quiz);
          if (errorEl) {
            errorEl.textContent = 'Произошла ошибка. Попробуйте ещё раз.';
            errorEl.style.display = '';
          }
        }
      });
    }

    // Инициализация — показываем первый шаг
    showStep(0);

    // Экспортируем для внешнего использования
    window.stroymskQuiz = {
      goNext,
      goPrev,
      showStep,
      getAnswers: () => ({ ...answers }),
      getCurrentStep: () => currentStep,
    };
  }

  /* ==========================================================================
     19. ФИНАЛЬНАЯ CTA-ФОРМА
     ========================================================================== */

  function initFinalCtaForm() {
    const section = qs('.final-cta, .cta-section, .bottom-cta');
    if (!section) return;

    const form = qs('form', section);
    if (!form) return;

    const miniQuizToggle = qs('.mini-quiz-toggle, .cta-quiz-toggle', section);
    const miniQuizContent = qs('.mini-quiz-content, .cta-quiz-body', section);
    const progressBar = qs('.cta-progress, .mini-quiz-progress__fill', section);

    // Мини-квиз toggle (раскрытие/скрытие)
    if (miniQuizToggle && miniQuizContent) {
      miniQuizToggle.addEventListener('click', () => {
        const isOpen = miniQuizContent.classList.contains('active');
        miniQuizContent.classList.toggle('active');
        miniQuizContent.style.display = isOpen ? 'none' : '';
        miniQuizToggle.classList.toggle('active');
      });
    }

    // Прогресс мини-квиза
    if (miniQuizContent && progressBar) {
      const totalQuestions =
        qsa('.mini-quiz-question, .cta-quiz-question', miniQuizContent).length || 1;

      miniQuizContent.addEventListener('click', (e) => {
        const option = e.target.closest('.mini-quiz-option, .cta-quiz-option');
        if (!option) return;

        const question = option.closest('.mini-quiz-question, .cta-quiz-question');
        if (!question) return;

        // Снимаем выбор с остальных в той же группе
        qsa('.mini-quiz-option.selected, .cta-quiz-option.selected', question).forEach((o) =>
          o.classList.remove('selected')
        );
        option.classList.add('selected');

        // Считаем количество отвеченных вопросов
        const answered = qsa('.mini-quiz-question, .cta-quiz-question', miniQuizContent).filter(
          (q) => qs('.selected', q)
        ).length;

        const percent = (answered / totalQuestions) * 100;
        progressBar.style.width = percent + '%';
      });
    }

    // Валидация и отправка обрабатываются в общем обработчике форм (п.25)
  }

  /* ==========================================================================
     20. АНИМАЦИЯ СЧЁТЧИКОВ
     ========================================================================== */

  function initCounterAnimation() {
    const counters = qsa('.counter, .stat-number, [data-counter]');
    if (!counters.length) return;

    function animateCounter(element) {
      if (element.dataset.animated) return; // Уже анимирован
      element.dataset.animated = 'true';

      const text = element.dataset.counter || element.dataset.target || element.textContent;
      const hasPlus = text.includes('+');
      const suffix = text.replace(/[\d.,+\s]/g, '').trim();
      const numericValue = parseFloat(text.replace(/[^\d.,]/g, '').replace(',', '.'));

      if (isNaN(numericValue)) return;

      const duration = 2000; // 2 секунды
      const startTime = performance.now();

      function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        // easeOutExpo для эффектного замедления в конце
        const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
        const current = Math.floor(numericValue * eased);

        let display = current.toLocaleString('ru-RU');
        if (hasPlus) display += '+';
        if (suffix) display += suffix;

        element.textContent = display;

        if (progress < 1) {
          requestAnimationFrame(update);
        } else {
          // Финальное значение — оригинальный текст (точное значение)
          element.textContent = text;
        }
      }

      requestAnimationFrame(update);
    }

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            animateCounter(entry.target);
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.3 }
    );

    counters.forEach((counter) => observer.observe(counter));
  }

  /* ==========================================================================
     21. ЭФФЕКТ ПРОЖЕКТОРА (Spotlight — радиальный градиент за мышью)
     ========================================================================== */

  function initSpotlightEffect() {
    const sections = qsa('.spotlight-section, [data-spotlight]');
    if (!sections.length) return;

    sections.forEach((section) => {
      section.addEventListener('mousemove', throttle((e) => {
        const rect = section.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        section.style.setProperty('--spotlight-x', x + 'px');
        section.style.setProperty('--spotlight-y', y + 'px');

        // Инициализируем при первом движении
        if (!section.dataset.spotlightInit) {
          section.dataset.spotlightInit = 'true';
          section.style.setProperty('--spotlight-opacity', '1');
        }
      }, 16)); // ~60fps

      section.addEventListener('mouseleave', () => {
        section.style.setProperty('--spotlight-opacity', '0');
      });

      section.addEventListener('mouseenter', () => {
        section.style.setProperty('--spotlight-opacity', '1');
      });
    });
  }

  /* ==========================================================================
     22. ЛЕНИВАЯ ЗАГРУЗКА ИЗОБРАЖЕНИЙ
     ========================================================================== */

  function initLazyImages() {
    const lazyImages = qsa('img[data-src], [data-bg]');
    if (!lazyImages.length) return;

    const lazyObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;

          const el = entry.target;

          if (el.dataset.src) {
            // Обычное изображение — подставляем src
            el.src = el.dataset.src;
            if (el.dataset.srcset) {
              el.srcset = el.dataset.srcset;
            }
            el.removeAttribute('data-src');
            el.removeAttribute('data-srcset');

            // Плавное появление после загрузки
            el.classList.add('loaded');
            el.addEventListener('load', () => {
              el.classList.add('lazy-loaded');
            }, { once: true });

            el.addEventListener('error', () => {
              el.classList.add('lazy-error');
              console.warn('Ошибка загрузки изображения:', el.src);
            }, { once: true });
          }

          if (el.dataset.bg) {
            // Фоновое изображение
            el.style.backgroundImage = `url('${el.dataset.bg}')`;
            el.removeAttribute('data-bg');
            el.classList.add('bg-loaded');
          }

          lazyObserver.unobserve(el);
        });
      },
      {
        rootMargin: '200px 0px', // Предзагрузка за 200px до появления во вьюпорте
        threshold: 0.01,
      }
    );

    // Проверяем поддержку нативной ленивой загрузки
    const supportsNativeLazy = 'loading' in HTMLImageElement.prototype;

    lazyImages.forEach((el) => {
      if (supportsNativeLazy && el.tagName === 'IMG' && el.dataset.src && !el.dataset.bg) {
        // Нативная ленивая загрузка (быстрее)
        el.src = el.dataset.src;
        if (el.dataset.srcset) el.srcset = el.dataset.srcset;
        el.loading = 'lazy';
        el.removeAttribute('data-src');
        el.removeAttribute('data-srcset');
        el.classList.add('loaded');
      } else {
        // IntersectionObserver для фоновых изображений и fallback
        lazyObserver.observe(el);
      }
    });
  }

  /* ==========================================================================
     23. ПАУЗА ОРБИТАЛЬНЫХ АНИМАЦИЙ ВНЕ ВИДИМОСТИ
     ========================================================================== */

  function initOrbitPause() {
    const orbitSections = qsa('.orbit-section, .orbit-animation, [data-orbit]');
    if (!orbitSections.length) return;

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          const state = entry.isIntersecting ? 'running' : 'paused';

          // Устанавливаем animationPlayState для всех анимированных дочерних элементов
          const animatedElements = qsa(
            '.orbit-item, .orbit-planet, .orbit-ring, [class*="orbit"]',
            entry.target
          );
          animatedElements.forEach((el) => {
            el.style.animationPlayState = state;
          });

          // Также для самого контейнера
          entry.target.style.animationPlayState = state;
        });
      },
      { threshold: 0.05 }
    );

    orbitSections.forEach((section) => observer.observe(section));
  }

  /* ==========================================================================
     24. КАТАЛОГ ПРОЕКТОВ (фильтрация + попап с галереей и планировками)
     ========================================================================== */

  function initProjectCatalog() {
    const catalog = qs('.project-catalog, .projects-catalog, .catalog-section');
    if (!catalog) return;

    const filterBtns = qsa('.catalog-filter, .filter-btn, [data-filter]', catalog);
    const projectItems = qsa('.catalog-item, .project-item, .catalog-card', catalog);

    /* --- Фильтрация по категориям --- */
    if (filterBtns.length && projectItems.length) {
      filterBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
          const filter = btn.dataset.filter || btn.dataset.category || 'all';

          // Обновляем активную кнопку фильтра
          filterBtns.forEach((b) => b.classList.remove('active'));
          btn.classList.add('active');

          // Фильтруем проекты с анимацией
          projectItems.forEach((item) => {
            const category = item.dataset.category || item.dataset.filter || '';
            const shouldShow =
              filter === 'all' || category === filter || category.includes(filter);

            if (shouldShow) {
              item.style.display = '';
              item.classList.remove('hidden');
              requestAnimationFrame(() => item.classList.add('visible'));
            } else {
              item.classList.remove('visible');
              item.classList.add('hidden');
              // Скрываем после анимации исчезновения
              setTimeout(() => {
                if (item.classList.contains('hidden')) item.style.display = 'none';
              }, 300);
            }
          });
        });
      });
    }

    /* --- Попап проекта с галереей и планировками --- */
    let projectPopup = qs('.project-popup, .catalog-popup');
    if (!projectPopup) {
      projectPopup = document.createElement('div');
      projectPopup.className = 'project-popup';
      projectPopup.setAttribute('role', 'dialog');
      projectPopup.setAttribute('aria-modal', 'true');
      projectPopup.innerHTML = `
        <div class="project-popup__overlay"></div>
        <div class="project-popup__content">
          <button class="project-popup__close" aria-label="Закрыть">&times;</button>
          <div class="project-popup__gallery">
            <button class="project-popup__prev" aria-label="Предыдущее фото">&#8249;</button>
            <img class="project-popup__image" src="" alt="" />
            <button class="project-popup__next" aria-label="Следующее фото">&#8250;</button>
            <div class="project-popup__gallery-dots"></div>
          </div>
          <div class="project-popup__info">
            <h3 class="project-popup__title"></h3>
            <div class="project-popup__description"></div>
            <div class="project-popup__floor-plans">
              <h4>Планировки</h4>
              <div class="project-popup__plans-grid"></div>
            </div>
            <div class="project-popup__specs"></div>
          </div>
        </div>
      `;
      document.body.appendChild(projectPopup);
    }

    const popupImage = qs('.project-popup__image', projectPopup);
    const popupTitle = qs('.project-popup__title', projectPopup);
    const popupDesc = qs('.project-popup__description', projectPopup);
    const popupPlansGrid = qs('.project-popup__plans-grid', projectPopup);
    const popupSpecs = qs('.project-popup__specs', projectPopup);
    const popupDots = qs('.project-popup__gallery-dots', projectPopup);
    const popupPrev = qs('.project-popup__prev', projectPopup);
    const popupNext = qs('.project-popup__next', projectPopup);
    const popupClose = qs('.project-popup__close', projectPopup);
    const popupOverlay = qs('.project-popup__overlay', projectPopup);

    let galleryImages = [];
    let currentGalleryIndex = 0;

    function showGalleryImage(index) {
      if (!galleryImages.length) return;
      currentGalleryIndex =
        ((index % galleryImages.length) + galleryImages.length) % galleryImages.length;

      if (popupImage) {
        popupImage.src = galleryImages[currentGalleryIndex];
      }

      // Обновляем точки-индикаторы
      if (popupDots) {
        qsa('.gallery-dot', popupDots).forEach((dot, i) => {
          dot.classList.toggle('active', i === currentGalleryIndex);
        });
      }
    }

    function openProjectPopup(projectEl) {
      // Извлекаем данные из data-атрибутов элемента
      const title =
        projectEl.dataset.title ||
        qs('.project-title, .catalog-card__title', projectEl)?.textContent ||
        '';
      const description =
        projectEl.dataset.description ||
        qs('.project-desc, .catalog-card__desc', projectEl)?.textContent ||
        '';
      const specs = projectEl.dataset.specs || '';

      // Массив изображений галереи
      try {
        galleryImages = JSON.parse(projectEl.dataset.gallery || '[]');
      } catch {
        const mainImage = projectEl.dataset.image || qs('img', projectEl)?.src;
        galleryImages = mainImage ? [mainImage] : [];
      }

      // Планировки этажей
      let floorPlans = [];
      try {
        floorPlans = JSON.parse(projectEl.dataset.floorPlans || '[]');
      } catch {
        floorPlans = [];
      }

      // Заполняем попап данными
      if (popupTitle) popupTitle.textContent = title;
      if (popupDesc) popupDesc.innerHTML = description;
      if (popupSpecs) popupSpecs.innerHTML = specs;

      // Генерируем точки галереи
      if (popupDots) {
        popupDots.innerHTML = galleryImages
          .map(
            (_, i) =>
              `<button class="gallery-dot${i === 0 ? ' active' : ''}" data-index="${i}" aria-label="Фото ${i + 1}"></button>`
          )
          .join('');
      }

      // Генерируем сетку планировок
      if (popupPlansGrid) {
        if (floorPlans.length) {
          popupPlansGrid.innerHTML = floorPlans
            .map(
              (plan) => `<div class="floor-plan-item">
                <img src="${plan.image || plan}" alt="${plan.title || 'Планировка'}" loading="lazy" />
                ${plan.title ? `<span>${plan.title}</span>` : ''}
              </div>`
            )
            .join('');
          popupPlansGrid.parentElement.style.display = '';
        } else {
          popupPlansGrid.parentElement.style.display = 'none';
        }
      }

      showGalleryImage(0);

      projectPopup.classList.add('active', 'open');
      document.body.classList.add('no-scroll');
      if (popupClose) popupClose.focus();
    }

    function closeProjectPopup() {
      projectPopup.classList.remove('active', 'open');
      document.body.classList.remove('no-scroll');
      galleryImages = [];
      currentGalleryIndex = 0;
    }

    // Клик по элементу каталога — открытие попапа
    catalog.addEventListener('click', (e) => {
      const projectEl = e.target.closest('.catalog-item, .project-item, .catalog-card');
      if (!projectEl) return;
      // Не перехватываем клики по ссылкам и кнопкам фильтров
      if (e.target.closest('a[href], button.filter-btn, .catalog-filter, [data-filter]')) return;

      e.preventDefault();
      openProjectPopup(projectEl);
    });

    // Навигация по галерее — кнопки
    if (popupPrev) popupPrev.addEventListener('click', () => showGalleryImage(currentGalleryIndex - 1));
    if (popupNext) popupNext.addEventListener('click', () => showGalleryImage(currentGalleryIndex + 1));

    // Навигация по галерее — клик по точке
    if (popupDots) {
      popupDots.addEventListener('click', (e) => {
        const dot = e.target.closest('.gallery-dot');
        if (dot) {
          const idx = parseInt(dot.dataset.index, 10);
          if (!isNaN(idx)) showGalleryImage(idx);
        }
      });
    }

    // Навигация клавиатурой в галерее
    projectPopup.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowLeft') showGalleryImage(currentGalleryIndex - 1);
      if (e.key === 'ArrowRight') showGalleryImage(currentGalleryIndex + 1);
    });

    // Закрытие попапа
    if (popupClose) popupClose.addEventListener('click', closeProjectPopup);
    if (popupOverlay) popupOverlay.addEventListener('click', closeProjectPopup);
    onEscape(closeProjectPopup);
  }

  /* ==========================================================================
     25. ОТПРАВКА ВСЕХ ФОРМ (fetch -> admin-post.php)
     ========================================================================== */

  function initFormSubmissions() {
    // Делегирование — обрабатываем все формы (кроме квиза, у которого свой обработчик)
    document.addEventListener('submit', async (e) => {
      const form = e.target;
      if (!form.matches('form')) return;
      // Квиз обрабатывается в initQuizEngine
      if (form.closest('.quiz, .quiz-section, .quiz-container')) return;

      e.preventDefault();

      const submitBtn = qs('button[type="submit"], input[type="submit"]', form);
      const originalBtnText = submitBtn ? submitBtn.textContent : '';

      // Валидация телефона если есть
      const phoneInput = qs('input[type="tel"], input.phone-input', form);
      if (phoneInput && phoneInput.value) {
        if (
          window.stroymskPhoneMask &&
          !window.stroymskPhoneMask.isPhoneComplete(phoneInput.value)
        ) {
          phoneInput.classList.add('error', 'invalid');
          phoneInput.focus();
          const errorEl = qs('.phone-error, .field-error', phoneInput.parentElement);
          if (errorEl) errorEl.textContent = 'Введите корректный номер телефона';
          return;
        }
        phoneInput.classList.remove('error', 'invalid');
      }

      // Проверяем обязательные поля
      const requiredFields = qsa('[required]', form);
      let hasErrors = false;
      requiredFields.forEach((field) => {
        if (!field.value.trim()) {
          field.classList.add('error', 'invalid');
          hasErrors = true;
        } else {
          field.classList.remove('error', 'invalid');
        }
      });
      if (hasErrors) return;

      // Блокируем кнопку на время отправки
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Отправка...';
        submitBtn.classList.add('loading');
      }

      // Собираем данные формы
      const formData = new FormData(form);

      // Добавляем action для WordPress если не задан
      if (!formData.has('action')) {
        const formAction = form.dataset.action || 'stroymsk_form';
        formData.append('action', formAction);
      }

      // Добавляем выбранные услуги если есть (из мульти-селекта п.17)
      const selectedServices = qsa('.service-option.selected, .consult-service-btn.active', form);
      if (selectedServices.length) {
        formData.append(
          'selected_services',
          selectedServices.map((s) => s.dataset.service || s.textContent.trim()).join(', ')
        );
      }

      try {
        const ajaxUrl =
          form.action ||
          (typeof stroymskData !== 'undefined' && stroymskData.ajaxUrl) ||
          '/wp-admin/admin-post.php';

        const response = await fetch(ajaxUrl, {
          method: 'POST',
          body: formData,
        });

        if (response.ok) {
          // Успешная отправка
          form.classList.add('form-success');

          // Показываем сообщение успеха
          let successMsg = qs('.form-success-message, .success-message', form.parentElement);
          if (!successMsg) {
            successMsg = document.createElement('div');
            successMsg.className = 'form-success-message';
            successMsg.innerHTML = `
              <div class="success-icon">&#10003;</div>
              <p class="success-text">Спасибо! Мы свяжемся с вами в ближайшее время.</p>
            `;
            form.parentElement.insertBefore(successMsg, form.nextSibling);
          }
          successMsg.classList.add('active', 'visible');
          successMsg.style.display = '';

          // Скрываем форму
          form.style.display = 'none';

          // Сбрасываем форму
          form.reset();

          // Событие для аналитики (Яндекс Метрика и т.д.)
          document.dispatchEvent(
            new CustomEvent('stroymsk:formSuccess', {
              detail: {
                formId: form.id || form.dataset.formId || 'unknown',
                action: formData.get('action'),
              },
            })
          );
        } else {
          throw new Error(`Сервер ответил с кодом ${response.status}`);
        }
      } catch (error) {
        console.error('Ошибка отправки формы:', error);

        // Показываем сообщение об ошибке
        let errorMsg = qs('.form-error-message, .error-message', form);
        if (!errorMsg) {
          errorMsg = document.createElement('div');
          errorMsg.className = 'form-error-message';
          form.appendChild(errorMsg);
        }
        errorMsg.textContent = 'Произошла ошибка при отправке. Попробуйте ещё раз.';
        errorMsg.classList.add('active', 'visible');
        errorMsg.style.display = '';

        // Скрываем ошибку через 5 секунд
        setTimeout(() => {
          errorMsg.classList.remove('active', 'visible');
          errorMsg.style.display = 'none';
        }, 5000);
      } finally {
        // Разблокируем кнопку
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.textContent = originalBtnText;
          submitBtn.classList.remove('loading');
        }
      }
    });
  }

  /* ==========================================================================
     ИНИЦИАЛИЗАЦИЯ ВСЕХ МОДУЛЕЙ
     ========================================================================== */

  // Порядок важен — некоторые модули экспортируют API для последующих
  initHeaderScroll();          // 1.  Шапка при скролле
  initMobileMenu();            // 2.  Мобильное меню
  initSmoothScroll();          // 3.  Плавная прокрутка
  initPhoneMask();             // 4.  Маска телефона
  initScrollReveal();          // 5.  Анимация появления
  initFaqAccordion();          // 6.  FAQ аккордеон
  initHeroSlideshow();         // 7.  Слайдшоу героя
  initProjectCardSlider();     // 8.  Слайдер карточек проектов
  initMarqueePause();          // 9.  Пауза бегущих строк
  initCasesLightbox();         // 11. Лайтбокс кейсов
  initVideoPopup();            // 12. Видео попап
  initCabinetTabs();           // 13. Вкладки кабинета
  initCabinetDemoPopup();      // 14. Демо-попап кабинета
  initTeamCarousel();          // 15. Карусель команды
  initServicePeriodicTable();  // 16. Периодическая таблица услуг
  initConsultationForm();      // 17. Форма консультации
  initQuizEngine();            // 18. Квиз-движок
  initFinalCtaForm();          // 19. Финальная CTA-форма
  initCounterAnimation();      // 20. Анимация счётчиков
  initSpotlightEffect();       // 21. Эффект прожектора
  initLazyImages();            // 22. Ленивая загрузка
  initOrbitPause();            // 23. Пауза орбитальных анимаций
  initProjectCatalog();        // 24. Каталог проектов
  initFormSubmissions();       // 25. Отправка форм

  // Логируем успешную инициализацию
  console.log('[СтройМСК] Все модули инициализированы.');
});
