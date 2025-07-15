<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'AI Fire LMS'; ?></title>
    <link rel="stylesheet" href="/public/assets/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <?php if (isset($_SESSION['user'])): ?>
        <meta name="csrf-token" content="<?= CSRF::getToken() ?>">
        <div data-user-id="<?= $_SESSION['user']['id'] ?>" style="display: none;"></div>
    <?php endif; ?>
    <script src="/public/assets/websocket-client.js" defer></script>
</head>
<body>