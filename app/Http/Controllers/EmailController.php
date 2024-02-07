<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\NotificationEmail;

class EmailController extends Controller
{
    public function registrationSuccessNotification(User $user) {
        $name = "Ian Albano";
        $email = "i.albano.515439@umindanao.edu.ph";
        Mail::to($email)->send(new NotificationEmail($name));
        return redirect('/admin/user');
    }
}
