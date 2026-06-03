<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PointController extends Controller
{
    public function index(Request $request): View
    {
        $query = UserPoint::with('user');

        if ($search = $request->get('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $userPoints = $query->orderByDesc('balance')->paginate(15)->withQueryString();

        return view('admin.points.index', compact('userPoints'));
    }

    public function show(User $user): View
    {
        $user->load('userPoints');

        $transactions = $user->pointTransactions()
            ->with('order')
            ->orderByDesc('created_at')
            ->paginate(20);

        $balance = $user->userPoints->first()->balance ?? 0;

        return view('admin.points.show', compact('user', 'transactions', 'balance'));
    }
}
