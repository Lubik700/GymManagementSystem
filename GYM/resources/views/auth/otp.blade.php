<!DOCTYPE html>
<html>
<head><title>Verify OTP</title></head>
<body>
<div style="max-width:400px; margin:80px auto; text-align:center;">
    <h2>Enter OTP</h2>
    <p>A 6-digit OTP has been sent to <strong>{{ session('pending_registration.email') }}</strong></p>

    @if($errors->any())
        <div style="color:red;">{{ $errors->first() }}</div>
    @endif
    @if(session('success'))
        <div style="color:green;">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('register.verify-otp') }}">
        @csrf
        <input type="text" name="otp" maxlength="6" placeholder="Enter 6-digit OTP"
               style="font-size:24px; letter-spacing:10px; text-align:center; width:200px; padding:10px;" required>
        <br><br>
        <button type="submit">Verify OTP</button>
    </form>

    <br>
    <form method="POST" action="{{ route('register.resend-otp') }}">
        @csrf
        <button type="submit" style="background:none; border:none; color:blue; cursor:pointer;">
            Resend OTP
        </button>
    </form>
</div>
</body>
</html>