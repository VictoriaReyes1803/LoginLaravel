<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Verification</h2>

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

        @if (session('message'))
            <div class="alert alert-info">
                {{ session('message') }}
            </div>
        @endif

        <form id="verificationForm" action="{{ route('verification') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="code" class="form-label">Verification Code</label>
                <input type="text" class="form-control" id="code" name="code" required>
                @error('code')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Verify</button>
        </form>
    </div>


    <div class="spinner-overlay" id="spinnerOverlay">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-text">Verifying...</div>
    </div>

    <script>
        // Show spinner on form submission
        document.getElementById('verificationForm').addEventListener('submit', function() {
            document.getElementById('spinnerOverlay').style.display = 'flex';
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


