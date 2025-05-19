<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create News Article</title> <!-- Set page title -->
    <link rel="stylesheet" href="/styles.css"> <!-- Link external stylesheet -->
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Load jQuery -->
</head>
<body>
    <?php include 'view/partials/header.php'; ?> <!-- Include the site navigation header -->
    <h1>Create News Article</h1> <!-- Main heading -->

    <div class="message" id="responseMessage"></div> <!-- Placeholder for success/error messages -->

    <!-- News creation/editing form -->
    <form id="newsForm" method="POST" enctype="multipart/form-data">
        <!-- Title input -->
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" 
            value="<?= htmlspecialchars($news_item['title'] ?? '') ?>" required>

        <!-- Introduction input -->
        <label for="intro">Introduction:</label>
        <textarea id="intro" name="intro" required><?= htmlspecialchars($news_item['intro'] ?? '') ?></textarea>

        <!-- Full content input -->
        <label for="content">Full Content:</label>
        <textarea id="content" name="content" required><?= htmlspecialchars($news_item['content'] ?? '') ?></textarea>

        <!-- Image upload -->
        <label for="image">Upload Image (PNG or JPEG):</label>
        <input type="file" id="image" name="image" accept="image/png, image/jpeg">

        <!-- Show current image if exists -->
        <?php if (!empty($news_item['image_path'])): ?>
            <p>Current image: <strong><?= htmlspecialchars(basename($news_item['image_path'])) ?></strong></p>
        <?php endif; ?>

        <!-- Hidden field to indicate form purpose -->
        <input type="hidden" name="form_action" value="handle_news_form">

        <!-- Hidden ID field if editing existing news -->
        <?php if (!is_null($news_item)): ?>
            <input type="hidden" name="id" value="<?= $news_item['id'] ?>">
        <?php endif; ?>
        
        <!-- Submit button -->
        <input type="submit" value="<?= isset($news_item) ? 'Update News' : 'Save News' ?>">
    </form>

    <script>
        // Handle form submission via AJAX
        $('#newsForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission behavior

            const form = $('#newsForm')[0];
            const formData = new FormData(form);
            const isEdit = formData.has('id'); // Check if form is for editing

            // Send form data via AJAX
            $.ajax({
                url: '/index.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log("succcess");
                    // Show success message
                    const message = isEdit ? 'News successfully updated!' : 'News successfully created!';
                    $('#responseMessage').css('color', 'green').text(message);
                    // Reset form only if creating a new item
                    if (!isEdit) {
                        $('#newsForm')[0].reset();
                    }
                },
                error: function(xhr, status, error) {
                    // Handle and display error message
                    let message = 'An error occurred.';

                    try {
                        const json = JSON.parse(xhr.responseText);
                        if (json.error) {
                            message = json.error;
                        }
                    } catch (e) {
                        message = 'Unexpected error: ' + error;
                    }

                    $('#responseMessage').css('color', 'red').text(message);
                }
            });
        });
    </script>

</body>
</html>
