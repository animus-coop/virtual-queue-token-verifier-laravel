use App\Http\Controllers\TestTokenVerifierController;

Route::get('/test-token-verifier', [TestTokenVerifierController::class, 'test']); 