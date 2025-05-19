<!DOCTYPE html>
<html lang="en">
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
        .action-buttons {
            margin-top: 0.5rem;
        }
        .action-buttons button {
            margin-right: 0.5rem;
            padding: 0.3rem 0.6rem;
            cursor: pointer;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
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
