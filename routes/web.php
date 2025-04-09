<?php

declare(strict_types=1);

use App\Livewire\CreateTicket;
use App\Livewire\CreateTicketJovemPcat;
use App\Livewire\LsTicket;
use App\Livewire\RelatorioTickets;
use App\Livewire\Success;
use App\Livewire\JovemPcat;
use App\Livewire\ViewPix;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', ViewIndex::class);
Route::get('/', JovemPcat::class);
Route::get('/ticket', CreateTicket::class)->name('ticket');
Route::get('/relatorio', RelatorioTickets::class)->name('relatorio');
Route::get('/pix', ViewPix::class)->name('pix');
Route::get('/success', Success::class)->name('success');
Route::get('/jovem', JovemPcat::class)->name('jovempcat');
Route::get('/ticketjovem', CreateTicketJovemPcat::class)->name('ticketjovem');
Route::get('/lsticket/{inscricaoEventoId}/{inscricaoCpf}', LsTicket::class)->name('lsticket');
