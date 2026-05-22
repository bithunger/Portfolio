<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EducationEntryController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\NewsletterSubscriptionController;
use App\Http\Controllers\Admin\PasswordResetController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\PublicationController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortfolioController::class, 'index'])->name('portfolio.home');
Route::get('/projects/{project:slug}', [PortfolioController::class, 'show'])->name('portfolio.projects.show');
Route::get('/blog', [PortfolioController::class, 'blogIndex'])->name('portfolio.blog.index');
Route::get('/blog/{blogPost:slug}', [PortfolioController::class, 'showBlog'])->name('portfolio.blog.show');
Route::get('/contact', [ContactController::class, 'create'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:6,1')->name('contact.store');
Route::post('/newsletter', [NewsletterController::class, 'store'])->middleware('throttle:6,1')->name('newsletter.store');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])
    ->whereAlphaNumeric('token')
    ->name('newsletter.unsubscribe');

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::middleware('guest')->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/setup', [AuthController::class, 'showSetup'])->name('setup');
    Route::post('/setup', [AuthController::class, 'setup'])->name('setup.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/forgot-password', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'email'])->name('password.email');
    Route::get('/forgot-password/verify', [PasswordResetController::class, 'verifyForm'])->name('password.verify');
    Route::post('/forgot-password/verify', [PasswordResetController::class, 'verify'])->name('password.verify.store');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/admin/reset-password', [PasswordResetController::class, 'create'])->name('password.reset');
    Route::post('/admin/reset-password', [PasswordResetController::class, 'store'])->name('password.update');
});

Route::post('/admin/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('admin.logout');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('users', UserController::class)->except('show');
    Route::resource('projects', ProjectController::class)->except('show');
    Route::resource('services', ServiceController::class)->except('show');
    Route::resource('skills', SkillController::class)->except('show');
    Route::resource('experiences', ExperienceController::class)->except('show');
    Route::resource('education', EducationEntryController::class)
        ->parameters(['education' => 'education'])
        ->except('show');
    Route::resource('publications', PublicationController::class)->except('show');
    Route::resource('testimonials', TestimonialController::class)->except('show');
    Route::resource('blog', BlogPostController::class)
        ->parameters(['blog' => 'blogPost'])
        ->except('show');
    Route::resource('newsletter', NewsletterSubscriptionController::class)
        ->parameters(['newsletter' => 'newsletterSubscription'])
        ->only(['index', 'destroy']);

    Route::get('/messages', [ContactMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('messages.show');
    Route::patch('/messages/{contactMessage}/read', [ContactMessageController::class, 'markRead'])->name('messages.read');
    Route::delete('/messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');
});
