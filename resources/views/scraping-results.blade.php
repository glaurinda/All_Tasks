<!-- resources/views/scraping-results.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Scraping Results</title>
</head>
<body>
    <h1>Scraping Results</h1>

    @if(isset($error))
        <p>{{ $error }}</p>
    @else
        <p><strong>Domain Name:</strong> {{ $data['domain_name'] }}</p>
        <p><strong>Status:</strong> {{ $data['status'] }}</p>
        @if($data['status'] == 'registered')
            <p><strong>Expiration Date:</strong> {{ $data['expiration_date'] }}</p>
            <p><strong>Registration Date:</strong> {{ $data['registration_date'] }}</p>
            <p><strong>Update Date:</strong> {{ $data['update_date'] }}</p>
        @endif
    @endif
</body>
</html>
