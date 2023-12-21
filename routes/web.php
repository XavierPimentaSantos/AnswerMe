<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\QuestionCommentController;
use App\Http\Controllers\AnswerCommentController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\ForgetPasswordController;

use App\Http\Controllers\FAQController;

use App\Mail\MyTestEmail;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

Route::controller(QuestionController::class)->group(function () {
    Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions/create', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('/', [QuestionController::class, 'index'])->name('questions.index');
    Route::get('/questions/{id}', [QuestionController::class, 'show'])->name('questions.show');
    Route::delete('/questions/{question_id}/delete', [QuestionController::class, 'delete'])->name('questions.delete');
    Route::post('/questions/{question_id}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::post('/questions/{question_id}/toggle_follow', [QuestionController::class, 'toggleFollow'])->name('questions.toggleFollow');
    Route::post('/questions', [QuestionController::class, 'search'])->name('questions.search');
    Route::post('/', [QuestionController::class, 'filter'])->name('questions.filter');
    // route for increasing a question's score
    Route::post('/increase_score', [QuestionController::class, 'inc_score']);
    // route for decreasing a question's score
    Route::post('/decrease_score', [QuestionController::class, 'dec_score']);
});

Route::controller(AnswerController::class)->group(function () {
    Route::post('/questions/{question_id}/answer', [AnswerController::class, 'store'])->name('answers.store');
    Route::post('/questions/{question_id}/answer/{answer_id}/edit', [AnswerController::class, 'edit'])->name('answers.edit');
    Route::delete('/questions/{question_id}/answer/{answer_id}/delete', [AnswerController::class, 'delete'])->name('answers.delete');
    // route for validating an answer
    Route::post('/validate_answer', [AnswerController::class, 'validate_answer']);
    // route for increasing an answer's score
    Route::post('/increase_score_ans', [AnswerController::class, 'inc_score']);
    // route for decreasing an answer's score
    Route::post('/decrease_score_ans', [AnswerController::class, 'dec_score']);
});

Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/{username}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/{username}', [ProfileController::class, 'showUser'])->name('profile.showUser');
    Route::delete('/profile/{username}/delete', [ProfileController::class, 'delete'])->name('profile.delete');
});

Route::controller(AdminController::class)->group(function () {
    Route::get('/admin', [AdminController::class, 'show'])->name('admin.show');
    Route::post('/admin/submit', [AdminController::class, 'submit'])->name('admin.submit');
    Route::post('/admin/block-user/{username}', [AdminController::class, 'blockUser'])->name('admin.blockUser');
    Route::post('/admin/promote-user/{username}', [AdminController::class, 'promoteUser'])->name('admin.promoteUser');
    Route::post('/admin/unblock-user/{username}', [AdminController::class, 'unblockUser'])->name('admin.unblockUser');
    Route::post('/admin/demote-user/{username}', [AdminController::class, 'demoteUser'])->name('admin.demoteUser');
    Route::post('/admin/addTag', [AdminController::class, 'addTag'])->name('admin.addTag');
    Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
});

Route::controller(QuestionCommentController::class)->group(function () {
    Route::get('/questions/{question_id}/comments', [QuestionCommentController::class, 'index'])->name('questioncomment.index');
    Route::post('/questions/{question_id}/comment', [QuestionCommentController::class, 'store'])->name('questioncomment.store');
    Route::put('/questions/{question_id}/comment/{comment_id}/edit', [QuestionCommentController::class, 'edit'])->name('questioncomment.edit');
    Route::delete('/questions/{question_id}/comment/{comment_id}/delete', [QuestionCommentController::class, 'delete'])->name('questioncomment.delete');
});

Route::controller(AnswerCommentController::class)->group(function () {
    Route::get('/answers/{answer_id}/comments', [AnswerCommentController::class, 'index'])->name('answercomment.index');
    Route::post('/answers/{answer_id}/comment', [AnswerCommentController::class, 'store'])->name('answercomment.store');
    Route::put('/answers/{answer_id}/comment/{comment_id}/edit', [AnswerCommentController::class, 'edit'])->name('answercomment.edit');
    Route::delete('/answers/{answer_id}/comment/{comment_id}/delete', [AnswerCommentController::class, 'delete'])->name('answercomment.delete');
});

Route::post('/tag', [TagController::class, 'store'])->name('tag.store');
Route::delete('/tag/{tag_id}', [TagController::class, 'delete'])->name('tag.delete');
Route::put('/tag/{tag_id}', [TagController::class, 'edit'])->name('tag.edit');

// route for updating a question's tags
Route::post('/update_tags', [TagController::class, 'updateTags']);

Route::get('/faq', [FAQController::class, 'show'])->name('faq.show');

Route::get('/forgot-password', [ForgetPasswordController::class, 'forgetPassword'])->name('forget.password');
Route::post('/forgot-password', [ForgetPasswordController::class, 'forgetPasswordPost'])->name('forget.password.post');
Route::get('/reset-password/{token}', [ForgetPasswordController::class, 'resetPassword'])->name('reset.password');
Route::post('/reset-password', [ForgetPasswordController::class, 'resetPasswordPost'])->name('reset.password.post');

