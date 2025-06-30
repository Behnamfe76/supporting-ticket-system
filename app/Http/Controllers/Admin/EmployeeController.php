<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeeRequest;
use App\Http\Requests\Admin\UpdateEmployeeRequest;
use App\Models\User;
use App\Services\Contracts\EmployeeServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class EmployeeController extends Controller
{
    public function __construct(
        public readonly EmployeeServiceInterface $employeeService
    )
    {}

    /**
     * @return Response|RedirectResponse
     */
    public function index(): Response|RedirectResponse
    {
        try {
            return  Inertia::render('Admin/Employees/Index', [
                'employees' => $this->employeeService->index()
            ]);
        }catch(\Exception $e){
            Log::error($e->getMessage());

            return back()->withErrors([
                "failed to get employees"
            ]);
        }
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Employees/Create');
    }

    /**
     * @param StoreEmployeeRequest $request
     * @return Response|RedirectResponse
     */
    public function store(StoreEmployeeRequest $request): Response|RedirectResponse
    {
        try {
            $this->employeeService->store($request->toDto());

            return  redirect()->route('admin.employees.index');
        }catch(\Exception $e){
            Log::error($e->getMessage());

            return back()->withErrors([
                "failed to get employees"
            ]);
        }
    }

    /**
     * @param User $employee
     * @return Response
     */
    public function edit(User $employee): Response
    {
        return Inertia::render('Admin/Employees/Edit', [
            'employee' => $employee->load('roles')
        ]);
    }

    /**
     * @param UpdateEmployeeRequest $request
     * @param $employeeSlug
     * @return Response|RedirectResponse
     */
    public function update(UpdateEmployeeRequest $request, $employeeSlug): Response|RedirectResponse
    {
        try {
            $user = User::where('slug', $employeeSlug)->firstOrFail();
            $this->employeeService->update($request->toDto($user), $user);

            return redirect()->route('admin.employees.index');
        }catch(\Exception $e){
            Log::error($e->getMessage());

            return back()->withErrors([
                "failed to get employees"
            ]);
        }
    }
}
