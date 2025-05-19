<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($news_item['title']) ?></title>
    <link rel="stylesheet" href="/styles.css">
    </style>
</head>
<body>
    <?php include 'view/partials/header.php'; ?>
    <div class="news-title"><?= htmlspecialchars($news_item['title']) ?></div>
    <div class="news-meta">
        <?= htmlspecialchars($news_item['published_at']) ?> — <?= htmlspecialchars($news_item['author']) ?>
    </div>

    <?php if (!empty($news_item['image_path'])): ?>
        <img class="news-image" src="<?= htmlspecialchars('/'.$news_item['image_path']) ?>" alt="News image">
    <?php endif; ?>

    <div class="news-intro"><?= nl2br(htmlspecialchars($news_item['intro'])) ?></div>
    <div class="news-content"><?= nl2br(htmlspecialchars($news_item['content'])) ?></div>

    <a class="back-link" href="/index.php/news">← Back to the all news</a>

</body>
</html>
