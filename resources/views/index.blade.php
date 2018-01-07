<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="has-navbar-fixed-top">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
  <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
  @if (env('WEBMASTER_TOOL_ID'))
  <meta name="google-site-verification" content="{!! env('WEBMASTER_TOOL_ID') !!}" />
  @endif
  <title>{{ config('app.name') }}</title>

  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
  <div id="app"></div>

  @include('scripts')
</body>
</html>
