<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>News</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 2rem;
        }
        .news-item {
            border-bottom: 1px solid #ccc;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
        }
        .news-title {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }
        .news-meta {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .news-intro {
            font-size: 1rem;
        }
        .news-link {
            margin-top: 0.5rem;
            display: inline-block;
            font-weight: bold;
            color: #007BFF;
            text-decoration: none;
        }
        .news-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>The newest news</h1>

    <?php if (empty($news_list)): ?>
        <p>Thre are not available news</p>
    <?php else: ?>
        <?php foreach ($news_list as $news_item): ?>
            <div class="news-item">
                <div class="news-title"><?= htmlspecialchars($news_item['title']) ?></div>
                <div class="news-meta">
                    <?= htmlspecialchars($news_item['published_at']) ?> — 
                    <?= htmlspecialchars($news_item['author']) ?>
                </div>
                <div class="news-intro"><?= nl2br(htmlspecialchars($news_item['intro'])) ?></div>
                <a class="news-link" href="/index.php/news/show/<?= urlencode($news_item['slug']) ?>">Next →</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
