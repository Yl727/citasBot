<?php
use App\Http\Controllers\BotManController;
use App\Http\Conversations\reservarcita;

$botman = resolve('botman');

$botman->hears('/start', function ($bot) {
    $bot->reply('Hola soy tu bot  amigo que te ayudara a solicitar una cita');
});
$botman->hears('Hola', function ($bot) {
    $bot->reply('Hola soy tu bot  amigo que te ayudara a solicitar una cita');
});
$botman->hears('reservar', function ($bot) {
    $bot->reply('Esperando');
    $bot->startConversation(new reservarcita());
})->stopsConversation();
$botman->hears('Start conversation', BotManController::class.'@startConversation');
