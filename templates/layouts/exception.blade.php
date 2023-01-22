<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        @import url(https://fonts.bunny.net/css?family=inter:400);
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        code *,pre * { font-family: "Lucida Console", monospace, sans-serif; }
        pre code.hljs { padding: 0 !important; overflow: visible !important; }
    </style>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/default.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/monokai-sublime.min.css" integrity="sha512-ade8vHOXH67Cm9z/U2vBpckPD1Enhdxl3N05ChXyFx5xikfqggrK4RrEele+VWY/iaZyfk7Bhk6CyZvlh7+5JQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Whoops - {{ $exception->getMessage() }}</title>
</head>
<body class="h-screen w-screen bg-gray-900">
@yield('content')

<script type="text/javascript" src="https://cdn.tailwindcss.com/3.2.4"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlightjs-line-numbers.js/2.8.0/highlightjs-line-numbers.min.js"></script>
@yield('scripts')
<script>
    hljs.highlightAll();
    
    document.querySelectorAll('code.hljs').forEach(block => {
        hljs.lineNumbersBlock(block);
    });
</script>
</body>
</html>