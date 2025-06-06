<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>News</title> <!-- Page title -->
    <link rel="stylesheet" href="/styles.css"> <!-- Main stylesheet -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 for confirmation dialogs -->
</head>
<body>
    <?php include 'view/partials/header.php'; ?> <!-- Include navigation header -->

    <h1>The newest news</h1> <!-- Page heading -->

    <!-- Check if the news list is empty -->
    <?php if (empty($news_list)): ?>
        <p>There are no available news</p>
    <?php else: ?>
        <!-- Loop through news items -->
        <?php foreach ($news_list as $news_item): ?>
            <div class="news-item" data-slug="<?= htmlspecialchars($news_item['slug']) ?>" data-id="<?= $news_item['id'] ?>">
                <div class="news-title"><?= htmlspecialchars($news_item['title']) ?></div>
                <div class="news-meta">
                    <?= htmlspecialchars($news_item['published_at']) ?> — 
                    <?= htmlspecialchars($news_item['author_name']) ?>
                </div>
                <div class="news-intro"><?= nl2br(htmlspecialchars($news_item['intro'])) ?></div>
                <a class="news-link" href="/index.php/news/show/<?= urlencode($news_item['slug']) ?>">Read more →</a>

                <!-- Show edit/delete buttons only if the current user is the author -->
                <?php if(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] === $news_item['user_id']): ?>
                <div class="action-buttons">
                    <button class="edit-button">Edit</button>
                    <button class="delete-button">Delete</button>
                </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <script>
        $(document).ready(function () {
            // Delete button click handler
            $('.delete-button').click(function () {
                const item = $(this).closest('.news-item'); // Get the parent .news-item
                const slug = item.data('slug');             // Get the slug from data attribute
                const id = item.data('id');                 // Get the ID from data attribute

                // Confirm deletion using SweetAlert2
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to undo this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Perform AJAX request to delete news
                        $.ajax({
                            url: '/index.php',
                            method: 'POST',
                            data: { id: id, form_action: 'delete_news' },
                            success: function (response) {
                                if (response.success) {
                                    item.remove(); // Remove the news item from DOM
                                    Swal.fire('Deleted!', 'The news item has been deleted.', 'success');
                                } else {
                                    Swal.fire('Error', response.error || 'Delete failed.', 'error');
                                }
                            },
                            error: function (xhr) {
                                // Handle unexpected error
                                let message = 'Unexpected error during deletion.';
                                try {
                                    const json = JSON.parse(xhr.responseText);
                                    if (json.error) message = json.error;
                                } catch (e) {}
                                Swal.fire('Error', message, 'error');
                            }
                        });
                    }
                });
            });

            // Edit button click handler
            $('.edit-button').click(function () {
                const slug = $(this).closest('.news-item').data('slug'); // Get slug
                // Redirect to the news edit form
                window.location.href = '/index.php/news/show_news_form/' + encodeURIComponent(slug);
            });
        });
    </script>
</body>
</html>
