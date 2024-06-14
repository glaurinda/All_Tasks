<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Domain Checker</title>
</head>
<body>
    <h1>Welcome to {{ config('app.name') }} Domain Checker</h1>
    <form action="{{ route('domains.check') }}" method="POST">
        @csrf
        <input type="text" name="domain" placeholder="Enter domain name">
        <button type="submit">Check Domain</button>
    </form>
    <div id="result"></div>

    <script>
        document.querySelector('form').addEventListener('submit', function (event) {
            event.preventDefault();

            let formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                let resultDiv = document.getElementById('result');
                if (data.error) {
                    resultDiv.innerHTML = `<p>${data.error}</p>`;
                } else {
                    resultDiv.innerHTML = `
                        <p>Domain: ${data.name}</p>
                        <p>Creation Date: ${data.creation_date}</p>
                        <p>Expiration Date: ${data.expiration_date}</p>
                        <p>Updated Date: ${data.updated_date}</p>
                    `;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
