import axios from 'axios';
import Echo from 'laravel-echo';
import './bootstrap';

const form = document.getElementById('form');
const inputMessage = document.getElementById('input-message');

form.addEventListener('submit', function (event){
    event.preventDefault()
    const userInput = inputMessage.value;

    axios.post('/chat-message', {
        message: userInput
    })
});

const channel = window.Echo.channel('public.chat.1');

channel.subscribed(() => {
    console.log('subscribed!')
}).listen('.chat-message', (event) => {
    console.log(event)
})