<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Developer docs</title>
    <script>
        var route_prefix = '{{ $routePrefix }}';
    </script>
    <style>
        #app {
            overflow: hidden;
        }
        @foreach($css as $file)
            {!! file_get_contents($file) !!}
        @endforeach
    </style>
    @if(!empty($customCss))
        @foreach($customCss as $css)
            <link href="{{ $css }}" rel="stylesheet">
        @endforeach
    @endif
</head>
<body>
<noscript><strong>We're sorry but composer-developer-docs doesn't work properly without JavaScript enabled. Please
        enable it to continue.</strong></noscript>
<div id="app"></div>
<script>
    @foreach($js as $file)
        {!! file_get_contents($file) !!}
    @endforeach
</script>
@if(!empty($customJs))
    @foreach($customJs as $js)
        <script src="{{ $js }}"></script>
    @endforeach
@endif
</body>
</html>
