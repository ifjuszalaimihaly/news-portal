<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="/styles.css">
    <!-- jQuery CDN for AJAX functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <!-- Page heading -->
    <h2>Login</h2>

    <!-- Area to display success or error message -->
    <div class="message" id="loginMessage"></div>

    <!-- Login form -->
    <form id="loginForm" method="POST">
        <!-- Email input -->
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <!-- Password input -->
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <!-- Hidden input to identify the form action -->
        <input type="hidden" name="form_action" value="handle_login">

        <!-- Submit button -->
        <input type="submit" value="Login">
    </form>

    <script>
        // Handle form submission using jQuery AJAX
        $('#loginForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            const form = $('#loginForm')[0];             // Get the form DOM element
            const formData = new FormData(form);         // Create FormData object for AJAX submission

            $.ajax({
                url: '/index.php/users/handle_login',     // Backend handler for login
                method: 'POST',
                data: formData,
                processData: false,                       // Prevent jQuery from processing data
                contentType: false,                       // Prevent jQuery from setting content type
                success: function(response) {
                    // Display success message and redirect after delay
                    $('#loginMessage')
                        .css('color', 'green')
                        .text('Login successful! Redirecting...');
                    setTimeout(() => {
                        window.location.href = '/index.php/news/list';
                    }, 1000);
                },
                error: function(xhr) {
                    // Display error message returned from server or a default one
                    const msg = xhr.responseJSON?.error || 'Login failed.';
                    $('#loginMessage')
                        .css('color', 'red')
                        .text(msg);
                }
            });
        });
    </script>

</body>
</html>
