<?php

namespace App\Http\Controllers;

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
        $res = DB::select("SELECT * FROM user WHERE id in (?,?);", [1, 2]);
        foreach ($res as $user) {
            dump($user->name);
        }
    }
}
