<!DOCTYPE html>
<html>
<head>
    <title>WebSecService</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="#">WebSecService</a>
        <div class="navbar-nav">
            <a class="nav-link" href="/minitest">MiniTest</a>
            <a class="nav-link" href="/transcript">Transcript</a>
            <a class="nav-link" href="/products">Products</a>
            <a class="nav-link" href="/calculator">Calculator</a>
            <a class="nav-link" href="/gpa">GPA</a>
        </div>
    </nav>
    <div class="container py-4">
        @yield('content')
    </div>
</body>
</html>
