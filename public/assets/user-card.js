document.addEventListener('DOMContentLoaded', function() {
    const loadMoreButton = document.getElementById('load-more-visits');
    if (!loadMoreButton) return;

    loadMoreButton.addEventListener('click', function() {
        let currentPage = parseInt(this.dataset.currentPage);
        const totalPages = parseInt(this.dataset.totalPages);
        const userId = this.dataset.userId;

        const nextPage = currentPage + 1;

        if (nextPage > totalPages) return;

        fetch(`/admin/users/visits/${userId}?page=${nextPage}`)
            .then(response => response.json())
            .then(visits => {
                const tableBody = document.getElementById('visits-log-table').querySelector('tbody');
                visits.forEach(visit => {
                    const row = document.createElement('tr');

                    const dateCell = document.createElement('td');
                    dateCell.textContent = new Date(visit.visit_date).toLocaleString('ru-RU');

                    const ipCell = document.createElement('td');
                    ipCell.textContent = visit.ip_address;

                    const urlCell = document.createElement('td');
                    urlCell.textContent = visit.page_url;

                    row.appendChild(dateCell);
                    row.appendChild(ipCell);
                    row.appendChild(urlCell);
                    tableBody.appendChild(row);
                });

                this.dataset.currentPage = nextPage;
                if (nextPage >= totalPages) {
                    this.style.display = 'none'; // Скрываем кнопку, если все загружено
                }
            })
            .catch(error => console.error('Error loading visits:', error));
    });
});