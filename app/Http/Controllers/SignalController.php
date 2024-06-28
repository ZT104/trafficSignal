<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SignalController extends Controller
{
    public function index()
    {
        return view('signals');
    }

    public function start(Request $request)
    {
        $request->validate([
            'sequence' => 'required|array',
            'green_interval' => 'required|integer|min:1',
            'yellow_interval' => 'required|integer|min:1',
        ]);

        $sequence = $request->input('sequence');
        $greenInterval = $request->input('green_interval');
        $yellowInterval = $request->input('yellow_interval');

        Session::put('signal_data', [
            'sequence' => $sequence,
            'green_interval' => $greenInterval,
            'yellow_interval' => $yellowInterval,
            'running' => true,
        ]);

        return response()->json(['status' => 'started']);
    }

    public function stop()
    {
        Session::forget('signal_data');

        return response()->json(['status' => 'stopped']);
    }
}
