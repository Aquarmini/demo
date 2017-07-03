<?php

namespace App\Http\Controllers;

use App\Jobs\Timer;
use App\User;
use Carbon\Carbon;
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

    public function timer()
    {
        // $job = (new Timer(time()))->delay(Carbon::now()->addSeconds(10));
        $job = new Timer(time());
        dispatch($job);
    }

    public function params($id, Request $request, $name)
    {
        dump($request);
        dump("ID " . $id);
        dump("Name " . $name);
    }

    public function calltest(Request $request)
    {
        $params = ['key' => 'key', 'request' => $request, 'val' => 'val',];
        $class = new \ReflectionClass(\App\Http\Controllers\IndexController::class);
        $method = $class->getMethod('callStatic');
        $fields = [];
        foreach ($method->getParameters() as $param) {
            $fields[] = $params[$param->getName()];
        }
        dump($method);
        dump($fields);
        static::callStatic(...$fields);
        // static::callStatic(...$params);
    }

    public static function callStatic($key, $val, Request $request)
    {
        dump($key);
        dump($val);
        dump($request);
    }
}
