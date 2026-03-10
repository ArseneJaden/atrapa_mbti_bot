<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookTelegramController extends Controller
{
    public function handle(Request $request)
    {
        $update = $request->all();
        \Log::info('Telegram update:', $update);

        // Manejar callback de botón inline
        if (isset($update['callback_query'])) {
            $callback = $update['callback_query'];
            $chatId = $callback['message']['chat']['id'];
            $callbackData = $callback['data'];

            $this->answerCallbackQuery($callback['id'], "Recibimos tu clic: " . $callbackData);
        }

        // Manejar mensajes normales (como /start)
        if (isset($update['message'])) {
            $chatId = $update['message']['chat']['id'];
            $text = $update['message']['text'] ?? '';

            if ($text === '/start') {
                $this->sendMessage($chatId, "¡Hola! Bienvenido al bot.");
            }
        }

        return response()->json(['status' => 'ok']);
    }

    private function answerCallbackQuery($callbackId, $text)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$token}/answerCallbackQuery";

        $postData = [
            'callback_query_id' => $callbackId,
            'text' => $text,
            'show_alert' => false
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_exec($ch);
        curl_close($ch);
    }

    private function sendMessage($chatId, $text)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        $postData = [
            'chat_id' => $chatId,
            'text' => $text
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_exec($ch);
        curl_close($ch);
    }
}
