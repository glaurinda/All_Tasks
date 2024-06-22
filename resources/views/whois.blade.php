<!DOCTYPE html>
<html>
<head>
    <title>Whois Lookup</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            margin: 20px;
        }
        .result-section {
            margin-top: 20px;
        }
        .result-section ul {
            list-style-type: none;
            padding: 0;
        }
        .result-section ul li {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Whois Lookup</h1>
        <form action="/whois" method="GET" class="form-inline">
            <div class="form-group mb-2">
                <label for="domain" class="sr-only">Enter Domain Name:</label>
                <input type="text" id="domain" name="domain" class="form-control" placeholder="example.com" required>
            </div>
            <button type="submit" class="btn btn-primary mb-2">Lookup</button>
        </form>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (isset($domainInfo))
            <div class="result-section">
                <h2>Domain Information</h2>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Domain:</strong> {{ $domainInfo['domain'] }}</li>
                    <li class="list-group-item"><strong>Creation Date:</strong> {{ $domainInfo['creation_date'] }}</li>
                    <li class="list-group-item"><strong>Expiration Date:</strong> {{ $domainInfo['expiration_date'] }}</li>
                    <li class="list-group-item"><strong>Name Servers:</strong> {{ implode(', ', $domainInfo['name_servers']) }}</li>

                </ul>
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger">
                <p>{{ session('error') }}</p>
            </div>
        @endif
    </div>
</body>
</html>
