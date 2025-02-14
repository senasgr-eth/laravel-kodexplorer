<?php

use Illuminate\Support\Facades\Route;
use Senasgr\KodExplorer\Http\Controllers\KodExplorerController;

Route::middleware(config('kodexplorer.middleware', ['web', 'kodexplorer.auth']))
    ->prefix(config('kodexplorer.path', 'kodexplorer'))
    ->group(function () {
        Route::get('/', [KodExplorerController::class, 'index'])->name('kodexplorer.index');
        Route::post('upload', [KodExplorerController::class, 'upload'])->name('kodexplorer.upload');
        Route::post('delete', [KodExplorerController::class, 'delete'])->name('kodexplorer.delete');
        Route::post('rename', [KodExplorerController::class, 'rename'])->name('kodexplorer.rename');
        Route::post('move', [KodExplorerController::class, 'move'])->name('kodexplorer.move');
        Route::post('copy', [KodExplorerController::class, 'copy'])->name('kodexplorer.copy');
        Route::post('folder/create', [KodExplorerController::class, 'createFolder'])->name('kodexplorer.folder.create');
        Route::get('download/{path}', [KodExplorerController::class, 'download'])->name('kodexplorer.download');
        Route::post('share', [KodExplorerController::class, 'share'])->name('kodexplorer.share');
    });
