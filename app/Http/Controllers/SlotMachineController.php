<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SlotMachineController extends Controller
{
    public function index(Request $request)
    {

        if (!$request->session()->has('player_state')) {
            Session::put(['player_state' => 1, 'user_credit' =>10]);
        }

        return view('slotmachine');
    }

    public function startSpin(Request $request)
    {
        if ($request->session()->get('user_credit') < 1) {
            return response()->json([
                'success' => false,
                'message' => 'You dont have enough credit'
            ]);
        }
        return response()->json([
            'success' => true,
            'data' => $request->session()->decrement('user_credit')
        ]);
    }

    public function slotResult(Request $request)
    {
        $roll_again_chance = rand(1,100);

        $user_credit = $request->session()->get('user_credit');

        if (($request->first_slot_value === $request->second_slot_value) && ($request->second_slot_value === $request->third_slot_value)) {
            if ($user_credit >= 40 && $user_credit <= 60) {
                if ($roll_again_chance < 30){
                    return response()->json([
                        'success' => false,
                        'roll_again_chance' =>$roll_again_chance,
                        'data' => $request->session()->get('user_credit')
                    ]);
                }
            }
            elseif($user_credit > 60){
                if ($roll_again_chance < 60){
                    return response()->json([
                        'success' => false,
                        'roll_again_chance' =>$roll_again_chance,
                        'data' => $request->session()->get('user_credit')
                    ]);
                }
            }

            if ($request->first_slot_value === 'cherry') {
                $request->session()->increment('user_credit', $incrementBy = 10);
            }
            if ($request->first_slot_value === 'lemon') {
                $request->session()->increment('user_credit', $incrementBy = 20);
            }
            if ($request->first_slot_value === 'orange') {
                $request->session()->increment('user_credit', $incrementBy = 30);
            }
            if ($request->first_slot_value === 'watermelon') {
                $request->session()->increment('user_credit', $incrementBy = 40);
            }

        }
        return response()->json([
            'success' => true,
            'roll_again_chance' =>$roll_again_chance,
            'data' => $request->session()->get('user_credit')
        ]);
    }
}
