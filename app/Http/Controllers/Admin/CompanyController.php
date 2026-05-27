<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $companies = User::where('role', User::ROLE_COMPANY)
            ->when(in_array($status, [User::STATUS_PENDING, User::STATUS_APPROVED, User::STATUS_REJECTED], true),
                fn ($q) => $q->where('status', $status))
            ->withCount('tours')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.companies.index', compact('companies', 'status'));
    }

    public function approve(User $company)
    {
        abort_unless($company->isCompany(), 404);

        $company->update(['status' => User::STATUS_APPROVED]);

        return back()->with('success', __('Company approved.'));
    }

    public function reject(User $company)
    {
        abort_unless($company->isCompany(), 404);

        $company->update(['status' => User::STATUS_REJECTED]);

        return back()->with('success', __('Company rejected.'));
    }
}
