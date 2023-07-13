<!-- resources/views/static_page.blade.php -->
<html>

<head>
    <title>Static Page</title>
</head>

<body>
    <h1>Static Page</h1>

    @if ($data === null)
    <p>Loading...</p>
    @else
    <p>Current : {{ $decrypted_data }}</p>
    @endif

    <script>
        var pageData = @json($data);
    </script>
</body>

</html>