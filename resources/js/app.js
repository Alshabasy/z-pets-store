// ── Scroll Animations ──
document.addEventListener('DOMContentLoaded', function () {
  const observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

  document.querySelectorAll('.animate-on-scroll').forEach(function (el) {
    observer.observe(el);
  });
});

window.initBulkSelect = function (options) {
    const tableId = options.tableId;
    const selectAllId = options.selectAllId;
    const checkboxClass = options.checkboxClass;
    const bulkBarId = options.bulkBarId;
    const countId = options.countId;
    const deleteButtonId = options.deleteButtonId;
    const bulkDestroyUrl = options.bulkDestroyUrl;
    const successRedirectUrl = options.successRedirectUrl;
    const entityName = options.entityName || 'items';

    const selectAll = document.getElementById(selectAllId);
    const checkboxes = document.querySelectorAll('.' + checkboxClass);
    const bulkBar = document.getElementById(bulkBarId);
    const countSpan = document.getElementById(countId);
    const deleteBtn = document.getElementById(deleteButtonId);

    if (!selectAll || checkboxes.length === 0 || !bulkBar) return;

    function getSelectedIds() {
        const ids = [];
        document.querySelectorAll('.' + checkboxClass + ':checked').forEach(function (cb) {
            ids.push(cb.value);
        });
        return ids;
    }

    function updateBulkBar() {
        const selectedIds = getSelectedIds();
        const count = selectedIds.length;

        if (count > 0) {
            bulkBar.classList.remove('translate-y-20', 'opacity-0');
            bulkBar.classList.add('translate-y-0', 'opacity-100');
            if (countSpan) countSpan.textContent = count;
        } else {
            bulkBar.classList.remove('translate-y-0', 'opacity-100');
            bulkBar.classList.add('translate-y-20', 'opacity-0');
        }

        // Keep Select All checkbox state in sync
        const totalCheckboxes = checkboxes.length;
        selectAll.checked = count === totalCheckboxes;
        selectAll.indeterminate = count > 0 && count < totalCheckboxes;
    }

    // Select All listener
    selectAll.addEventListener('change', function () {
        const isChecked = selectAll.checked;
        checkboxes.forEach(function (cb) {
            cb.checked = isChecked;
            const row = cb.closest('tr');
            if (row) {
                if (isChecked) row.classList.add('bg-brand-green/5');
                else row.classList.remove('bg-brand-green/5');
            }
        });
        updateBulkBar();
    });

    // Individual checkbox listeners
    checkboxes.forEach(function (cb) {
        cb.addEventListener('change', function () {
            const row = cb.closest('tr');
            if (row) {
                if (cb.checked) row.classList.add('bg-brand-green/5');
                else row.classList.remove('bg-brand-green/5');
            }
            updateBulkBar();
        });
    });

    // Delete button listener
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function () {
            const selectedIds = getSelectedIds();
            if (selectedIds.length === 0) return;

            if (!confirm(
                'Are you sure you want to delete ' + selectedIds.length + ' selected ' + entityName + '?\n\n' +
                'This action is highly destructive and cannot be undone.'
            )) return;

            deleteBtn.disabled = true;
            deleteBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Deleting...';

            fetch(bulkDestroyUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(function (r) {
                if (!r.ok) {
                    return r.json().then(function (err) { throw err; });
                }
                return r.json();
            })
            .then(function (data) {
                if (data.success) {
                    if (successRedirectUrl) {
                        window.location.href = successRedirectUrl;
                    } else {
                        // Fallback fallback: reload page
                        window.location.reload();
                    }
                } else {
                    alert(data.message || 'Failed to bulk delete items.');
                    deleteBtn.disabled = false;
                    deleteBtn.innerHTML = '<i class="fa-solid fa-trash mr-2"></i>Delete Selected';
                }
            })
            .catch(function (error) {
                console.error('Bulk delete error:', error);
                alert(error.message || 'An error occurred during bulk deletion.');
                deleteBtn.disabled = false;
                deleteBtn.innerHTML = '<i class="fa-solid fa-trash mr-2"></i>Delete Selected';
            });
        });
    }
};
