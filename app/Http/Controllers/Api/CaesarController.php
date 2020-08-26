<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Libs\Caesar;
use Illuminate\Http\Request;

class CaesarController extends Controller
{
	/**
	 * Handles encrypt Requests
	 * @param Request $request
	 * @return string
	 */
	public function encrypt(Request $request)
	{
		return response()->json((new Caesar)->encrypt($request->text, $request->key));
    }

	/**
	 * Handles decrypt Requests
	 * @param Request $request
	 * @return string
	 */
	public function decrypt(Request $request)
	{
		return response()->json((new Caesar)->decrypt($request->text, $request->key));
	}

	/**
	 * Handles Crack Requests
	 * @param Request $request
	 * @return int
	 */
	public function crack(Request $request)
	{
		$key = (new Caesar)->crack($request->text);
		return response()->json((new Caesar)->decrypt($request->text, $key));
	}
}
