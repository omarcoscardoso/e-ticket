<?php

declare(strict_types=1);

use App\Livewire\CreateTicket;
use App\Livewire\ListTickets;
use App\Livewire\ViewIndex;
use App\Livewire\ViewPix;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', ViewIndex::class);

Route::get('/ticket', CreateTicket::class);

Route::get('/lista', ListTickets::class);

Route::get('/pix', ViewPix::class);

// Route::get('/pix', function () {
//     return view('pix');
// });
