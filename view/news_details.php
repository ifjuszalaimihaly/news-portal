<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($news_item['title']) ?></title> <!-- Set page title from news item -->
    <link rel="stylesheet" href="/styles.css"> <!-- Include external CSS -->
    </style>
</head>
<body>
    <?php include 'view/partials/header.php'; ?> <!-- Include navigation header -->

    <!-- Display news title -->
    <div class="news-title"><?= htmlspecialchars($news_item['title']) ?></div>

    <!-- Display publication date and author name -->
    <div class="news-meta">
        <?= htmlspecialchars($news_item['published_at']) ?> — <?= htmlspecialchars($news_item['author_name']) ?>
    </div>

    <!-- Display news image if available -->
    <?php if (!empty($news_item['image_path'])): ?>
        <img class="news-image" src="<?= htmlspecialchars('/'.$news_item['image_path']) ?>" alt="News image">
    <?php endif; ?>

    <!-- Display news intro -->
    <div class="news-intro"><?= nl2br(htmlspecialchars($news_item['intro'])) ?></div>

    <!-- Display full news content -->
    <div class="news-content"><?= nl2br(htmlspecialchars($news_item['content'])) ?></div>

    <!-- Back link to news list -->
    <a class="back-link" href="/index.php/news">← Back to the all news</a>

</body>
</html>
