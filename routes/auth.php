<?php

use App\Http\Actions\Auth\AcceptTerms;
use App\Http\Actions\Auth\LoginGuest;
use App\Http\Actions\Auth\LogoutUser;
use App\Http\Actions\Auth\RejectTerms;
use App\Http\Actions\Auth\ResetPassword;
use App\Http\Actions\Auth\SendPasswordResetLink;
use App\Http\Actions\Auth\ShowAcceptTermsPage;
use App\Http\Actions\Auth\ShowLoginPage;
use App\Http\Actions\Auth\ShowPasswordResetLinkPage;
use App\Http\Actions\Auth\ShowPasswordResetPage;
use App\Http\Actions\Auth\ShowRegisterGuestPage;

$router->get('/login', ShowLoginPage::class)
    ->middleware('guest')
    ->name('login');

$router->post('/login', LoginGuest::class)
    ->middleware('guest');

$router->post('/logout', LogoutUser::class)
    ->middleware('auth')
    ->name('logout');

$router->get('/accept-terms', ShowAcceptTermsPage::class)
    ->middleware(['auth', 'saned.not-registered-in-service'])
    ->name('accept-terms');

$router->post('/accept-terms', AcceptTerms::class)
    ->middleware(['auth', 'saned.not-registered-in-service'])
    ->name('accept-terms.accept');

$router->delete('/accept-terms', RejectTerms::class)
    ->middleware(['auth', 'saned.not-registered-in-service'])
    ->name('accept-terms.reject');

$router->get('/register', ShowRegisterGuestPage::class)
    ->middleware('guest')
    ->name('register');

$router->get('/forgot-password', ShowPasswordResetLinkPage::class)
    ->middleware('guest')
    ->name('password.request');

$router->post('/forgot-password', SendPasswordResetLink::class)
    ->middleware('guest')
    ->name('password.email');

$router->get('/reset-password/{token}', ShowPasswordResetPage::class)
    ->middleware('guest')
    ->name('password.reset');

$router->post('/reset-password', ResetPassword::class)
    ->middleware('guest')
    ->name('password.update');
