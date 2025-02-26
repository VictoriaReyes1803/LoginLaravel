<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>2FA Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>2FA Verification</h2>
        <form id="verifyForm">
        @csrf
            <div class="mb-3">
                <label for="code" class="form-label">Verification Code</label>
                <input type="text" class="form-control" id="code" name="code" required>
                <div id="codeError" class="text-danger"></div>
            </div>

            <button type="submit" class="btn btn-primary">Verify Code</button>
        </form>

        <div class="spinner-overlay" id="spinnerOverlay">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Charging...</span>
        </div>
        <span class="spinner-text">Charging...</span>
    </div>

        <div id="message" class="mt-3"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
         const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        const token = sessionStorage.getItem('jwt_token');
        if (!token) {
            window.location.href = '{{ route('login') }}';
        }


        document.getElementById('verifyForm').addEventListener('submit', function (e) {
            e.preventDefault(); 

            const code = document.getElementById('code').value;

            document.getElementById('spinnerOverlay').style.display = 'flex';

            document.getElementById('codeError').textContent = '';
            document.getElementById('message').textContent = '';

            const token = sessionStorage.getItem('jwt_token');
            
            axios.post('{{ route('verification') }}', {
                code: code,
                token: token, 
            })
            .then(function (response) {
                console.log(response);
                
                document.getElementById('spinnerOverlay').style.display = 'none';

                document.getElementById('message').textContent = response.data.message;
                sessionStorage.clear();

                window.location.href = '{{ route('dashboard') }}';
                            
            })
            .catch(function (error) {
                document.getElementById('spinnerOverlay').style.display = 'none';

                if (error.response && error.response.status === 400) {
                   
                    document.getElementById('codeError').textContent = 'Incorrect code. Please try again.';
                } else {
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


