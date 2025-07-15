document.addEventListener('DOMContentLoaded', () => {
    // Используем делегирование событий
    document.body.addEventListener('click', function(event) {
        const favoriteButton = event.target.closest('.favorite-toggle-btn');

        if (favoriteButton) {
            event.preventDefault();

            const itemId = favoriteButton.dataset.itemId;
            const itemType = favoriteButton.dataset.itemType;

            // --- НАЧАЛО ИСПРАВЛЕНИЙ ---

            // 1. Находим CSRF-токен на странице
            const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
            if (!csrfTokenElement) {
                console.error('CSRF token meta tag not found!');
                return;
            }
            const csrfToken = csrfTokenElement.content;

            // 2. Создаем данные для отправки через FormData
            const formData = new FormData();
            formData.append('item_id', itemId);
            formData.append('item_type', itemType);
            formData.append('csrf_token', csrfToken); // <-- ДОБАВЛЯЕМ ТОКЕН В ЗАПРОС

            // --- КОНЕЦ ИСПРАВЛЕНИЙ ---

            fetch('/favorite/toggle', {
                method: 'POST',
                body: formData, // Отправляем как FormData
                headers: {
                    // При отправке FormData заголовок Content-Type устанавливается автоматически
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 401) { // Ошибка авторизации
                            window.location.href = '/login';
                        }
                        throw new Error('Network response was not ok, status: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        favoriteButton.classList.toggle('active');
                    } else {
                        console.error('Error toggling favorite:', data.message);
                    }
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                });
        }
    });
});