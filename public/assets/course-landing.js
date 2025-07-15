// ===============================================
// COURSE LANDING INTERACTIVE EFFECTS
// ===============================================

document.addEventListener('DOMContentLoaded', function() {
    
    // Анимация появления элементов при скролле
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
            }
        });
    }, observerOptions);

    // Наблюдаем за элементами для анимации
    const elementsToAnimate = document.querySelectorAll('.module-item, .benefit-item, .section-header');
    elementsToAnimate.forEach(el => {
        el.classList.add('scroll-reveal');
        observer.observe(el);
    });

    // Аккордеон для модулей (только один открыт одновременно)
    const moduleHeaders = document.querySelectorAll('.module-header');
    const moduleItems = document.querySelectorAll('.module-item');
    
    // Открываем первый модуль по умолчанию
    if (moduleItems.length > 0) {
        moduleItems[0].classList.add('active');
    }
    
    moduleHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const currentModule = this.closest('.module-item');
            
            // Закрываем все модули
            moduleItems.forEach(item => {
                item.classList.remove('active');
            });
            
            // Открываем текущий модуль
            currentModule.classList.add('active');
        });
    });

    // Плавная прокрутка к секциям
    const smoothScrollLinks = document.querySelectorAll('a[href^="#"]');
    smoothScrollLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Эффект параллакса для hero секции
    const heroSection = document.querySelector('.hero-section');
    if (heroSection) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            heroSection.style.transform = `translateY(${rate}px)`;
        });
    }

    // Анимация счетчика для уроков
    function animateCounter(element, target, duration = 2000) {
        let start = 0;
        const increment = target / (duration / 16);
        
        function updateCounter() {
            start += increment;
            if (start < target) {
                element.textContent = Math.floor(start);
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target;
            }
        }
        
        updateCounter();
    }

    // Запускаем анимацию счетчика когда элемент появляется в поле зрения
    const lessonCounter = document.querySelector('.meta-value');
    if (lessonCounter && lessonCounter.textContent.match(/^\d+$/)) {
        const lessonCount = parseInt(lessonCounter.textContent);
        lessonCounter.textContent = '0';
        
        const counterObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(lessonCounter, lessonCount);
                    counterObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        counterObserver.observe(lessonCounter);
    }

    // Эффект печатающегося текста для заголовка
    function typeWriter(element, text, speed = 100) {
        let i = 0;
        element.textContent = '';
        
        function type() {
            if (i < text.length) {
                element.textContent += text.charAt(i);
                i++;
                setTimeout(type, speed);
            }
        }
        
        type();
    }

    // Применяем эффект печатающегося текста к заголовку курса
    const courseTitle = document.querySelector('.course-title');
    if (courseTitle) {
        const originalText = courseTitle.textContent;
        courseTitle.textContent = '';
        
        const titleObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    typeWriter(courseTitle, originalText, 50);
                    titleObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        titleObserver.observe(courseTitle);
    }

    // Интерактивные карточки преимуществ
    const benefitItems = document.querySelectorAll('.benefit-item');
    benefitItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Эффект волны для кнопок
    const buttons = document.querySelectorAll('.btn-primary');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Добавляем стили для ripple эффекта
    const style = document.createElement('style');
    style.textContent = `
        .btn-primary {
            position: relative;
            overflow: hidden;
        }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }
        
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    // Анимация загрузки для изображений
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
            this.style.transform = 'scale(1)';
        });
        
        img.style.opacity = '0';
        img.style.transform = 'scale(0.95)';
        img.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    });

    // Эффект появления цены
    const priceAmount = document.querySelector('.price-amount');
    if (priceAmount) {
        const originalText = priceAmount.textContent;
        priceAmount.textContent = '0';
        
        const priceObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (originalText.includes('₽')) {
                        const price = parseInt(originalText.replace(/[^\d]/g, ''));
                        animateCounter(priceAmount, price, 1500);
                        setTimeout(() => {
                            priceAmount.textContent = originalText;
                        }, 1500);
                    } else {
                        priceAmount.textContent = originalText;
                    }
                    priceObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        priceObserver.observe(priceAmount);
    }

    // Добавляем класс для анимации при загрузке страницы
    document.body.classList.add('landing-loaded');
});

// Дополнительные утилиты
const CourseLandingUtils = {
    // Форматирование чисел
    formatNumber: function(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    },
    
    // Проверка видимости элемента
    isElementInViewport: function(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    },
    
    // Дебаунс функция
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}; 