/**
 * СтройМСК — main.js
 * Мобильное меню + плавная прокрутка
 */

document.addEventListener('DOMContentLoaded', function () {
    // Мобильное меню
    var toggle = document.getElementById('menu-toggle');
    var nav = document.getElementById('main-nav');

    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            nav.classList.toggle('active');
        });
    }

    // Плавная прокрутка по якорным ссылкам
    document.querySelectorAll('a[href^="#"]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            var targetId = this.getAttribute('href');
            if (targetId === '#') return;

            var target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                var headerHeight = document.querySelector('.site-header').offsetHeight;
                var targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                window.scrollTo({ top: targetPosition, behavior: 'smooth' });

                // Закрыть мобильное меню после клика
                if (nav) nav.classList.remove('active');
            }
        });
    });

    // Маска для телефона
    document.querySelectorAll('input[type="tel"]').forEach(function (input) {
        input.addEventListener('input', function () {
            var val = this.value.replace(/\D/g, '');
            if (val.length === 0) {
                this.value = '';
                return;
            }
            if (val[0] === '7' || val[0] === '8') val = val.substring(1);
            var formatted = '+7';
            if (val.length > 0) formatted += ' (' + val.substring(0, 3);
            if (val.length >= 3) formatted += ') ' + val.substring(3, 6);
            if (val.length >= 6) formatted += '-' + val.substring(6, 8);
            if (val.length >= 8) formatted += '-' + val.substring(8, 10);
            this.value = formatted;
        });
    });
});
