<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js?render=6LdL2OIqAAAAAC3E8xbcg8cz9pFvG-0kjs9B9-Zb" async defer></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Login</h2>

        <!-- Mostrar errores generales -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Mostrar mensajes de éxito o error -->
        @if (session('message'))
            <div class="alert alert-info">
                {{ session('message') }}
            </div>
        @endif

        <form id="LoginForm" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">


            
            <button type="submit" class="btn btn-primary">Login</button>

            <div class="mt-3">
                <p>Not registered yet? <a href="{{ route('register') }}" class="btn btn-link">Regíster here</a></p>
            </div>
        </form>
    </div>

    <div class="spinner-overlay" id="spinnerOverlay">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-text">Verifying...</div>
    </div>


    <script>
        document.getElementById('LoginForm').addEventListener('submit', function(event) {
            event.preventDefault();
            document.getElementById('spinnerOverlay').style.display = 'flex';
            grecaptcha.ready(function() {
                grecaptcha.execute('6LdL2OIqAAAAAC3E8xbcg8cz9pFvG-0kjs9B9-Zb', {action: 'submit'}).then(function(token) {
                    document.getElementById('g-recaptcha-response').value = token;
                    document.getElementById('LoginForm').submit();
                });
            });
        });
        </script>
</body>


<style>
        
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
        body {
            background-color: #007bff; /* Fondo azul */
           
            color: #333;
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
    </style>
</html>
