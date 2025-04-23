<?php
// Ovde može ići PHP kod ako je potreban pre prikaza forme
?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Kreiraj Autora</title>
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
                text-align: center;
                margin-bottom: 20px;
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

            /* Sakrivamo poruke inicijalno */
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
        <h2>Kreiraj Autora</h2>
        <form action="processCreateAuthor.php" method="post" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="firstName">Ime:</label>
                <input type="text" id="firstName" name="firstName" maxlength="100" required
                       oninvalid="this.nextElementSibling.style.display='block'"
                       oninput="this.nextElementSibling.style.display='none'">
                <p class="error-message">* Ovo polje je obavezno</p>
            </div>
            <div class="form-group">
                <label for="lastName">Prezime:</label>
                <input type="text" id="lastName" name="lastName" maxlength="100" required
                       oninvalid="this.nextElementSibling.style.display='block'"
                       oninput="this.nextElementSibling.style.display='none'">
                <p class="error-message">* Ovo polje je obavezno</p>
            </div>
            <button type="submit">Sačuvaj</button>

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
// Ovde može ići PHP kod nakon prikaza forme
?>