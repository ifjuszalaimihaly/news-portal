<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($news_item['title']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 2rem;
        }
        .news-title {
            font-size: 2rem;
            margin-bottom: 0.25rem;
        }
        .news-meta {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        .news-intro {
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .news-content {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .news-image {
            max-width: 100%;
            height: auto;
            margin: 1rem 0;
            border: 1px solid #ccc;
        }
        .back-link {
            display: inline-block;
            margin-top: 1rem;
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

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
