class NotificationManager {
    static show(message, type = 'info') {
        const container = document.getElementById('notification-container');
        const notification = document.createElement('div');
        
        // Adiciona classes e ícone baseado no tipo
        notification.className = `notification ${type}`;
        let icon = '';
        switch(type) {
            case 'success':
                icon = 'fas fa-check-circle';
                break;
            case 'error':
                icon = 'fas fa-exclamation-circle';
                break;
            default:
                icon = 'fas fa-info-circle';
        }

        notification.innerHTML = `
            <i class="${icon}"></i>
            <span>${message}</span>
        `;

        container.appendChild(notification);

        // Força um reflow para permitir a animação
        notification.offsetHeight;

        // Adiciona a classe show para iniciar a animação
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);

        // Remove a notificação após 2 segundos
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => {
                container.removeChild(notification);
            }, 500); // Tempo da animação de fade out
        }, 2000);
    }

    static success(message) {
        this.show(message, 'success');
    }

    static error(message) {
        this.show(message, 'error');
    }

    static info(message) {
        this.show(message, 'info');
    }
}