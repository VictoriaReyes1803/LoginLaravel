<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/enterprise.js?render=6LeU88UqAAAAACX51aVlmRpQzAtuCrpCoEN4PkNa"></script>

</head>
<body>
    <div class="container mt-5">
        <h2>Login</h2>
        <form id="loginForm">
            @csrf
            
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
            
            <button type="submit" class="btn btn-primary">Login</button>

            <div class="mt-3">
            <p>Not registered yet?<a href="/register" class="btn btn-link">Regístrate aquí</a></p>
        </div>

        </form>

        <div class="spinner-overlay" id="spinnerOverlay">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Charging...</span>
        </div>
        <span class="spinner-text">wait a bit...</span>
    </div>

        <div id="message" class="mt-3"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;


        document.getElementById('loginForm').addEventListener('submit', function (e) {
            e.preventDefault(); 

            document.getElementById('spinnerOverlay').style.display = 'flex';

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

           
            document.getElementById('emailError').textContent = '';
            document.getElementById('passwordError').textContent = '';
            document.getElementById('message').textContent = '';

            axios.post('{{ route('login') }}', {
                email: email,
                password: password
            })
            .then(function (response) {
                document.getElementById('spinnerOverlay').style.display = 'none';
                document.getElementById('message').textContent = response.data.message;
                sessionStorage.setItem('jwt_token', response.data.token); 

                  const verificationUrl = '{{ route('verification') }}';
                    window.location.href = verificationUrl;

            })
            .catch(function (error) {
                document.getElementById('spinnerOverlay').style.display = 'none';
                if (error.response && error.response.status === 422) {
                   
                    const errors = error.response.data.errors;
                    if (errors.email) {
                        document.getElementById('emailError').textContent = errors.email[0];
                    }
                    if (errors.password) {
                        document.getElementById('passwordError').textContent = errors.password[0];
                    }
                } else
                if(error.response && error.response.status === 401){
                    alert('Incorrect email or password')
                }
                else {
                    console.error(error);
                    alert(error.response.data.message);
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
