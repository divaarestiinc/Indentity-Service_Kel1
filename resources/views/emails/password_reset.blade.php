<!doctype html>
<html>
  <body>
    <p>Hi,</p>
    <p>You requested to reset your password. Use this token to reset:</p>
    <p><strong>{{ $token }}</strong></p>
    <p>Or click: <a href="{{ $reset_url }}">{{ $reset_url }}</a></p>
    <p>If you didn't request this, ignore.</p>
  </body>
</html>
