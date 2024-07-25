<!DOCTYPE html>
<html>
<head>
    <title>Pemberitahuan Reset Password</title>
</head>
<body>
    <h1>Pemberitahuan Reset Password</h1>
    <p>Klik link di bawah untuk mereset password akun anda:</p>
    <a href="{{ url('/reset-password/' . $token) }}">Reset Password</a>
</body>
</html>