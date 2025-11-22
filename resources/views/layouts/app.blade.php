<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f4f4f4; display:flex; justify-content:center; align-items:center; height:100vh; }
        .box { background:white; padding:20px; border-radius:10px; width:300px; box-shadow:0 0 10px rgba(0,0,0,0.2); }
        .box h2 { margin-bottom:20px; text-align:center; }
        input { width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:5px; }
        .password-wrapper { position:relative; }
        .toggle-password {
            position:absolute;  
            right:10px;
            top:50%;
            transform:translateY(-50%);
            cursor:pointer;
            font-size:14px;
            color:#007BFF;
            user-select:none;
        }
        button { width:100%; padding:10px; background:#007BFF; border:none; border-radius:5px; color:white; cursor:pointer; font-weight:bold; }
        button:hover { background:#0056b3; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Login Admin</h2>
        <form method="POST" action  ="{{ route('admin.login') }}">
            @csrf
            <input type="text" name="nama_admin" placeholder="Nama Admin" required>

            <div class="password-wrapper">
                <input type="password" id="password" name="kode_admin" placeholder="Password" required>
                <span class="toggle-password" onclick="togglePassword()">👁️</span>
            </div>

            <button type="submit">Login</button>
        </form>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById("password");
            if (password.type === "password") {
                password.type = "text";
            } else {
                password.type = "password";
            }
        }
    </script>
</body>
</html>
