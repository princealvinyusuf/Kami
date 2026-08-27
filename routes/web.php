<?php

use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\Admin\ResourceController as AdminResourceController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/tentang-kami', [PublicController::class, 'about'])->name('about');
Route::get('/program-pelatihan', [PublicController::class, 'trainings'])->name('trainings.index');
Route::get('/program-pelatihan/{training:slug}', [PublicController::class, 'training'])->name('trainings.show');
Route::get('/sertifikasi', [PublicController::class, 'certifications'])->name('certifications.index');
Route::get('/sertifikasi/{certification:slug}', [PublicController::class, 'certification'])->name('certifications.show');
Route::get('/layanan', [PublicController::class, 'services'])->name('services.index');
Route::get('/layanan/{service:slug}', [PublicController::class, 'service'])->name('services.show');
Route::get('/trainer', [PublicController::class, 'trainers'])->name('trainers.index');
Route::get('/trainer/{trainer:slug}', [PublicController::class, 'trainer'])->name('trainers.show');
Route::get('/mitra', [PublicController::class, 'partners'])->name('partners');
Route::get('/portofolio', [PublicController::class, 'portfolio'])->name('portfolio');
Route::get('/pemagangan', [PublicController::class, 'apprenticeship'])->name('apprenticeship');
Route::get('/konsultan', [PublicController::class, 'consulting'])->name('consulting');
Route::get('/artikel', [PublicController::class, 'articles'])->name('articles.index');
Route::get('/artikel/{article:slug}', [PublicController::class, 'article'])->name('articles.show');
Route::get('/dokumen-legal/{legalDocument:slug}/berkas', [PublicController::class, 'legalDocumentAttachment'])->name('legal-documents.attachment');
Route::get('/kontak', [ContactController::class, 'create'])->name('contact');
Route::post('/kontak', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');
Route::get('/daftar/{type}/{slug?}', [RegistrationController::class, 'create'])
    ->whereIn('type', ['training', 'certification', 'consultation', 'internship'])
    ->name('registrations.create');
Route::post('/daftar/{type}/{slug?}', [RegistrationController::class, 'store'])
    ->whereIn('type', ['training', 'certification', 'consultation', 'internship'])
    ->middleware('throttle:5,1')
    ->name('registrations.store');
Route::get('/pencarian', [PublicController::class, 'search'])->name('search');
Route::get('/sitemap.xml', [PublicController::class, 'sitemap'])->name('sitemap');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('/admin/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/admin/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('throttle:6,1')->name('password.email');
    Route::get('/admin/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/admin/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', AdminDashboardController::class)->name('admin.dashboard');
    Route::get('/admin/pendaftaran', [AdminRegistrationController::class, 'index'])->name('admin.registrations.index');
    Route::get('/admin/pendaftaran-export.csv', [AdminRegistrationController::class, 'export'])->name('admin.registrations.export');
    Route::patch('/admin/pendaftaran/{registration}/status', [AdminRegistrationController::class, 'update'])->name('admin.registrations.update');
    Route::get('/admin/pendaftaran/{registration}/dokumen', [AdminRegistrationController::class, 'document'])->name('admin.registrations.document');
    Route::get('/admin/pesan', [AdminContactMessageController::class, 'index'])->name('admin.messages.index');
    Route::get('/admin/pesan/{message}', [AdminContactMessageController::class, 'show'])->name('admin.messages.show');
    Route::patch('/admin/pesan/{message}', [AdminContactMessageController::class, 'update'])->name('admin.messages.update');
    Route::delete('/admin/pesan/{message}', [AdminContactMessageController::class, 'destroy'])->name('admin.messages.destroy');
    Route::get('/admin/pengaturan', [AdminSettingsController::class, 'edit'])->name('admin.settings.edit');
    Route::put('/admin/pengaturan', [AdminSettingsController::class, 'update'])->name('admin.settings.update');
    Route::get('/admin/cms/{resource}', [AdminResourceController::class, 'index'])->name('admin.resources.index');
    Route::get('/admin/cms/{resource}/tambah', [AdminResourceController::class, 'create'])->name('admin.resources.create');
    Route::post('/admin/cms/{resource}', [AdminResourceController::class, 'store'])->name('admin.resources.store');
    Route::get('/admin/cms/{resource}/{id}/edit', [AdminResourceController::class, 'edit'])->whereNumber('id')->name('admin.resources.edit');
    Route::put('/admin/cms/{resource}/{id}', [AdminResourceController::class, 'update'])->whereNumber('id')->name('admin.resources.update');
    Route::delete('/admin/cms/{resource}/{id}', [AdminResourceController::class, 'destroy'])->whereNumber('id')->name('admin.resources.destroy');
    Route::get('/admin/cms/{resource}/{id}/berkas/{field}', [AdminResourceController::class, 'download'])->whereNumber('id')->name('admin.resources.download');
    Route::post('/admin/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
