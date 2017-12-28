import Echo from 'laravel-echo'

window.Pusher = require('pusher-js')

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'b197dc464f07501a56b9',
    cluster: 'ap1',
    encrypted: true,
});
