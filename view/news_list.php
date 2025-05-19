<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>News</title>
    <link rel="stylesheet" href="/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include 'view/partials/header.php'; ?>
    <h1>The newest news</h1>

    <?php if (empty($news_list)): ?>
        <p>There are no available news</p>
    <?php else: ?>
        <?php foreach ($news_list as $news_item): ?>
            <div class="news-item" data-slug="<?= htmlspecialchars($news_item['slug']) ?>" data-id="<?= $news_item['id'] ?>">
                <div class="news-title"><?= htmlspecialchars($news_item['title']) ?></div>
                <div class="news-meta">
                    <?= htmlspecialchars($news_item['published_at']) ?> — 
                    <?= htmlspecialchars($news_item['author']) ?>
                </div>
                <div class="news-intro"><?= nl2br(htmlspecialchars($news_item['intro'])) ?></div>
                <a class="news-link" href="/index.php/news/show/<?= urlencode($news_item['slug']) ?>">Read more →</a>

                <div class="action-buttons">
                    <button class="edit-button">Edit</button>
                    <button class="delete-button">Delete</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <script>
        $(document).ready(function () {
            $('.delete-button').click(function () {
                const item = $(this).closest('.news-item');
                const slug = item.data('slug');
                const id = item.data('id');

                if (!confirm('Are you sure you want to delete this news item?')) return;

                $.ajax({
                    url: '/index.php',
                    method: 'POST',
                    data: { id: id, form_action: 'delete_news'},
                    success: function (response) {
                        if (response.success) {
                            item.remove();
                        } else {
                            alert('Delete failed: ' + (response.error || 'Unknown error.'));
                        }
                    },
                    error: function () {
                        alert('Server error during deletion.');
                    }
                });
            });

            $('.edit-button').click(function () {
                const slug = $(this).closest('.news-item').data('slug');
                window.location.href = '/index.php/news/show_news_form/' + encodeURIComponent(slug);
            });
        });
    </script>
</body>
</html>
