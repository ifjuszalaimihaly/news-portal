<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create News Article</title>
    <link rel="stylesheet" href="/styles.css">
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include 'view/partials/header.php'; ?>
    <h1>Create News Article</h1>

    <div class="message" id="responseMessage"></div>

    <form id="newsForm" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" 
            value="<?= htmlspecialchars($news_item['title'] ?? '') ?>" required>

        <label for="intro">Introduction:</label>
        <textarea id="intro" name="intro" required><?= htmlspecialchars($news_item['intro'] ?? '') ?></textarea>

        <label for="content">Full Content:</label>
        <textarea id="content" name="content" required><?= htmlspecialchars($news_item['content'] ?? '') ?></textarea>

        <label for="image">Upload Image (PNG or JPEG):</label>
        <input type="file" id="image" name="image" accept="image/png, image/jpeg">

        <?php if (!empty($news_item['image_path'])): ?>
            <p>Current image: <strong><?= htmlspecialchars(basename($news_item['image_path'])) ?></strong></p>
        <?php endif; ?>

        <input type="hidden" name="form_action" value="handle_news_form">
        <?php if (!is_null($news_item)): ?>
            <input type="hidden" name="id" value="<?= $news_item['id'] ?>">
        <?php endif; ?>
        
        <input type="submit" value="<?= isset($news_item) ? 'Update News' : 'Save News' ?>">
    </form>

    <script>
        $('#newsForm').on('submit', function(e) {
            e.preventDefault(); // prevent default form submit

            const form = $('#newsForm')[0];
            const formData = new FormData(form);
            const isEdit = formData.has('id');

            $.ajax({
                url: '/index.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    const message = isEdit ? 'News successfully updated!' : 'News successfully created!';
                    $('#responseMessage').css('color', 'green').text(message);
                    if (!isEdit) {
                        $('#newsForm')[0].reset(); // only reset if creating new
                    }
                },
                error: function(xhr, status, error) {
                    const message = isEdit ? 'Failed to update news: ' : 'Failed to create news: ';
                    $('#responseMessage').css('color', 'red').text(message + error);
                }
            });
        });
    </script>

</body>
</html>
