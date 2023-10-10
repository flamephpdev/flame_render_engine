<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Demo Rendering</title>
</head>
<body>
     <h1>Hi {{ $name }}!</h1>

     @if($needHelp)
          <a href="https://flamephp.mrtn.vip/docs/v1/views/getting-started">
               Documentation (Not everything the same, but you will be fine with it)
          </a>
     @endif
</body>
</html>