// public/assets/websocket-client.js

class NotificationClient {
    constructor() {
        this.ws = null;
        this.userId = null;
        this.token = null;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.reconnectDelay = 1000;
        this.isConnected = false;
        this.notificationCount = 0;
        
        this.init();
    }

    init() {
        // Получаем данные пользователя из страницы
        this.userId = this.getCurrentUserId();
        this.token = this.getCurrentUserToken();
        
        if (!this.userId) {
            console.log('Пользователь не авторизован, WebSocket не подключается');
            return;
        }

        this.connect();
        this.setupEventListeners();
    }

    connect() {
        try {
            // Автоматически определяем хост для WebSocket
            const wsHost = window.location.hostname;
            const wsPort = 8080;
            const wsProto = window.location.protocol === 'https:' ? 'wss' : 'ws';
            const wsUrl = `${wsProto}://${wsHost}:${wsPort}`;
            console.log('Подключение к WebSocket:', wsUrl);
            this.ws = new WebSocket(wsUrl);
            
            this.ws.onopen = () => {
                console.log('WebSocket соединение установлено');
                this.isConnected = true;
                this.reconnectAttempts = 0;
                this.authenticate();
            };

            this.ws.onmessage = (event) => {
                this.handleMessage(event.data);
            };

            this.ws.onclose = () => {
                console.log('WebSocket соединение закрыто');
                this.isConnected = false;
                this.handleReconnect();
            };

            this.ws.onerror = (error) => {
                console.error('WebSocket ошибка:', error);
                this.isConnected = false;
            };

        } catch (error) {
            console.error('Ошибка подключения к WebSocket:', error);
            this.handleReconnect();
        }
    }

    authenticate() {
        if (this.ws && this.ws.readyState === WebSocket.OPEN) {
            this.ws.send(JSON.stringify({
                type: 'auth',
                userId: this.userId,
                token: this.token
            }));
        }
    }

    handleMessage(data) {
        try {
            const message = JSON.parse(data);
            
            switch (message.type) {
                case 'auth_success':
                    console.log('WebSocket аутентификация успешна');
                    this.updateNotificationCount();
                    break;
                    
                case 'notification':
                    this.handleNotification(message.data);
                    break;
                    
                case 'pong':
                    // Ответ на ping, можно использовать для проверки соединения
                    break;
                    
                case 'error':
                    console.error('WebSocket ошибка:', message.message);
                    break;
                    
                default:
                    console.log('Неизвестный тип сообщения:', message.type);
            }
        } catch (error) {
            console.error('Ошибка обработки сообщения:', error);
        }
    }

    handleNotification(notification) {
        console.log('Получено уведомление:', notification);
        
        // Показываем toast уведомление
        this.showToast(notification);
        
        // Обновляем счетчик уведомлений
        this.updateNotificationCount();
        
        // Добавляем в список уведомлений если он открыт
        this.addToNotificationList(notification);
        
        // Воспроизводим звук (опционально)
        this.playNotificationSound();
    }

    showToast(notification) {
        // Создаем toast элемент
        const toast = document.createElement('div');
        toast.className = 'notification-toast';
        toast.innerHTML = `
            <div class="toast-header">
                <strong>${this.escapeHtml(notification.title)}</strong>
                <button type="button" class="toast-close" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
            <div class="toast-body">
                ${this.escapeHtml(notification.message)}
            </div>
            <div class="toast-time">
                ${this.formatTime(notification.created_at)}
            </div>
        `;
        
        // Добавляем стили
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            max-width: 350px;
            animation: slideIn 0.3s ease-out;
            color: black;
        `;
        
        document.body.appendChild(toast);
        
        // Удаляем через 5 секунд
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 5000);
    }

    addToNotificationList(notification) {
        const notificationList = document.getElementById('notifications-list');
        if (notificationList) {
            // Проверяем, нет ли уже такого уведомления (по id)
            if (notificationList.querySelector(`[data-id="${notification.id}"]`)) {
                return;
            }
            const notificationItem = document.createElement('div');
            notificationItem.className = 'notification-item unread';
            notificationItem.setAttribute('data-id', notification.id);
            notificationItem.innerHTML = `
                <div class="notification-content">
                    <div class="notification-header">
                        <h4 class="notification-title">${this.escapeHtml(notification.title)}</h4>
                        <div class="notification-time">${this.formatTime(notification.created_at)}</div>
                    </div>
                    <div class="notification-message">${this.escapeHtml(notification.message)}</div>
                </div>
                <div class="notification-actions">
                    <button class="btn btn-sm btn-primary mark-read-btn" data-id="${notification.id}" title="Отметить как прочитанное">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20,6 9,17 4,12"></polyline>
                        </svg>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${notification.id}" title="Удалить">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            `;
            // Добавляем в начало списка
            notificationList.insertBefore(notificationItem, notificationList.firstChild);
            // Добавляем обработчики для новых кнопок
            this.setupNotificationActions();
        }
    }

    updateNotificationCount() {
        // Обновляем счетчик в хедере
        const countElement = document.getElementById('notification-count');
        if (countElement) {
            fetch('/notifications/get-unread-count')
                .then(response => response.json())
                .then(data => {
                    this.notificationCount = data.unreadCount;
                    countElement.textContent = this.notificationCount;
                    
                    // Показываем/скрываем счетчик
                    if (this.notificationCount > 0) {
                        countElement.style.display = 'inline';
                    } else {
                        countElement.style.display = 'none';
                    }
                })
                .catch(error => console.error('Ошибка получения счетчика:', error));
        }
    }

    handleReconnect() {
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            console.log(`Попытка переподключения ${this.reconnectAttempts}/${this.maxReconnectAttempts}`);
            
            setTimeout(() => {
                this.connect();
            }, this.reconnectDelay * this.reconnectAttempts);
        } else {
            console.error('Превышено максимальное количество попыток переподключения');
        }
    }

    setupEventListeners() {
        // Обработчик для кнопки уведомлений
        const notificationBtn = document.querySelector('.notifications-btn');
        if (notificationBtn) {
            notificationBtn.addEventListener('click', () => {
                this.toggleNotificationList();
            });
        }

        // Обработчик для отметки всех как прочитанные
        const markAllReadBtn = document.querySelector('.mark-all-read-btn');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', () => {
                this.markAllAsRead();
            });
        }

        // Ping каждые 30 секунд для поддержания соединения
        setInterval(() => {
            if (this.isConnected && this.ws) {
                this.ws.send(JSON.stringify({ type: 'ping' }));
            }
        }, 30000);
    }

    toggleNotificationList() {
        const notificationList = document.getElementById('notifications-list');
        if (notificationList) {
            const isVisible = notificationList.style.display !== 'none';
            
            if (!isVisible) {
                this.loadNotifications();
            }
            
            notificationList.style.display = isVisible ? 'none' : 'block';
        }
    }

    loadNotifications() {
        fetch('/notifications/get-notifications')
            .then(response => response.json())
            .then(data => {
                this.renderNotifications(data.notifications);
                this.notificationCount = data.unreadCount;
                this.updateNotificationCount();
            })
            .catch(error => console.error('Ошибка загрузки уведомлений:', error));
    }

    renderNotifications(notifications) {
        const notificationList = document.getElementById('notifications-list');
        if (!notificationList) return;

        notificationList.innerHTML = '';
        
        if (notifications.length === 0) {
            notificationList.innerHTML = '<div class="no-notifications">Нет уведомлений</div>';
            return;
        }

        notifications.forEach(notification => {
            const item = document.createElement('div');
            item.className = `notification-item ${notification.is_read ? '' : 'unread'}`;
            item.innerHTML = `
                <div class="notification-content">
                    <div class="notification-title">${this.escapeHtml(notification.title)}</div>
                    <div class="notification-message">${this.escapeHtml(notification.message)}</div>
                    <div class="notification-time">${notification.created_at_formatted}</div>
                </div>
                <div class="notification-actions">
                    <button class="mark-read-btn" data-id="${notification.id}">✓</button>
                    <button class="delete-btn" data-id="${notification.id}">×</button>
                </div>
            `;
            
            notificationList.appendChild(item);
        });

        // Добавляем обработчики для кнопок
        this.setupNotificationActions();
    }

    setupNotificationActions() {
        // Обработчики для отметки как прочитанное
        document.querySelectorAll('.mark-read-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const notificationId = e.target.dataset.id;
                this.markAsRead(notificationId);
            });
        });

        // Обработчики для удаления
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const notificationId = e.target.dataset.id;
                this.deleteNotification(notificationId);
            });
        });
    }

    markAsRead(notificationId) {
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
                const item = document.querySelector(`[data-id="${notificationId}"]`).closest('.notification-item');
                item.classList.remove('unread');
                this.updateNotificationCount();
            }
        })
        .catch(error => console.error('Ошибка отметки как прочитанное:', error));
    }

    markAllAsRead() {
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
                this.updateNotificationCount();
            }
        })
        .catch(error => console.error('Ошибка отметки всех как прочитанные:', error));
    }

    deleteNotification(notificationId) {
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
                const item = document.querySelector(`[data-id="${notificationId}"]`).closest('.notification-item');
                item.remove();
                this.updateNotificationCount();
            }
        })
        .catch(error => console.error('Ошибка удаления уведомления:', error));
    }

    getCurrentUserId() {
        // Получаем ID пользователя из страницы
        const userIdElement = document.querySelector('[data-user-id]');
        return userIdElement ? userIdElement.dataset.userId : null;
    }

    getCurrentUserToken() {
        // Получаем токен пользователя (можно использовать CSRF токен)
        const tokenElement = document.querySelector('meta[name="csrf-token"]');
        return tokenElement ? tokenElement.content : null;
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    formatTime(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = now - date;
        
        if (diff < 60000) { // меньше минуты
            return 'только что';
        } else if (diff < 3600000) { // меньше часа
            const minutes = Math.floor(diff / 60000);
            return `${minutes} мин. назад`;
        } else if (diff < 86400000) { // меньше дня
            const hours = Math.floor(diff / 3600000);
            return `${hours} ч. назад`;
        } else {
            return date.toLocaleDateString('ru-RU');
        }
    }

    playNotificationSound() {
        // Опционально: воспроизведение звука
        // const audio = new Audio('/public/assets/sounds/notification.mp3');
        // audio.play();
    }
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    window.notificationClient = new NotificationClient();
});

// Добавляем CSS для анимации
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .notification-toast {
        font-family: inherit;
        font-size: 14px;
    }
    
    .toast-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    
    .toast-close {
        background: none;
        border: none;
        font-size: 18px;
        cursor: pointer;
        color: #666;
    }
    
    .toast-body {
        margin-bottom: 8px;
        line-height: 1.4;
    }
    
    .toast-time {
        font-size: 12px;
        color: #666;
    }
`;
document.head.appendChild(style);
