<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" value="{{ csrf_token() }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    <style> @import url('https://fonts.googleapis.com/css2?family=Nunito&display=swap'); * {font-family: 'Nunito', sans-serif;} </style>
    <title>Tether</title>
</head>
<body class="h-screen w-screen">
    @yield('content')

    <script type="text/javascript" src="https://cdn.tailwindcss.com/3.2.4"></script>
</body>
</html>