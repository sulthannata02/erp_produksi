<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
</head>
<body>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <h2>/h2>
    <a href="/materials">Material</a> |
    <a href="/productions">Production</a>

    @yield('content') {{-- INI YANG PENTING --}}

</body>
</html>