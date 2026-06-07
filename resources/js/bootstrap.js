window._ = require('lodash');

/**
 * Carregaremos a biblioteca axios HTTP que nos permite fazer facilmente requisições
 * ao backend Laravel. Esta biblioteca cuida automaticamente de enviar o token
 * CSRF como cabeçalho com base no valor do cookie de token "XSRF".
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo expõe uma API expressiva para se inscrever em canais e ouvir
 * eventos que são transmitidos pelo Laravel. Echo e transmissão de eventos
 * permite que sua equipe construa facilmente aplicações web robustas em tempo real.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
