<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Setup;
use App\Livewire\Scoreboard;
use App\Livewire\Friends;
use App\Models\FriendInvitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/setup', Setup::class)->name('setup');
// Route::get('/scoreboard', Scoreboard::class)->name('scoreboard');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/scoreboard', Scoreboard::class)->name('scoreboard');
    Route::get('/setup', Setup::class)->name('setup');
    Route::get('/friends', Friends::class)->name('friends');
});

Route::get('/friend-invite/{token}', function ($token) {
    $invitation = FriendInvitation::where('token', $token)->firstOrFail();
    
    if (!Auth::check()) {
        // Store the invitation token and recipient email in session
        session(['friend_invitation_token' => $token]);
        
        // Flash data for the registration page
        return redirect()->route('register')
            ->with([
                'invitation_message' => 'Please register to use the app and confirm your friendship with ' . $invitation->sender->name,
                'email' => $invitation->recipient_email
            ]);
    }

    // Confirm the friendship
    $user = Auth::user();
    $sender = $invitation->sender;

    // Create mutual friendship
    $user->friends()->attach($sender->id);
    $sender->friends()->attach($user->id);

    // Share players between friends
    $senderPlayers = $sender->players;
    foreach ($senderPlayers as $player) {
        $user->sharedPlayers()->attach($player->id, ['user_id' => $sender->id]);
    }

    $userPlayers = $user->players;
    foreach ($userPlayers as $player) {
        $sender->sharedPlayers()->attach($player->id, ['user_id' => $user->id]);
    }

    $invitation->update(['accepted' => true]);

    return redirect()->route('scoreboard')->with('success', 'Friendship confirmed!');
});

Route::get('/scorecard', function () {
    return view('scorecard');
})->name('scorecard');

Route::get('/stats', function () {
    return view('stats');
})->name('stats');

Route::get('/test', function () {
    return view('test');
});

Route::post('/verify-recaptcha', function (Request $request) {
    $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        'secret' => config('services.recaptcha.secret_key'),
        'response' => $request->token,
    ]);

    if ($response->successful()) {
        $data = $response->json();
        return response()->json([
            'success' => $data['success'] && $data['score'] >= 0.5 && $data['action'] === $request->action
        ]);
    }

    return response()->json(['success' => false]);
})->middleware('throttle:6,1');