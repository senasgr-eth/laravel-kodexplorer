<?php

namespace Senasgr\KodExplorer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Senasgr\KodExplorer\Facades\KodExplorer;

class KodExplorerController extends Controller
{
    public function index()
    {
        return view('kodexplorer::index');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'path' => 'required|string',
        ]);

        $path = $request->file('file')->store(
            $request->input('path'),
            'kodexplorer'
        );

        return response()->json(['path' => $path]);
    }

    public function download($path)
    {
        return Storage::disk('kodexplorer')->download($path);
    }

    public function delete(Request $request)
    {
        $request->validate(['path' => 'required|string']);
        Storage::disk('kodexplorer')->delete($request->input('path'));
        return response()->json(['success' => true]);
    }

    public function rename(Request $request)
    {
        $request->validate([
            'from' => 'required|string',
            'to' => 'required|string',
        ]);

        Storage::disk('kodexplorer')->move(
            $request->input('from'),
            $request->input('to')
        );

        return response()->json(['success' => true]);
    }

    public function move(Request $request)
    {
        $request->validate([
            'from' => 'required|string',
            'to' => 'required|string',
        ]);

        Storage::disk('kodexplorer')->move(
            $request->input('from'),
            $request->input('to')
        );

        return response()->json(['success' => true]);
    }

    public function copy(Request $request)
    {
        $request->validate([
            'from' => 'required|string',
            'to' => 'required|string',
        ]);

        Storage::disk('kodexplorer')->copy(
            $request->input('from'),
            $request->input('to')
        );

        return response()->json(['success' => true]);
    }

    public function createFolder(Request $request)
    {
        $request->validate(['path' => 'required|string']);
        Storage::disk('kodexplorer')->makeDirectory($request->input('path'));
        return response()->json(['success' => true]);
    }

    public function share(Request $request)
    {
        $request->validate(['path' => 'required|string']);
        
        $share = KodExplorer::share(
            $request->input('path'),
            $request->input('expires_at')
        );

        return response()->json($share);
    }
}
