<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;
    var pusher = new Pusher('a58c34aee64b7da8dfcb', {
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
              'Accept': 'application/json',
            }
        },
        cluster: 'eu',
        forceTLS: true
    });

    var channel = pusher.subscribe('private-draft-player.123456789');
    // channel.bind('comment.created', function(data) {
    //     console.log('stringify data');
    //     console.log(JSON.stringify(data));
    // });
    channel.bind('player.choose', function(data) {
        console.log();
        console.log(data);
    });
</script>