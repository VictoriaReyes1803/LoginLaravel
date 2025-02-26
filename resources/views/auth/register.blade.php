<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>User Registration</h2>
        <form id="registerForm">
        @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
                <div id="nameError" class="text-danger"></div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div id="emailError" class="text-danger"></div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <div id="passwordError" class="text-danger"></div>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>

            
            <div class="mt-3">
            <p>Already have an account?<a href="/login" class="btn btn-link">Login here</a></p>
        </div>
        </form>
        <div id="postRegisterButtonContainer" class="mt-3" style="display: none;">
        <button type="button" id="resendActivation" class="btn btn-link  ">Resend activation email</button>
        </div>


        <div class="spinner-overlay" id="spinnerOverlay">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Charging...</span>
        </div>
        <span class="spinner-text">Charging...</span>
    </div>

        <div id="successMessage" class="mt-3 text-success"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;


        document.getElementById('registerForm').addEventListener('submit', function (e) {
            e.preventDefault(); 
            document.getElementById('spinnerOverlay').style.display = 'flex';

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
         
            if (password !== passwordConfirmation) {
            document.getElementById('passwordError').textContent = 'Las contraseñas no coinciden.';
            document.getElementById('spinnerOverlay').style.display = 'none';
            return;
        }
      
            document.getElementById('nameError').textContent = '';
            document.getElementById('emailError').textContent = '';
            document.getElementById('passwordError').textContent = '';
            document.getElementById('successMessage').textContent = '';

            axios.post('{{ route('register') }}', {
                name: name,
                email: email,
                password: password,
                password_confirmation: passwordConfirmation
            })
            .then(function (response) {
                document.getElementById('spinnerOverlay').style.display = 'none';

                console.log('',response.data.message);
                document.getElementById('successMessage').textContent = response.data.message;
                document.getElementById('postRegisterButtonContainer').style.display = 'block';
            })
            .catch(function (error) {
                document.getElementById('spinnerOverlay').style.display = 'none';


                if (error.response && error.response.status === 422) {
                    
                    const errors = error.response.data.errors;
                    if (errors.name) {
                        document.getElementById('nameError').textContent = errors.name[0];
                    }
                    if (errors.email) {
                        document.getElementById('emailError').textContent = errors.email[0];
                    }
                    if (errors.password) {
                        document.getElementById('passwordError').textContent = errors.password[0];
                    }
                } else {
                    console.error(error);
                    alert(error.response.data.message);
                }
            });
        });



        document.getElementById('resendActivation').addEventListener('click', function () {
            const email = document.getElementById('email').value;

            if (!email) {
                alert('Please enter an email address.');
                return;
            }

            document.getElementById('spinnerOverlay').style.display = 'flex';

            axios.post('{{ route('resendActivationEmail') }}', { email: email })
            .then(function (response) {
                document.getElementById('spinnerOverlay').style.display = 'none';
                alert(response.data.message);
            })
            .catch(function (error) {
                document.getElementById('spinnerOverlay').style.display = 'none';

                if (error.response && error.response.status === 429) {
                    alert('Please wait a moment before forwarding the email.');
                } else {
                    alert(error.response.data.message || 'An error occurred while forwarding the email.');
                }
            });
        });
    </script>
</body>

<style>
        /* Estilo del contenedor del spinner */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display:none;
            justify-content: center;
            align-items: center;
            z-index: 9999; 
            backdrop-filter: blur(5px);
        }

        /* Estilo del spinner */
        .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.3em;
        }

        .spinner-text {
            color: white;
            margin-left: 10px;
            font-size: 1.2rem;
        }

        body {
            background-color: #007bff; /* Fondo azul */
            color: #333; /* Texto en color oscuro para contraste */
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 500px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
        }
    </style>
</html>

