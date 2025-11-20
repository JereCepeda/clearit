<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AgentTicketController;
use App\Http\Controllers\CustomertTicketController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:user'])->prefix('customer/tickets')->name('customer.tickets.')->group(function () {
    Route::get('/', [CustomertTicketController::class, 'index'])->name('index');
    Route::get('/create', [CustomertTicketController::class, 'create'])->name('create');
    Route::post('/', [CustomertTicketController::class, 'store'])->name('store');
    Route::patch('/{ticket}', [CustomertTicketController::class, 'update'])->name('update');
    Route::delete('/{ticket}', [CustomertTicketController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth', 'role:agent|admin'])->prefix('agent/tickets')->name('agent.tickets.')->group(function () {
    Route::get('/', [AgentTicketController::class, 'index'])->name('index');
    Route::patch('/{id}/take', [AgentTicketController::class, 'take'])->name('take');
    Route::patch('/{id}/complete', [AgentTicketController::class, 'complete'])->name('complete');
    Route::post('/{id}/request-documents', [AgentTicketController::class, 'requestDocuments'])->name('request-documents');
    Route::get('/{ticket}/download/{document}', [AgentTicketController::class, 'downloadDocument'])->name('download');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin/tickets')->name('admin.tickets.')->group(function () {
    Route::patch('/{ticket}/assign', [AgentTicketController::class, 'assign'])->name('assign');
    Route::get('/{ticket}/edit', [AgentTicketController::class, 'edit'])->name('edit');
});

require __DIR__.'/auth.php';


