use App\Http\Controllers\ImageController;

Route::get('/', function () {
    return view('images');
});

Route::get('/images', [ImageController::class, 'index']);
Route::get('/load-more-data', [ImageController::class, 'loadMoreData']);
Route::post('/upload-image', [ImageController::class, 'uploadImage'])->name('uploadImage');
