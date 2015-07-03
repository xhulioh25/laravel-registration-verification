<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Verify Your Email Address</h2>

<div>
    <p>Hi {{$name}},</p>
    <p>Thanks for creating an account in our Online Library System.</p>
    <p>Please follow the link below to verify your email address:</p>
    <a href="{{ URL::to('register/verify/' . $conf) }}" target="_blank">Verify Your Account</a>.<br/>

    <p>System</p>

</div>

</body>
</html>