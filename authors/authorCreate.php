<?php
// PHP code can go here if needed before displaying the form
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Create Author</title>
        <style>
            body {
                font-family: sans-serif;
            }

            .container {
                width: 80%;
                max-width: 500px;
                margin: 20px auto;
                border: 1px solid #ccc;
                padding: 20px;
                border-radius: 5px;
            }

            h2 {
                text-align: left;
                border-bottom: 2px solid #ADD8E6;
                padding-bottom: 5px;
                margin-bottom: 10px;
            }

            .form-group {
                margin-bottom: 15px;
            }

            label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }

            input[type="text"] {
                width: 100%;
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 3px;
                box-sizing: border-box;
            }

            .error-message {
                color: red;
                font-size: 0.9em;
                margin-top: 5px;
                display: none;
            }

            /* Initially hide messages */
            button[type="submit"] {
                background-color: #007bff;
                color: white;
                padding: 10px 15px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 1em;
            }

            button[type="submit"]:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <h2 style="text-align: left;">Author Create</h2>
        <form action="processCreateAuthor.php" method="post" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="firstName">First name:</label>
                <input type="text" id="firstName" name="firstName" maxlength="100" required
                       oninvalid="this.nextElementSibling.style.display='block'"
                       oninput="this.nextElementSibling.style.display='none'">
                <p class="error-message">* This field is required</p>
            </div>
            <div class="form-group">
                <label for="lastName">Last name:</label>
                <input type="text" id="lastName" name="lastName" maxlength="100" required
                       oninvalid="this.nextElementSibling.style.display='block'"
                       oninput="this.nextElementSibling.style.display='none'">
                <p class="error-message">* This field is required</p>
            </div>
            <button type="submit">Save</button>

            <script>
                function validateForm() {
                    let valid = true;
                    const firstNameInput = document.getElementById('firstName');
                    const lastNameInput = document.getElementById('lastName');
                    const firstNameError = firstNameInput.nextElementSibling;
                    const lastNameError = lastNameInput.nextElementSibling;

                    if (!firstNameInput.value.trim()) {
                        firstNameError.style.display = 'block';
                        valid = false;
                    } else {
                        firstNameError.style.display = 'none';
                    }

                    if (!lastNameInput.value.trim()) {
                        lastNameError.style.display = 'block';
                        valid = false;
                    } else {
                        lastNameError.style.display = 'none';
                    }

                    return valid;
                }
            </script>
        </form>
    </div>
    </body>
    </html>

<?php
// PHP code can go here after displaying the form
?>