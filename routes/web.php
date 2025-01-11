<?php

declare(strict_types=1);

use App\Livewire\CreateTicket;
use App\Livewire\ListTickets;
use App\Livewire\Success;
use App\Livewire\ViewIndex;
use App\Livewire\ViewPix;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', ViewIndex::class);
Route::get('/ticket', CreateTicket::class)->name('ticket');
Route::get('/lista', ListTickets::class)->name('lista');
Route::get('/pix', ViewPix::class)->name('pix');
Route::get('/success', Success::class)->name('success');
