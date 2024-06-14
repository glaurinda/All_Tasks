<?php

use App\Http\Controllers\DomainController;

Route::get('/enter-domain', [DomainController::class, 'index']);
Route::get('/domains/check', [DomainController::class, 'checkDomain'])->name('domains.check');
Route::get('/domains', [DomainController::class, 'showDomains']);
Route::post('/scrape-domain', [DomainController::class, 'scrapeDomain']);

Route::post('/enter-domain', [DomainController::class, 'domainCheck']); // Traite le formulaire et vérifie le domaine



