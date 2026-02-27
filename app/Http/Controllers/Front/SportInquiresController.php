<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use App\Models\SportInquires;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Mail\SportInquiryMail;
use Illuminate\Support\Facades\Mail;
class SportInquiresController extends Controller
{
     public function sportInquires(Request $request)
    {
          $validator = Validator::make($request->all(), [
                'category' => 'required',
                'sport_game_txt'    => 'required',
                'state'    => 'required',
                'email'    => 'required|email'
            ]);

            if ($validator->fails()) {
                return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

            $inquiry=sportInquires::create([
                'category' => $request->category,
                'sport_game'    => $request->sport_game_txt,
                'state'    => $request->state,
                'email'    => $request->email
            ]);
        Mail::to('kerry@athleat.com')->send(new SportInquiryMail($inquiry->toArray()));
        return response()->json([
                'success' => true,
            'message' => 'Thanks for subscribe'
        ]);
    }
    
}
