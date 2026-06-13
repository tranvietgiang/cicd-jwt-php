<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e(config('app.name')) ?></title>
    <script>window.APP_BASE = '<?= e(base_url('')) ?>'.replace(/\/$/, '');</script>
    <link rel="stylesheet" href="<?= e(base_url('assets/css/app.css')) ?>">
</head>
<body>
    <main class="shell">
        <section class="panel">
            <h1><?= e(config('app.name')) ?></h1>
            <p>Bo khung MVC PHP thuan voi REST API, JWT, CSRF, XSS escaping, PDO prepared statements va AJAX mau.</p>

            <form id="login-form" autocomplete="off">
                <label>
                    Email
                    <input name="email" type="email" required>
                </label>
                <label>
                    Mat khau
                    <input name="password" type="password" required>
                </label>
                <button type="submit">Dang nhap AJAX</button>
            </form>

            <pre id="result">San sang goi API.</pre>
        </section>
    </main>
    <script src="<?= e(base_url('assets/js/app.js')) ?>"></script>
</body>
</html>
