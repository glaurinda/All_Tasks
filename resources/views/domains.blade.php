<!DOCTYPE html>
<html>
<head>
    <title>Liste des domaines</title>
</head>
<body>
    <h1>Liste des domaines :</h1>
    <ul>
        @foreach($domains as $domain)
            <li>{{ $domain }}</li>
        @endforeach
    </ul>
</body>
</html>
