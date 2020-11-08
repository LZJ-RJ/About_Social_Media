<?php

namespace App\Http\Controllers;

use Hash;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
//TODO : Check you have included enough files.

class UserController extends Controller
{
    //TODO : These two functions can be merged to one function.
    public function teacherInformation()
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole('teacher') || $user->current_role != 'teacher') {
            return redirect(route('index'));
        }

        $id = '';
        $name = '';
        $email = '';

        if ($user) {
            $id = $user->id;
            $name = $user->name;
            $email = $user->email;
        }

        return view('myAccount.teacherIndex', compact(array('user', 'name',)));
    }

    public function studentInformation()
    {
        $user = Auth::user();

        if (!$user || $user->current_role != 'student' || !$user->hasRole('student')) {
            return redirect(route('index'));
        }
        $id = '';
        $name = '';
        $email = '';

        if ($user) {
            $id = $user->id;
            $name = $user->name;
            $email = $user->email;
        }

        return view('my-account.student-index', compact(array(
            'user', 'id', 'name', 'email'
        )));
    }
}