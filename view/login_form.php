<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="/styles.css">
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <h2>Login</h2>

    <div class="message" id="loginMessage"></div>

    <form id="loginForm" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <input type="hidden" name="form_action" value="handle_login">

        <input type="submit" value="Login">
    </form>

    <script>
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();

            const form = $('#loginForm')[0];
            const formData = new FormData(form);

            $.ajax({
                url: '/index.php/users/handle_login',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#loginMessage')
                        .css('color', 'green')
                        .text('Login successful! Redirecting...');
                    setTimeout(() => {
                        window.location.href = '/index.php/news/list';
                    }, 1000);
                },
                error: function(xhr) {
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
