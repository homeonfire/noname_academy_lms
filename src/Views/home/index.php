<?php echo 'VIEW OK'; exit; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<style>
    .main-hero-bg {
        background: #18181b;
        color: #fff;
        padding: 64px 0 48px 0;
        text-align: center;
    }
    .main-hero-title {
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 18px;
        letter-spacing: -1px;
    }
    .main-hero-title .highlight {
        color: #a78bfa;
        font-weight: 900;
    }
    .main-hero-desc {
        color: #d1d5db;
        font-size: 1.2rem;
        margin-bottom: 32px;
    }
    .main-hero-form {
        display: flex;
        justify-content: center;
        gap: 0;
        margin-bottom: 32px;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }
    .main-hero-form input[type=email] {
        padding: 14px 18px;
        border-radius: 8px 0 0 8px;
        border: none;
        font-size: 1rem;
        width: 70%;
        outline: none;
    }
    .main-hero-form button {
        padding: 14px 24px;
        border-radius: 0 8px 8px 0;
        border: none;
        background: #fff;
        color: #18181b;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    .main-hero-form button:hover {
        background: #a78bfa;
        color: #fff;
    }
    .main-hero-logos {
        margin: 32px 0 0 0;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 32px;
        opacity: 0.7;
    }
    .main-hero-logos img {
        height: 32px;
        object-fit: contain;
        filter: grayscale(1);
    }
    .main-guides-section {
        background: #fff;
        border-radius: 24px 24px 0 0;
        margin-top: -32px;
        padding: 48px 0 64px 0;
        min-height: 60vh;
    }
    .main-guides-title {
        font-size: 2.2rem;
        font-weight: 800;
        color: #18181b;
        text-align: center;
        margin-bottom: 8px;
    }
    .main-guides-desc {
        color: #6b7280;
        text-align: center;
        margin-bottom: 32px;
    }
    .main-guides-filters {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-bottom: 32px;
    }
    .main-guides-filters button {
        background: #f3f4f6;
        color: #18181b;
        border: none;
        border-radius: 8px;
        padding: 8px 18px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .main-guides-filters button.active, .main-guides-filters button:hover {
        background: #a78bfa;
        color: #fff;
    }
    .main-guides-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 32px;
        max-width: 1100px;
        margin: 0 auto;
    }
    .main-guide-card {
        background: #f9fafb;
        border-radius: 16px;
        box-shadow: 0 2px 8px 0 rgba(0,0,0,0.04);
        overflow: hidden;
        transition: box-shadow 0.2s, transform 0.2s;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        min-height: 340px;
        text-decoration: none;
    }
    .main-guide-card:hover {
        box-shadow: 0 8px 24px 0 rgba(80,60,180,0.10);
        transform: translateY(-4px) scale(1.02);
    }
    .main-guide-card-img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        background: #e5e7eb;
    }
    .main-guide-card-content {
        padding: 20px 18px 18px 18px;
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
    }
    .main-guide-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #18181b;
        margin-bottom: 8px;
        flex: 0 0 auto;
    }
    .main-guide-card-desc {
        color: #6b7280;
        font-size: 0.98rem;
        margin-bottom: 12px;
        flex: 1 1 auto;
    }
    .main-guide-card-meta {
        color: #a78bfa;
        font-size: 0.9rem;
        font-weight: 600;
        margin-top: auto;
    }
</style>

<div class="main-hero-bg">
    <div class="container">
        <div class="main-hero-title">
            Learn AI in <span class="highlight">5 minutes</span> a day.
        </div>
        <div class="main-hero-desc">
            Get the latest AI guides, understand why it matters, and learn how to apply it in your work.
        </div>
        <form class="main-hero-form" autocomplete="off" onsubmit="return false;">
            <input type="email" placeholder="Email Address" required disabled>
            <button type="submit" disabled>Subscribe</button>
        </form>
        <div class="main-hero-logos">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg" alt="Google">
            <img src="https://upload.wikimedia.org/wikipedia/commons/0/05/Facebook_Logo_%282019%29.png" alt="Meta" style="height:28px;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" alt="Microsoft">
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/IBM_logo.svg" alt="IBM">
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/HubSpot_Logo.png" alt="HubSpot" style="height:28px;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/9/96/Cisco_logo_blue_2016.svg" alt="Cisco" style="height:28px;">
        </div>
    </div>
</div>

<div class="main-guides-section">
    <div class="main-guides-title">Latest Guides</div>
    <div class="main-guides-desc">The latest developments in AI, Tech and Robotics.</div>
    <div class="main-guides-filters">
        <button class="active" type="button">All</button>
        <button type="button">AI</button>
        <button type="button">Tech</button>
        <button type="button">Robotics</button>
    </div>
    <div class="main-guides-list">
        <?php foreach ($guides as $guide): ?>
            <a href="/login" class="main-guide-card">
                <?php if (!empty($guide['cover_url'])): ?>
                    <img src="<?= htmlspecialchars($guide['cover_url']) ?>" alt="<?= htmlspecialchars($guide['title']) ?>" class="main-guide-card-img">
                <?php else: ?>
                    <div class="main-guide-card-img"></div>
                <?php endif; ?>
                <div class="main-guide-card-content">
                    <div class="main-guide-card-title"><?= htmlspecialchars($guide['title']) ?></div>
                    <div class="main-guide-card-desc">
                        <?= htmlspecialchars(mb_strimwidth(strip_tags($guide['content_json'] ?? $guide['content_url'] ?? ''), 0, 90, '...')) ?>
                    </div>
                    <div class="main-guide-card-meta">
                        <?= htmlspecialchars($guide['difficulty_level']) ?>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?> 