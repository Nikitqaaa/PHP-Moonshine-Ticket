<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;

final class ServerController extends Controller
{
    public function createToken(Server $server)
    {
        $token = $server->createToken($server->name, ['server'])->plainTextToken;

        return redirect()->back()->with('create', 'Сгенерирован новый токен авторизации для сервера ' . $server->name . ': ' . $token);
    }

    public function destroyToken(Server $server, Request $request)
    {
        $token = $server->tokens()->where('id', $request->get('token_id'))->first();
        $token->delete();

        return redirect()->back()->with('create', 'Токен успешно удалён');
    }

    public function destroyAll(Server $server)
    {
        $server->tokens()->delete();

        return redirect()->back()->with('create', 'Все токены успешно удалены');
    }
}
