<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<style>
    body{
        background: #f5f5f9;
    }
</style>

<body class="border-primary d-flex flex-column">

    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="text-center mb-4">
                <img src="https://app.iexe.edu.mx/seguimiento/assets/img/iexe_login.jpg" alt="logo" width="88" height="88" style="border-radius: 20px;">
                <h1 class="mt-3">IEXE - Sistema de Requisiciones</h1>
            </div>
            
            <form class="card card-md" autocomplete="off">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">
                        Login to your account
                    </h2>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input name="username" type="text" class="form-control" placeholder="Enter username" id="username">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input name="username" type="password" class="form-control" placeholder="Enter password" id="password">
                    </div>

                    <div class="mb-2">
                        <label class="form-check">
                            <input type="checkbox" name="remember_me" class="form-check-input" checked="checked">
                            <span class="form-check-label">Remember me</span>
                        </label>
                    </div>
                    <div class="form-footer">
                        
                        <button class="btn btn-primary w-100" id="enviar" type="button">
                            Sign in
                        </button>
                        
                    </div>
                </div>
            </form>

        </div>
    </div>

<script>
    
    $(document).on("click", "#enviar", function(event) {

        event.preventDefault();

        let username = $('#username').val();
        let password = $('#password').val();

        $.ajax({
            url: 'http://127.0.0.1/requisiciones/login/validar',
            type: 'POST',
            data: {
                username: username,
                password: password
            },
            success: function(response) {
                
                if (response.status === 'success') {
                
                    window.location.href = 'http://localhost/requisiciones/inventario/lista';

                }else{

                    $("#username").addClass("is-invalid");
                    $("#password").addClass("is-invalid");
                    
                }

            }
        });

    });

    $("#username").keydown(function(){
        $(this).removeClass("is-invalid");
        $(this).addClass("is-valid");
    });

    $("#password").keydown(function(){
        $(this).removeClass("is-invalid");
        $(this).addClass("is-valid");
    });

</script>

</body>
</html>
