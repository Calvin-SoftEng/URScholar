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

Broadcast::channel('chat.{scholarshipId}', function (User $user, int $scholarshipId) {
    return Auth::check();
});

Broadcast::channel('batch.{id}', function ($user, $id) {
    // Authorization logic for batch channels
    // Return true if the user is authorized to listen to this batch channel
    return $user->canAccessBatch($id); // implement this method in your User model
});

Broadcast::channel('staff.{id}', function ($user, $id) {
    // Authorization logic for staff channels
    // Return true if the user is authorized to listen to this staff channel
    return $user->canAccessStaff($id); // implement this method in your User model
});

Broadcast::channel('scholarship.{scholarshipId}', function ($user, $scholarshipId) {
    return $user->scholarships->contains($scholarshipId) ? ['id' => $user->id, 'name' => $user->name] : false;
});

Broadcast::channel('notifications.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

