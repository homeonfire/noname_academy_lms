document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('edit-user-modal');
    if (!modal) return;

    const closeModalBtn = modal.querySelector('.close-modal-btn');
    const editForm = document.getElementById('edit-user-form');

    // Открытие модального окна
    document.querySelectorAll('.edit-user-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.userId;

            // Загружаем данные пользователя
            fetch(`/admin/users/get/${userId}`)
                .then(response => response.json())
                .then(user => {
                    if (user.error) {
                        alert(user.error);
                        return;
                    }

                    // Заполняем все поля формы
                    document.getElementById('edit-user-id').value = user.id;
                    document.getElementById('edit-first-name').value = user.first_name || '';
                    document.getElementById('edit-last-name').value = user.last_name || '';
                    document.getElementById('edit-email').value = user.email;
                    document.getElementById('edit-role').value = user.role;
                    document.getElementById('edit-experience-level').value = user.experience_level;
                    document.getElementById('edit-preferred-skill-type').value = user.preferred_skill_type || '';

                    // Устанавливаем action для формы
                    editForm.action = `/admin/users/update/${user.id}`;

                    // Показываем модальное окно
                    modal.style.display = 'flex';
                });
        });
    });

    // Функции закрытия модального окна
    closeModalBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});

// --- ЛОГИКА ДЛЯ МОДАЛЬНОГО ОКНА КАТЕГОРИЙ ---
document.addEventListener('DOMContentLoaded', function() {
    const categoryModal = document.getElementById('edit-category-modal');
    if (!categoryModal) return;

    const closeBtn = categoryModal.querySelector('.close-modal-btn');
    const editForm = document.getElementById('edit-category-form');
    const nameInput = document.getElementById('edit-category-name');

    // Открытие модального окна
    document.querySelectorAll('.edit-category-btn').forEach(button => {
        button.addEventListener('click', function() {
            const categoryId = this.dataset.categoryId;
            const categoryName = this.dataset.categoryName;

            // Заполняем форму
            nameInput.value = categoryName;

            // Устанавливаем action для формы
            editForm.action = `/admin/categories/update/${categoryId}`;

            // Показываем модальное окно
            categoryModal.style.display = 'flex';
        });
    });

    // Функции закрытия модального окна
    function closeCategoryModal() {
        categoryModal.style.display = 'none';
    }

    closeBtn.addEventListener('click', closeCategoryModal);

    categoryModal.addEventListener('click', (event) => {
        if (event.target === categoryModal) {
            closeCategoryModal();
        }
    });
});