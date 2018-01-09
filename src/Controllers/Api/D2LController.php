<?php

namespace SmithAndAssociates\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SmithAndAssociates\LaravelValence\Helper\D2LHelper;

class D2LController extends Controller
{
	public function index()
	{
		return resolve('D2LHelper')->test();
	}
}