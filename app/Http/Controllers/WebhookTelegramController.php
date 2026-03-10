<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookTelegramController extends Controller
{
    public function handle(Request $request)
    {
        // Obtener toda la información que envía Telegram
        $update = $request->all();

        // Para depuración, puedes guardar en el log
        \Log::info('Telegram update:', $update);

        // Verificar si hay un callback de botón inline (clic)
        if (isset($update['callback_query'])) {
            $callback = $update['callback_query'];
            $chatId = $callback['message']['chat']['id'];
            $callbackData = $callback['data'];

            // Aquí llamamos a un servicio que procesa la acción
            $responseText = "Recibimos tu clic: " . $callbackData;

            // Responder a Telegram
            $this->answerCallbackQuery($callback['id'], $responseText);
        }

        return response()->json(['status' => 'ok']);
    }

    private function answerCallbackQuery($callbackId, $text)
    {
        $token = env('TELEGRAM_BOT_TOKEN'); // tu token de BotFather
        $url = "https://api.telegram.org/bot{$token}/answerCallbackQuery";

        $postData = [
            'callback_query_id' => $callbackId,
            'text' => $text,
            'show_alert' => false
        ];

        // Enviar request a Telegram
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_exec($ch);
        curl_close($ch);
    }
}
