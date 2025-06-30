<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Contracts\UserServiceInterface;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(
        public readonly UserServiceInterface $userService
    )
    {}

    /**
     * @return Response
     */
    public function index(): Response
    {
        return  Inertia::render('Admin/Users/Index', [
            'users' => $this->userService->index()
        ]);
    }
}
