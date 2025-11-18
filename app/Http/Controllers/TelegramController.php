<?php

namespace App\Http\Controllers;

use App\Services\Telegram\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    protected TelegramService $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    public function webhook(Request $request)
    {

        Log::info('Telegram update', $request->all());
        $update = $request->all();
        $this->telegram->handleUpdate($update);
        return response()->json(['ok' => true]);
    }
}
