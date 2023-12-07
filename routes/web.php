<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\TagController;


use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\AdminController;


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
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('/', [QuestionController::class, 'index'])->name('questions.index');
    Route::get('/questions/{id}', [QuestionController::class, 'show'])->name('questions.show');
    Route::delete('/questions/{question_id}/delete', [QuestionController::class, 'delete'])->name('questions.delete');
    Route::post('/questions/{question_id}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
});

Route::controller(AnswerController::class)->group(function () {
    Route::post('/questions/{question_id}/answer', [AnswerController::class, 'store'])->name('answers.store');
    Route::post('/questions/{question_id}/answer/{answer_id}/edit', [AnswerController::class, 'edit'])->name('answers.edit');
    Route::delete('/questions/{question_id}/answer/{answer_id}/delete', [AnswerController::class, 'delete'])->name('answers.delete');

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
    Route::post('/admin/unblock-user/{username}', [AdminController::class, 'unblockUser'])->name('admin.unblockUser');
});

Route::post('/tag', [TagController::class, 'store'])->name('tag.store');
Route::delete('/tag/{tag_id}', [TagController::class, 'delete'])->name('tag.delete');
Route::put('/tag/{tag_id}', [TagController::class, 'edit'])->name('tag.edit');

// route for updating a question's tags
Route::post('/update_tags', [TagController::class, 'updateTags']);

// route for validating an answer
Route::post('/validate_answer', [AnswerController::class, 'validate_answer']);
// route for increasing a question's score
Route::post('/increase_score', [QuestionController::class, 'inc_score']);
// route for decreasing a question's score
Route::post('/decrease_score', [QuestionController::class, 'dec_score']);
>>>>>>> routes/web.php

/*

// M01
// R101
Route::get('/login', function() { // retorna o form de login
    return view('login_form'); // é preciso criar uma view chamada login_form.blade.php
});
// R102
Route::post('/login', function(Request $request) { // cria nova sessão para o User
    // efetuar login com as credenciais do $request
});
// R103
Route::post('/logout', function(Request $request) { // termina a sessão atual
    // verificar se as credenciais do $request são as do User atualmente logged-in, e terminar a sessão
});
Route::get('/signup', function() { // retorna o form de criação de conta
    return view('signup_form'); // é preciso criar uma view chamada signup_form.blade.php
});
Route::post('/sigup', function(Request $request) { // cria nova conta e retorna o perfil do novo utilizador
    // cria nova conta se os dados no $request estiverem corretos
    return view('profile', [passar dados aqui]);
    });
    // R105
    Route::post('/recover_credentials', function(Request $request) { // efetua os procedimentos de recuperação de senha
        // verificar se as credenciais (email) enviadas no $request são válidas, e efetuar procedimentos de recuperação de senha
    });
    // R106
    Route::get('/users/{user_id}', function(int $user_id) { // retorna o perfil do utilizador especificado
        // obter dados do utilizador a colocar no perfil
        return view('profile', [passar esses dados aqui]); // é preciso criar uma view chamada profile.blade.php
    });
    Route::get('users/{user_id}/edit', function(int $user_id) { // retorna a página de edição de perfil
        // obter dados do utilizador
        return view('edit_profile', [passar esses dados aqui]); // é preciso criar uma view chamada edit_profile.blade.php
    });
    // R107
    Route::put('/users/{user_id}/edit', function(Request $request, int $user_id) { // edita os dados do utilizador na base de dados
        //obter dados do utilizador que vão ser atualizados
        // chamar uma função qualquer do UserController para atualizar esses dados
        return view('profile', []); // depois de efetuar as alterações, retorna à pagina de perfil
    });
    
    // M02
    
    Route::post('/questions/{question_id}/follow', function(Request $request, int $question_id) { // põe o User a seguir a Questão
        // criar nova relação User-Question
        // acho que não precisa de retornar a view
    });
    
    Route::delete('/questions/{question_id}/unfollow', function(Request $request, int $question_id) {
        // elimina a relação User-Question
    });
    
    Route::post('/users/{target_user_id}/follow', function(Request $request, int $target_user_id) {
        // cria nova relação User-User
    });
    
    Route::delete('users/{target_user_id}/unfollow', function(Request $request, int $target_user_id) {
        // elimina a relação User-User
    });
    
    Route::post('tag/{tag_id}/follow', function(Request $request, int $tag_id) {
        // cria nova relação User-Tag
    });
    
    Route::delete('tag/{tag_id}/unfollow', function(Request $request, int $tag_id) {
        // elimina a relação User-Tag
    });
    
    Route::post('questions/{question_id}/vote', function(Request $request, int $question_id) {
        // cria nova entrada na tabela post_votes
        // valor do voto (1 ou -1) está no $request?
    });
    
    Route::delete('questions/{question_id}/vote', function(Request $request, int $question_id) {
        // elimina a entrada na tabela post_votes
    });
    
    // M03
    
    Route::post('questions', function(Request $request) {
        // adiciona nova Question à base de dados
        return view('question', [parâmetros]); // é preciso criar uma view question.blade.php que vai incluir as respostas e tudo
    });
    
    Route::get('questions/{question_id}', function(int $question_id) {
        return view('question', [parâmetros]);
    });
    
    Route::put('questions/{question_id}', function(Request $request, int $question_id) {
        // editar questão
    });
    
    Route::delete('question/{question_id}', function(Request $request, int $question_id) {
        // elimina questão da base de dados
    });
    
    Route::patch('question/{question_id}', function(Request $request, int $question_id) {
        // altera as tags da questão
    });
    
    // M04
    
    Route::post('questions/{question_id}/answers', function(Request $request, int $question_id) {
        // adiciona nova Answer à BD ligada à dita Question
    });
    
    Route::put('questions/{question_id}/answers/{answer_id}', function(Request $request, int $question_id, int $answer_id) {
        // editar a resposta
    });
    
    Route::delete('questions/{question_id}/answers/{answer_id}', function(Request $request, int $question_id, int $answer_id) {
        // eliminar a resposta da base de dados
    });
    
    Route::patch('questions/{question_id}/answers/{answer_id}', function(Request $request, int $question_id, int $answer_id) {
        // validar a resposta
    });
    
    // M05
    
    Route::post('questions/{question_id}/comments', function(Request $request, int $question_id) {
        // adiciona novo comentário para uma Question na BD
    });
    
    Route::post('questions/{question_id}/answers/{answer_id}/comments', function(Request $request, int $question_id, int $answer_id) {
        // adiciona novo comentário para uma Answer na BD
    });
    
    Route::put('questions/{question_id}/comments/{comment_id}', function(Request $request, int $question_id, int $comment_id) {
        // edita comentário
    });
    
    Route::delete('questions/{question_id}/comments/{comment_id}', function(Request $request, int $question_id, int $comment_id) {
        // elimina comentário
    });
    
    Route::put('questions/{question_id}/answers/{answer_id}/comments/{comment_id}', function(Request $request, int $question_id, int $answer_id, int $comment_id) {
        // edita comentário
    });
    
    Route::delete('questions/{question_id}/answers/{answer_id}/comments/{comment_id}', function(Request $request, int $question_id, int $answer_id, int $comment_id) {
        // edita comentário
    });
    
    // M06
    
    Route::get('/search', function(Request $request) {
        // retorna os resultados da pesquisa
    });
    
    // M07
    
    Route::get('/notifications', function(Request $request) {
        // obter notificações
    });
    
    Route::post('/notifications/new-follower', function(Request $request) {
        // adiciona nova notificação do tipo notification_users e mais entradas nas tabelas dependentes desta
    });
    
    Route::post('/notifications/new-comment', function(Request $request) {
        // adiciona nova notificação do tipo notification_comments e mais entradas nas tabelas dependentes desta
    });
    
    Route::post('/notifications/new-vote', function(Request $request) {
        // adiciona nova notificação do tipo notification_votes e mais entradas nas tabelas dependentes desta
    });
    
    Route::post('/notifications/post-deleted', function(Request $request) {
        // adiciona nova notificação do tipo notification_deletes e mais entradas nas tabelas dependentes desta
    });
    

*/
