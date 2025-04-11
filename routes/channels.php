<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Scholarship;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('chat', function ($user) {
    return Auth::check(); // Ensure the user is authenticated
});

Broadcast::channel('chat.{id}', function (User $user, int $scholarshipId) {
    return Auth::check();
});

Broadcast::channel('batch.{id}', function ($user, $id) {
    return Auth::check();
});

Broadcast::channel('staff.{id}', function ($user, $id) {
    return Auth::check();
});

Broadcast::channel('scholarship.{scholarshipId}', function ($user, $scholarshipId) {
    return $user->scholarships->contains($scholarshipId) ? ['id' => $user->id, 'name' => $user->name] : false;
});

Broadcast::channel('notifications.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

