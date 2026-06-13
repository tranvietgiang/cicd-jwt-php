const result = document.querySelector('#result');
const loginForm = document.querySelector('#login-form');
const appBase = window.APP_BASE || '';

async function getCsrfToken() {
    const response = await fetch(`${appBase}/api/csrf-token`);
    const data = await response.json();

    return data.csrf_token;
}

async function api(path, options = {}) {
    const csrfToken = await getCsrfToken();
    const response = await fetch(path, {
        ...options,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken,
            ...(options.headers || {}),
        },
    });

    const data = await response.json();

    if (!response.ok) {
        throw data;
    }

    return data;
}

loginForm?.addEventListener('submit', async (event) => {
    event.preventDefault();

    const formData = new FormData(loginForm);

    try {
        const data = await api(`${appBase}/api/auth/login`, {
            method: 'POST',
            body: JSON.stringify(Object.fromEntries(formData.entries())),
        });

        localStorage.setItem('token', data.token);
        result.textContent = JSON.stringify(data, null, 2);
    } catch (error) {
        result.textContent = JSON.stringify(error, null, 2);
    }
});
