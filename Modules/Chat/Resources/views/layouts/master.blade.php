<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Module Chat</title>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    {{-- Laravel Mix - CSS File --}}
    {{-- <link rel="stylesheet" href="{{ mix('css/chat.css') }}"> --}}
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('cbe947b09a127d1d6084', {
            cluster: 'mt1'
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            alert(JSON.stringify(data));
        });
    </script>

</head>

<body>
    @yield('content')

    {{-- Laravel Mix - JS File --}}
    {{-- <script src="{{ mix('js/chat.js') }}"></script> --}}
</body>

</html>
