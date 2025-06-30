<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeeRequest;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateEmployeeRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
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

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Users/Create');
    }

    /**
     * @param StoreUserRequest $request
     * @return Response|RedirectResponse
     */
    public function store(StoreUserRequest $request): Response|RedirectResponse
    {
        try {
            $this->userService->store($request->toDto());

            return  redirect()->route('admin.users.index');
        }catch(\Exception $e){
            Log::error($e->getMessage());

            return back()->withErrors([
                "failed to get user"
            ]);
        }
    }

    /**
     * @param User $employee
     * @return Response
     */
    public function edit(User $user): Response
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user->load('roles')
        ]);
    }

    /**
     * @param UpdateUserRequest $request
     * @param $employeeSlug
     * @return Response|RedirectResponse
     */
    public function update(UpdateUserRequest $request, $employeeSlug): Response|RedirectResponse
    {
        try {
            $user = User::where('slug', $employeeSlug)->firstOrFail();
            $this->userService->update($request->toDto($user), $user);

            return redirect()->route('admin.users.index');
        }catch(\Exception $e){
            Log::error($e->getMessage());

            return back()->withErrors([
                "failed to get users"
            ]);
        }
    }
}
