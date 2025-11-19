  // Sonner-like Toast Notification System
  function showToast(type, title, description, duration = 4000) {
    const container = document.getElementById('sonner-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `sonner-toast ${type}`;
    toast.style.setProperty('--toast-color', getToastColor(type));

    const icons = {
        success: '✓',
        error: '✕',
        warning: '⚠',
        info: 'ℹ'
    };

    const progressBar = duration > 0 ? `
        <div class="sonner-progress">
            <div class="sonner-progress-bar" style="animation-duration: ${duration}ms;"></div>
        </div>
    ` : '';

    toast.innerHTML = `
        <div class="sonner-icon">${icons[type] || icons.info}</div>
        <div class="sonner-content">
            <div class="sonner-title">${title}</div>
            ${description ? `<div class="sonner-description">${description}</div>` : ''}
        </div>
        <button class="sonner-close" onclick="removeToast(this)">×</button>
        ${progressBar}
    `;

    container.appendChild(toast);

    // Auto remove after duration
    if (duration > 0) {
        setTimeout(() => {
            removeToast(toast.querySelector('.sonner-close'));
        }, duration);
    }

    return toast;
}

function getToastColor(type) {
    const colors = {
        success: '#10b981',
        error: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6'
    };
    return colors[type] || colors.info;
}

function removeToast(button) {
    const toast = button.closest('.sonner-toast');
    if (toast) {
        toast.classList.add('removing');
        setTimeout(() => {
            toast.remove();
        }, 200);
    }
}

// Convenience functions
function toastSuccess(title, description, duration) {
    return showToast('success', title, description, duration);
}

function toastError(title, description, duration) {
    return showToast('error', title, description, duration);
}

function toastWarning(title, description, duration) {
    return showToast('warning', title, description, duration);
}

function toastInfo(title, description, duration) {
    return showToast('info', title, description, duration);
}
