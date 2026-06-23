function getAppData() {
    const el = document.getElementById('app-data');

    if (!el) {
        return null;
    }

    return {
        issueId: el.dataset.issueId,
        csrfToken: el.dataset.csrf,
        commentsUrl: el.dataset.commentsUrl,
        storeCommentUrl: el.dataset.storeCommentUrl,
        tagsUrl: el.dataset.tagsUrl,
        storeTagUrl: el.dataset.storeTagUrl,
        attachTagUrl: el.dataset.attachTagUrl,
        detachTagUrlTemplate: el.dataset.detachTagUrlTemplate,
        usersUrl: el.dataset.usersUrl,
        attachMemberUrl: el.dataset.attachMemberUrl,
        detachMemberUrlTemplate: el.dataset.detachMemberUrlTemplate,
        attachedTags: JSON.parse(el.dataset.attachedTags || '[]'),
        attachedMembers: JSON.parse(el.dataset.attachedMembers || '[]')
    };
}

const state = getAppData();

if (state) {

/**
 * =========================
 * COMMENTS (PAGINATION)
 * =========================
 */

let commentPage = 1;
let hasMoreComments = true;
let isLoadingComments = false;

function loadComments(reset = true) {
    if (isLoadingComments) return;

    if (reset) {
        commentPage = 1;
        hasMoreComments = true;
    }

    if (!hasMoreComments) return;

    isLoadingComments = true;

    fetch(`${state.commentsUrl}?page=${commentPage}`)
        .then(r => r.json())
        .then(res => {
            const container = document.getElementById('comment-list');
            if (!container) return;

            const data = res.comments.data;

            if (reset) {
                container.innerHTML = '';
            }

            data.forEach(comment => {
                container.innerHTML += renderComment(comment);
            });

            hasMoreComments = !!res.comments.next_page_url;

            if (hasMoreComments) {
                commentPage++;
            }

            renderLoadMoreButton();

        })
        .catch(() => {
            const errors = document.getElementById('comment-errors');
            if (errors) errors.innerText = 'Failed to load comments';
        })
        .finally(() => {
            isLoadingComments = false;
        });
}

function renderLoadMoreButton() {
    let btn = document.getElementById('load-more-comments');

    if (!btn) {
        btn = document.createElement('button');
        btn.id = 'load-more-comments';
        btn.className = 'mt-4 px-4 py-2 bg-gray-200 rounded';
        btn.innerText = 'Load more comments';

        btn.addEventListener('click', () => loadComments(false));

        const container = document.getElementById('comment-list');
        if (container && container.parentNode) {
            container.parentNode.appendChild(btn);
        }
    }

    btn.style.display = hasMoreComments ? 'block' : 'none';
}

/**
 * COMMENT FORM
 */

function bindCommentForm() {
    const form = document.getElementById('comment-form');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        try {
            const res = await fetch(state.storeCommentUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': state.csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await res.json();

            if (!res.ok) {
                document.getElementById('comment-errors').innerText =
                    Object.values(data.errors || {}).flat().join(', ');
                return;
            }

            const container = document.getElementById('comment-list');
            container.innerHTML =
                renderComment(data.comment) + container.innerHTML;

            form.reset();

        } catch {
            document.getElementById('comment-errors').innerText = 'Failed to submit comment';
        }
    });
}

function renderComment(comment) {
    return `
        <div class="border p-2 rounded">
            <div class="font-semibold text-sm">${escapeHtml(comment.author_name)}</div>
            <div class="text-sm text-gray-700">${escapeHtml(comment.body)}</div>
        </div>
    `;
}

/**
 * =========================
 * TAGS
 * =========================
 */

function openTagModal() {
    const modal = document.getElementById('tag-modal');
    if (modal) modal.classList.remove('hidden');

    loadTags();
}

function loadTags() {
    return fetch(state.tagsUrl, { headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(data => renderTagModal(data.tags || []))
        .catch(() => showTagErrors(['Failed to load tags']));
}

function renderTagModal(tags) {
    const container = document.getElementById('tag-modal-list');
    if (!container) return;

    container.innerHTML = '';

    tags.forEach(tag => {
        const isAttached = state.attachedTags.includes(tag.id);

        container.innerHTML += `
            <div class="flex justify-between border p-2 rounded">
                <span data-tag-id="${tag.id}">${escapeHtml(tag.name)}</span>

                <button onclick="toggleTag(${tag.id}, ${isAttached ? 1 : 0})"
                    class="text-blue-600">
                    ${isAttached ? 'Detach' : 'Attach'}
                </button>
            </div>
        `;
    });
}

function closeTagModal() {
    const modal = document.getElementById('tag-modal');
    if (modal) modal.classList.add('hidden');
}

function toggleTag(tagId, isAttached) {
    isAttached = Boolean(isAttached);

    const url = isAttached
        ? state.detachTagUrlTemplate.replace('__TAG__', tagId)
        : state.attachTagUrl;

    const options = {
        method: isAttached ? 'DELETE' : 'POST',
        headers: {
            'X-CSRF-TOKEN': state.csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    };

    if (!isAttached) {
        options.body = JSON.stringify({ tag_id: tagId });
    }

    fetch(url, options)
        .then(r => r.json())
        .then(() => {
            const tagList = document.getElementById('tag-list');
            if (!tagList) return;

            if (isAttached) {
                const el = tagList.querySelector(`[data-tag-id="${tagId}"]`);
                if (el) el.remove();

                state.attachedTags = state.attachedTags.filter(id => id !== tagId);
            } else {
                const modalTag = document.querySelector(`[data-tag-id="${tagId}"]`);

                tagList.innerHTML += `
                    <span class="px-2 py-1 bg-gray-200 rounded" data-tag-id="${tagId}">
                        ${modalTag ? modalTag.innerText : 'Tag'}
                    </span>
                `;

                if (!state.attachedTags.includes(tagId)) {
                    state.attachedTags.push(tagId);
                }
            }
        })
        .catch(() => showTagErrors(['Tag update failed']));
}

function bindTagForm() {
    const form = document.getElementById('tag-form');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        showTagErrors([]);

        const formData = new FormData(form);

        try {
            const res = await fetch(state.storeTagUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': state.csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await res.json();

            if (!res.ok) {
                showTagErrors(Object.values(data.errors || {}).flat());
                return;
            }

            form.reset();
            await loadTags();
        } catch {
            showTagErrors(['Failed to create tag']);
        }
    });
}

function showTagErrors(errors) {
    const container = document.getElementById('tag-errors');
    if (!container) return;

    container.innerText = errors.join(', ');
}

/**
 * =========================
 * MEMBERS (BONUS FEATURE)
 * =========================
 */

function openMemberModal() {
    const modal = document.getElementById('member-modal');
    if (modal) modal.classList.remove('hidden');

    fetch(state.usersUrl)
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('member-modal-list');
            if (!container) return;

            container.innerHTML = '';

            data.users.forEach(user => {
                const isAttached = state.attachedMembers.includes(user.id);

                container.innerHTML += `
                    <div class="flex justify-between border p-2 rounded">
                        <span data-user-id="${user.id}">${user.name}</span>

                        <button onclick="toggleMember(${user.id}, ${isAttached ? 1 : 0})"
                            class="text-blue-600">
                            ${isAttached ? 'Remove' : 'Add'}
                        </button>
                    </div>
                `;
            });
        })
        .catch(() => showMemberErrors(['Failed to load users']));
}

function closeMemberModal() {
    const modal = document.getElementById('member-modal');
    if (modal) modal.classList.add('hidden');
}

function toggleMember(userId, isAttached) {
    isAttached = Boolean(isAttached);

    const url = isAttached
        ? state.detachMemberUrlTemplate.replace('__USER__', userId)
        : state.attachMemberUrl;

    const options = {
        method: isAttached ? 'DELETE' : 'POST',
        headers: {
            'X-CSRF-TOKEN': state.csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    };

    if (!isAttached) {
        options.body = JSON.stringify({ user_id: userId });
    }

    fetch(url, options)
        .then(r => r.json())
        .then(() => {
            const memberList = document.getElementById('member-list');
            if (!memberList) return;

            if (isAttached) {
                const el = memberList.querySelector(`[data-member-id="${userId}"]`);
                if (el) el.remove();

                state.attachedMembers = state.attachedMembers.filter(id => id !== userId);
            } else {
                const modalUser = document.querySelector(`[data-user-id="${userId}"]`);

                memberList.innerHTML += `
                    <span class="px-2 py-1 bg-blue-100 rounded" data-member-id="${userId}">
                        ${modalUser ? escapeHtml(modalUser.innerText) : 'Member'}
                    </span>
                `;

                if (!state.attachedMembers.includes(userId)) {
                    state.attachedMembers.push(userId);
                }
            }
        })
        .catch(() => showMemberErrors(['Member update failed']));
}

function showMemberErrors(errors) {
    const container = document.getElementById('member-errors');
    if (!container) return;

    container.innerText = errors.join(', ');
}

function bindModalControls() {
    document.getElementById('open-tag-modal')?.addEventListener('click', openTagModal);
    document.getElementById('close-tag-modal')?.addEventListener('click', closeTagModal);
    document.getElementById('open-member-modal')?.addEventListener('click', openMemberModal);
    document.getElementById('close-member-modal')?.addEventListener('click', closeMemberModal);
}

/**
 * =========================
 * SECURITY
 * =========================
 */

function escapeHtml(str) {
    return String(str)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

/**
 * =========================
 * INIT
 * =========================
 */

loadComments();
bindCommentForm();
bindTagForm();
bindModalControls();

window.openTagModal = openTagModal;
window.closeTagModal = closeTagModal;
window.toggleTag = toggleTag;

window.openMemberModal = openMemberModal;
window.closeMemberModal = closeMemberModal;
window.toggleMember = toggleMember;
}
