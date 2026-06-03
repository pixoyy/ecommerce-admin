<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FileStorage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FileStorageController extends Controller
{
    public function index(Request $request): View
    {
        $files = FileStorage::orderByDesc('created_at')->paginate(15);

        return view('admin.file-storages.index', compact('files'));
    }
}
