<!DOCTYPE html>
<html>
<head>
    <title>Domain Expiration Notification</title>
</head>
<body>
    <h1>Domain Expiration Notification</h1>
    <p>Dear user,</p>
    <p>The domain <strong>{{ $domain->name }}</strong> is set to expire on <strong>{{ $domain->expiry_date }}</strong>.</p>
    <p>Please take the necessary action to renew your domain.</p>
    <p>Thank you!</p>
</body>
</html>
