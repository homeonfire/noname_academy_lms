<?php
file_put_contents(__DIR__ . '/../../debug.log', "[View:notifications/index] notifications: " . json_encode($notifications) . ", user_id: " . ($_SESSION['user']['id'] ?? 'null') . ", currentPage: " . ($currentPage ?? 'null') . ", unreadCount: " . ($unreadCount ?? 'null') . "\n", FILE_APPEND);
?>
<?php include __DIR__ . '/../layouts/app-header.php'; ?>

<div class="app-layout">
    <?php include __DIR__ . '/../layouts/app-sidebar.php'; ?>

    <main class="main-content">
        <div class="content-wrapper">
            <div class="page-header">
                <h1 class="page-title">Уведомления</h1>
                <?php if ($unreadCount > 0): ?>
                    <button class="btn btn-secondary mark-all-read-btn">Отметить все как прочитанные</button>
                <?php endif; ?>
            </div>

            <div class="notifications-container">
                <?php if (empty($notifications)): ?>
                    <div class="empty-state">
                        <div class="empty-icon">🔔</div>
                        <h3>Нет уведомлений</h3>
                        <p>У вас пока нет уведомлений. Они появятся здесь, когда что-то важное произойдет.</p>
                    </div>
                <?php else: ?>
                    <div class="notifications-list" id="notifications-list">
                        <?php foreach ($notifications as $notification): ?>
                            <div class="notification-item <?= $notification['is_read'] ? '' : 'unread' ?>" data-id="<?= $notification['id'] ?>">
                                <div class="notification-content">
                                    <div class="notification-header">
                                        <h4 class="notification-title"><?= htmlspecialchars($notification['title']) ?></h4>
                                        <div class="notification-time"><?= date('d.m.Y H:i', strtotime($notification['created_at'])) ?></div>
                                    </div>
                                    <div class="notification-message"><?= htmlspecialchars($notification['message']) ?></div>
                                    
                                    <?php if (!empty($notification['data'])): ?>
                                        <?php $data = json_decode($notification['data'], true); ?>
                                        <?php if ($notification['type'] === 'new_message' && isset($data['message_preview'])): ?>
                                            <div class="notification-preview">
                                                <em>"<?= htmlspecialchars($data['message_preview']) ?>"</em>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="notification-actions">
                                    <?php if (!$notification['is_read']): ?>
                                        <button class="btn btn-sm btn-primary mark-read-btn" data-id="<?= $notification['id'] ?>" title="Отметить как прочитанное">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="20,6 9,17 4,12"></polyline>
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $notification['id'] ?>" title="Удалить">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (count($notifications) >= 20): ?>
                        <div class="load-more-container">
                            <button class="btn btn-secondary load-more-btn" data-page="<?= $currentPage + 1 ?>">
                                Загрузить еще
                            </button>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<style>
.notifications-container {
    max-width: 800px;
    margin: 0 auto;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.notifications-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.notification-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 1.5rem;
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    transition: all 0.2s ease;
}

.notification-item:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.notification-item.unread {
    border-left: 4px solid var(--primary-color);
    background: var(--primary-bg);
}

.notification-content {
    flex: 1;
    margin-right: 1rem;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.5rem;
}

.notification-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-color);
}

.notification-time {
    font-size: 0.875rem;
    color: var(--text-muted);
    white-space: nowrap;
    margin-left: 1rem;
}

.notification-message {
    color: var(--text-color);
    line-height: 1.5;
    margin-bottom: 0.5rem;
}

.notification-preview {
    font-size: 0.875rem;
    color: var(--text-muted);
    padding: 0.5rem;
    background: var(--bg-secondary);
    border-radius: 6px;
    margin-top: 0.5rem;
}

.notification-actions {
    display: flex;
    gap: 0.5rem;
    flex-shrink: 0;
}

.notification-actions .btn {
    padding: 0.5rem;
    min-width: auto;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--text-muted);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    margin: 0 0 1rem 0;
    color: var(--text-color);
}

.empty-state p {
    margin: 0;
    max-width: 400px;
    margin: 0 auto;
}

.load-more-container {
    text-align: center;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-color);
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .notification-item {
        flex-direction: column;
        gap: 1rem;
    }
    
    .notification-content {
        margin-right: 0;
    }
    
    .notification-actions {
        align-self: flex-end;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Обработчик для отметки как прочитанное
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notificationId = this.dataset.id;
            markAsRead(notificationId);
        });
    });

    // Обработчик для удаления
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notificationId = this.dataset.id;
            if (confirm('Вы уверены, что хотите удалить это уведомление?')) {
                deleteNotification(notificationId);
            }
        });
    });

    // Обработчик для "Отметить все как прочитанные"
    const markAllReadBtn = document.querySelector('.mark-all-read-btn');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function() {
            markAllAsRead();
        });
    }

    // Обработчик для "Загрузить еще"
    const loadMoreBtn = document.querySelector('.load-more-btn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const page = parseInt(this.dataset.page);
            loadMoreNotifications(page);
        });
    }
});

function markAsRead(notificationId) {
    fetch('/notifications/mark-as-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ notification_id: notificationId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const item = document.querySelector(`[data-id="${notificationId}"]`);
            item.classList.remove('unread');
            
            // Обновляем счетчик в хедере если есть
            if (window.notificationClient) {
                window.notificationClient.updateNotificationCount();
            }
        }
    })
    .catch(error => console.error('Ошибка:', error));
}

function deleteNotification(notificationId) {
    fetch('/notifications/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ notification_id: notificationId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const item = document.querySelector(`[data-id="${notificationId}"]`);
            item.remove();
            
            // Обновляем счетчик в хедере если есть
            if (window.notificationClient) {
                window.notificationClient.updateNotificationCount();
            }
            
            // Проверяем, есть ли еще уведомления
            const remainingItems = document.querySelectorAll('.notification-item');
            if (remainingItems.length === 0) {
                location.reload(); // Перезагружаем для показа пустого состояния
            }
        }
    })
    .catch(error => console.error('Ошибка:', error));
}

function markAllAsRead() {
    fetch('/notifications/mark-all-as-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.notification-item').forEach(item => {
                item.classList.remove('unread');
            });
            
            // Скрываем кнопку "Отметить все как прочитанные"
            const markAllReadBtn = document.querySelector('.mark-all-read-btn');
            if (markAllReadBtn) {
                markAllReadBtn.style.display = 'none';
            }
            
            // Обновляем счетчик в хедере если есть
            if (window.notificationClient) {
                window.notificationClient.updateNotificationCount();
            }
        }
    })
    .catch(error => console.error('Ошибка:', error));
}

function loadMoreNotifications(page) {
    const loadMoreBtn = document.querySelector('.load-more-btn');
    loadMoreBtn.textContent = 'Загрузка...';
    loadMoreBtn.disabled = true;

    fetch(`/notifications/get-notifications?page=${page}`)
        .then(response => response.json())
        .then(data => {
            const notificationsList = document.getElementById('notifications-list');
            
            data.notifications.forEach(notification => {
                const item = document.createElement('div');
                item.className = `notification-item ${notification.is_read ? '' : 'unread'}`;
                item.dataset.id = notification.id;
                item.innerHTML = `
                    <div class="notification-content">
                        <div class="notification-header">
                            <h4 class="notification-title">${escapeHtml(notification.title)}</h4>
                            <div class="notification-time">${notification.created_at_formatted}</div>
                        </div>
                        <div class="notification-message">${escapeHtml(notification.message)}</div>
                    </div>
                    <div class="notification-actions">
                        ${!notification.is_read ? `
                            <button class="btn btn-sm btn-primary mark-read-btn" data-id="${notification.id}" title="Отметить как прочитанное">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20,6 9,17 4,12"></polyline>
                                </svg>
                            </button>
                        ` : ''}
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${notification.id}" title="Удалить">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                `;
                
                notificationsList.appendChild(item);
            });

            // Обновляем кнопку "Загрузить еще"
            if (data.hasMore) {
                loadMoreBtn.dataset.page = page + 1;
                loadMoreBtn.textContent = 'Загрузить еще';
                loadMoreBtn.disabled = false;
            } else {
                loadMoreBtn.remove();
            }

            // Добавляем обработчики для новых элементов
            setupNotificationActions();
        })
        .catch(error => {
            console.error('Ошибка загрузки:', error);
            loadMoreBtn.textContent = 'Ошибка загрузки';
            loadMoreBtn.disabled = false;
        });
}

function setupNotificationActions() {
    // Обработчики для новых кнопок
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        if (!btn.hasAttribute('data-handler-attached')) {
            btn.setAttribute('data-handler-attached', 'true');
            btn.addEventListener('click', function() {
                const notificationId = this.dataset.id;
                markAsRead(notificationId);
            });
        }
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        if (!btn.hasAttribute('data-handler-attached')) {
            btn.setAttribute('data-handler-attached', 'true');
            btn.addEventListener('click', function() {
                const notificationId = this.dataset.id;
                if (confirm('Вы уверены, что хотите удалить это уведомление?')) {
                    deleteNotification(notificationId);
                }
            });
        }
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>