document.addEventListener('DOMContentLoaded', function () {
    const accordionItems = document.querySelectorAll('.accordion-item');

    accordionItems.forEach(clickedItem => {
        const header = clickedItem.querySelector('.accordion-header');

        header.addEventListener('click', () => {
            // --- Логика для сворачивания других вкладок ---
            accordionItems.forEach(otherItem => {
                // Если это не тот элемент, на который мы нажали,
                // и он был открыт, то мы его закрываем.
                if (otherItem !== clickedItem && otherItem.classList.contains('active')) {
                    otherItem.classList.remove('active');
                }
            });

            // Переключаем состояние (открываем/закрываем) текущего элемента
            clickedItem.classList.toggle('active');
        });
    });
});