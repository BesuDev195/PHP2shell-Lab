/**
 * CTC Bank — Main Application Scripts
 * Mobile navigation, table search, filtering, and copy utilities.
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('appSidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('open');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (sidebar.classList.contains('open') && !sidebar.contains(e.target) && e.target !== sidebarToggle) {
                sidebar.classList.remove('open');
            }
        });
    }

    // 2. Table Search and Filtering
    const searchInputs = document.querySelectorAll('[data-table-search]');
    searchInputs.forEach(input => {
        const targetTableSelector = input.getAttribute('data-table-search');
        const targetTable = document.querySelector(targetTableSelector);
        if (!targetTable) return;

        input.addEventListener('input', () => {
            const query = input.value.toLowerCase().trim();
            const rows = targetTable.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // 3. Status Filters for Tables
    const filterSelects = document.querySelectorAll('[data-table-filter]');
    filterSelects.forEach(select => {
        const targetTableSelector = select.getAttribute('data-table-filter');
        const columnIdx = parseInt(select.getAttribute('data-filter-column') || '0', 10);
        const targetTable = document.querySelector(targetTableSelector);
        if (!targetTable) return;

        select.addEventListener('change', () => {
            const filterValue = select.value.toLowerCase().trim();
            const rows = targetTable.querySelectorAll('tbody tr');

            rows.forEach(row => {
                if (!filterValue || filterValue === 'all') {
                    row.style.display = '';
                    return;
                }
                const cells = row.querySelectorAll('td');
                const cellText = (cells[columnIdx]?.textContent || '').toLowerCase();
                if (cellText.includes(filterValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // 4. Copy to Clipboard utility
    window.copyToClipboard = function(text, btnElement) {
        if (!navigator.clipboard) {
            // Fallback
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showCopyFeedback(btnElement);
            return;
        }

        navigator.clipboard.writeText(text).then(() => {
            showCopyFeedback(btnElement);
        }).catch(err => {
            console.error('Copy failed: ', err);
        });
    };

    function showCopyFeedback(btn) {
        if (!btn) return;
        const origText = btn.textContent;
        btn.textContent = 'Copied!';
        setTimeout(() => {
            btn.textContent = origText;
        }, 1800);
    }
});
