<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js?render=6LdL2OIqAAAAAC3E8xbcg8cz9pFvG-0kjs9B9-Zb" async defer></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>User Registration</h2>

                @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form id="registerForm" action="{{ route('register') }}" method="POST">
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
            <label for="password" class="form-label">Password (must contain uppercase letters, numbers, and special characters)</label>
               
                <input type="password" class="form-control" id="password" name="password" required/>
                <div id="passwordError" class="text-danger"></div>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div
                    class="cf-turnstile"
                    data-sitekey="0x4AAAAAAA_a5H6azc783Tuw"
                    data-callback="javascriptCallback"
                    ></div>

            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">


            <button type="submit" class="btn btn-primary">Register</button>

            </form>
            <div class="mt-3">
            <p>Already have an account?<a href="/login" class="btn btn-link">Login here</a></p>
        </div>
        
        <div id="postRegisterButtonContainer" class="mt-3" style="display: none;">
        <button type="button" id="resendActivation" class="btn btn-link  ">Resend activation email</button>
        </div>


        <div class="spinner-overlay" id="spinnerOverlay">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-text">Verifying...</div>
    </div>


    </div>


    <script>
       function turnstileCallback(token) {
        document.getElementById('cf-turnstile-response').value = token;
    }
    
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            e.preventDefault();
            document.getElementById('spinnerOverlay').style.display = 'flex';

            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;

            if (password !== passwordConfirmation) {
                document.getElementById('spinnerOverlay').style.display = 'none';
                document.getElementById('passwordError').textContent = 'Las contraseñas no coinciden.';
                return;
            }

            grecaptcha.ready(function() {
                grecaptcha.execute('6LdL2OIqAAAAAC3E8xbcg8cz9pFvG-0kjs9B9-Zb', {action: 'submit'}).then(function(token) {
                    document.getElementById('g-recaptcha-response').value = token;
                    document.getElementById('registerForm').submit();
                });
            });

            console.log("Formulario enviado");
        });


        document.getElementById('resendActivation').addEventListener('click', function () {
            const email = document.getElementById('email').value;

            if (!email) {
                alert('Please enter an email address.');
                return;
            }

            document.getElementById('spinnerOverlay').style.display = 'flex';

            fetch('{{ route('resendActivationEmail') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('spinnerOverlay').style.display = 'none';
                alert(data.message);
            })
            .catch(error => {
                document.getElementById('spinnerOverlay').style.display = 'none';
                alert('An error occurred while resending the activation email.');
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

