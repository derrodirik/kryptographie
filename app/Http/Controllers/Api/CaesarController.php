<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessCrack;
use App\Http\Requests\ProcessCrypt;
use App\Libs\Caesar;
use Illuminate\Http\Request;

class CaesarController extends Controller
{
	/**
	 * Handles encrypt Requests
	 * @param Request $request
	 * @return string
	 */
	public function encrypt(ProcessCrypt $request)
	{
		$request->validated();
		return response()->json((new Caesar)->encrypt($request->text, $request->key));
    }

	/**
	 * Handles decrypt Requests
	 * @param Request $request
	 * @return string
	 */
	public function decrypt(ProcessCrypt $request)
	{
		$request->validated();
		return response()->json((new Caesar)->decrypt($request->text, $request->key));
	}

	/**
	 * Handles Crack Requests
	 * @param Request $request
	 * @return int
	 */
	public function crack(ProcessCrack $request)
	{
		$request->validated();
		$key = (new Caesar)->crack($request->text);
		return response()->json((new Caesar)->decrypt($request->text, $key));
	}
}
