<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        // $user = User::where('is_admin',0)->get();
        $users = DB::table('users as u')
                    ->where('u.is_admin',0)
                    ->select([
                      'u.id',
                      'u.name',
                      'u.email',
                      'u.created_at',
                    ])
                    ->get();
        // dd($users);

        return Inertia::render('User/Index',[
            'users' => $users,
        ]);
    }
}
