<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Electshop') }}</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🛒</text></svg>">
</head>
<body>
    <div id="app"></div>
    @php
        $css = glob(public_path('build/assets/index-*.css'));
        $js = glob(public_path('build/assets/index-*.js'));
    @endphp
    @if($css)
    <link rel="stylesheet" href="{{ asset('build/assets/' . basename($css[0])) }}">
    @endif
    @if($js)
    <script src="{{ asset('build/assets/' . basename($js[0])) }}"></script>
    @endif
</body>
</html>
