<?php

namespace App\Http\Controllers;

use App\BotMan\Conversations\DeviceSelectionConversation;
use Illuminate\Http\Request;

class BotManController extends Controller
{
    public function handle(Request $request)
    {
        $botman = app('botman');

        $botman->hears('/start', function ($bot) {
            $bot->startConversation(new DeviceSelectionConversation());
        });

        $botman->listen();
    }
}
