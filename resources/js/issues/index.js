const searchInput = document.querySelector('input[name="search"]');
const filtersForm = document.getElementById('filters');
const issuesList = document.querySelector('.issues-list');

let timeout = null;

function fetchIssues() {
    const formData = new FormData(filtersForm);
    const params = new URLSearchParams(formData);

    fetch(`/issues?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.text())
    .then(html => {
        issuesList.innerHTML = html;
    });
}

// debounce search
if (searchInput) {
    searchInput.addEventListener('input', () => {
        clearTimeout(timeout);

        timeout = setTimeout(() => {
            fetchIssues();
        }, 400);
    });
}

// filter submit override
if (filtersForm) {
    filtersForm.addEventListener('submit', (e) => {
        e.preventDefault();
        fetchIssues();
    });
}