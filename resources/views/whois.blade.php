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
        @if(isset($domainInfo))
        <h1>Informations sur le domaine</h1>
        <ul>
            @foreach($domainInfo as $key => $value)
                <li><strong>{{ $key }}:</strong> {{ $value }}</li>
            @endforeach
        </ul>
    @elseif(isset($error))
        <p>Error: {{ $error }}</p>
    @else
        <p>No information available for this domain.</p>
    @endif

    </div>
</body>
</html>
