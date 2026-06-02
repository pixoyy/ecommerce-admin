<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Authorization;
use App\Models\AuthorizationType;
use App\Models\Module;
use App\Models\ModuleGroup;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthorizationController extends Controller
{
    public function edit(Role $role): View
    {
        $groups = ModuleGroup::with(['modules' => fn ($q) => $q->orderBy('order')])->orderBy('id')->get();
        $authTypes = AuthorizationType::orderBy('id')->get();
        $currentAuths = Authorization::where('role_id', $role->id)
            ->get()
            ->groupBy('module_id')
            ->map(fn ($items) => $items->pluck('authorization_type_id')->toArray());

        return view('admin.roles.authorizations', compact('role', 'groups', 'authTypes', 'currentAuths'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $request->validate([
            'auth' => 'nullable|array',
            'auth.*' => 'array',
            'auth.*.*' => 'exists:authorization_types,id',
        ]);

        DB::transaction(function () use ($request, $role) {
            Authorization::where('role_id', $role->id)->delete();

            $authorizations = [];

            foreach ($request->input('auth', []) as $moduleId => $authTypeIds) {
                foreach ($authTypeIds as $authTypeId) {
                    $authorizations[] = [
                        'role_id' => $role->id,
                        'module_id' => $moduleId,
                        'authorization_type_id' => $authTypeId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($authorizations)) {
                Authorization::insert($authorizations);
            }
        });

        return redirect()->route('admin.roles.authorizations.edit', $role)
            ->with('success', 'Otorisasi berhasil disimpan.');
    }
}
