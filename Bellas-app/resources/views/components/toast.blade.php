@if(session('toast'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast align-items-center {{ session('toast.type', 'text-bg-primary') }} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">{{ session('toast.message') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif

<script>
window.showToast = function(message, type = 'text-bg-primary') {
    const container = document.querySelector('.toast-container') || createToastContainer();
    const toast = createToastElement(message, type);
    container.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
};

function createToastContainer() {
    const container = document.createElement('div');
    container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
    document.body.appendChild(container);
    return container;
}

function createToastElement(message, type) {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center ${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    return toast;
}
</script>