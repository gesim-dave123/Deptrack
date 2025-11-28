<!-- Toast Notification System - Include this in your PHP files -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* Toast Container */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-width: 400px;
    }

    /* Toast Styles */
    .toast {
        background: white;
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        gap: 15px;
        min-width: 300px;
        opacity: 0;
        transform: translateX(400px);
        animation: slideIn 0.3s ease forwards;
        position: relative;
        overflow: hidden;
    }

    .toast.hiding {
        animation: slideOut 0.3s ease forwards;
    }

    /* Toast Animations */
    @keyframes slideIn {
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideOut {
        to {
            opacity: 0;
            transform: translateX(400px);
        }
    }

    /* Toast Types */
    .toast.success {
        border-left: 5px solid #10b981;
    }

    .toast.error {
        border-left: 5px solid #ef4444;
    }

    .toast.warning {
        border-left: 5px solid #f59e0b;
    }

    .toast.info {
        border-left: 5px solid #3b82f6;
    }

    /* Toast Icon */
    .toast-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .toast.success .toast-icon {
        color: #10b981;
    }

    .toast.error .toast-icon {
        color: #ef4444;
    }

    .toast.warning .toast-icon {
        color: #f59e0b;
    }

    .toast.info .toast-icon {
        color: #3b82f6;
    }

    /* Toast Content */
    .toast-content {
        flex: 1;
    }

    .toast-title {
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 3px;
        color: #1f2937;
    }

    .toast-message {
        font-size: 0.875rem;
        color: #6b7280;
        line-height: 1.4;
    }

    /* Close Button */
    .toast-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        color: #9ca3af;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s;
    }

    .toast-close:hover {
        background: #f3f4f6;
        color: #1f2937;
    }

    /* Progress Bar */
    .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: currentColor;
        opacity: 0.3;
        animation: progress 3s linear forwards;
    }

    @keyframes progress {
        from {
            width: 100%;
        }
        to {
            width: 0%;
        }
    }

    .toast.success .toast-progress {
        background: #10b981;
    }

    .toast.error .toast-progress {
        background: #ef4444;
    }

    .toast.warning .toast-progress {
        background: #f59e0b;
    }

    .toast.info .toast-progress {
        background: #3b82f6;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .toast-container {
            right: 10px;
            left: 10px;
            top: 10px;
        }

        .toast {
            min-width: auto;
        }
    }
</style>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<script>
    // Toast notification function
    function showToast(type, title, message, duration = 3000) {
        const container = document.getElementById('toastContainer');
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        // Icon based on type
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas ${icons[type]}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="closeToast(this)">
                <i class="fas fa-times"></i>
            </button>
            <div class="toast-progress"></div>
        `;
        
        container.appendChild(toast);
        
        // Auto remove after duration
        setTimeout(() => {
            removeToast(toast);
        }, duration);
    }

    // Close toast manually
    function closeToast(button) {
        const toast = button.closest('.toast');
        removeToast(toast);
    }

    // Remove toast with animation
    function removeToast(toast) {
        toast.classList.add('hiding');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }

    // Auto-show toast from URL parameters or PHP session
    window.addEventListener('DOMContentLoaded', () => {
        // Check URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const successMsg = urlParams.get('success');
        const errorMsg = urlParams.get('error');
        
        if (successMsg) {
            showToast('success', 'Success!', decodeURIComponent(successMsg));
        }
        if (errorMsg) {
            showToast('error', 'Error!', decodeURIComponent(errorMsg));
        }
        
        <?php
        // Check PHP session for toast messages
        if (isset($_SESSION['toast'])) {
            $toast = $_SESSION['toast'];
            echo "showToast('{$toast['type']}', '{$toast['title']}', '{$toast['message']}');";
            unset($_SESSION['toast']);
        }
        ?>
    });
</script>