<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function hello()
    {
        echo "HELLO WORLD!";
    }

    public function sql()
    {
        // $res = DB::select("SELECT * FROM user WHERE id in (?,?);", [1, 2]);
        // foreach ($res as $user) {
        //     dump($user->name);
        // }

        // $res = DB::table('user')->whereIn('id', [1, 2, 3])->get();
        $res = User::whereIn('id', [1, 2, 3])->get();
        foreach ($res as $user) {
            dump($user->name);
        }
    }
}
