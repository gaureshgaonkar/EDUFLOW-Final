document.addEventListener('DOMContentLoaded', () => {
    // 1. Live Client-Side Table Filter Search
    const searchInput = document.getElementById('tableSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }

    // 2. Form Submission Confirmation Dialogs
    const statusForms = document.querySelectorAll('.toggle-status-form');
    statusForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!confirm('Are you sure you want to change this user\'s status?')) {
                e.preventDefault();
            }
        });
    });
});