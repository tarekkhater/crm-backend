<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Events\TradeUpdated;
use App\Http\Controllers\User\Auth\LoginController;
Route::get('/autologin', [LoginController::class, 'autoLogin']);

Broadcast::routes(['middleware' => ['auth:sanctum']]);


Route::get('/broadcast-test', function () {
    event(new TradeUpdated(68, [
        'id' => 1,
        'status' => 'open',
        'price' => 1200,
    ]));

    return 'ok';
});

// Helper function to process HTML content from old folder
function processOldHtml($htmlPath, $scrollToSection = null) {
    if (!file_exists($htmlPath)) {
        return null;
    }
    
    $content = file_get_contents($htmlPath);
    
    // Fix asset paths to work from /old/assets/ - handle all variations
    // Fix href attributes (for CSS, icons, etc)
    $content = preg_replace('/href=["\']assets\//i', 'href="/old/assets/', $content);
    $content = preg_replace("/href=['\"]assets\//i", 'href="/old/assets/', $content);
    
    // Fix src attributes (for images, scripts, etc)
    $content = preg_replace('/src=["\']assets\//i', 'src="/old/assets/', $content);
    $content = preg_replace("/src=['\"]assets\//i", 'src="/old/assets/', $content);
    
    // Fix CSS url() functions
    $content = preg_replace('/url\(["\']?assets\//i', 'url(/old/assets/', $content);
    $content = preg_replace("/url\(['\"]?assets\//i", 'url(/old/assets/', $content);
    
    // Handle CSS @import statements
    $content = preg_replace('/(@import\s+["\'])assets\//i', '$1/old/assets/', $content);
    $content = preg_replace('/(@import\s+[\'"])assets\//i', '$1/old/assets/', $content);
    
    // Fix navigation links to use Laravel routes - handle multiple formats
    $content = str_replace('href="index.html"', 'href="/"', $content);
    $content = str_replace("href='index.html'", "href='/'", $content);
    $content = str_replace('href="index.html#about"', 'href="/about"', $content);
    $content = str_replace("href='index.html#about'", "href='/about'", $content);
    $content = str_replace('href="index.html#contact"', 'href="/contact"', $content);
    $content = str_replace("href='index.html#contact'", "href='/contact'", $content);
    $content = str_replace('href="index.html#faq"', 'href="/faq"', $content);
    $content = str_replace("href='index.html#faq'", "href='/faq'", $content);
    $content = preg_replace('/href=["\']index\.html#/', 'href="/#', $content);
    
    // Update other page links to work with routes (if needed)
    $content = str_replace('href="accounts.html"', 'href="/old/accounts.html"', $content);
    $content = str_replace('href="vip.html"', 'href="/old/vip.html"', $content);
    $content = str_replace('href="education.html"', 'href="/old/education.html"', $content);
    $content = str_replace('href="art1.html"', 'href="/old/art1.html"', $content);
    $content = str_replace('href="art2.html"', 'href="/old/art2.html"', $content);
    $content = str_replace('href="art3.html"', 'href="/old/art3.html"', $content);
    $content = str_replace('href="art4.html"', 'href="/old/art4.html"', $content);
    
    
    // Add scroll script if section is specified
    if ($scrollToSection) {
        $scrollScript = '<script>document.addEventListener("DOMContentLoaded", function() { setTimeout(function() { const section = document.getElementById("' . $scrollToSection . '"); if(section) { section.scrollIntoView({ behavior: "smooth", block: "start" }); window.history.pushState(null, "", "/' . $scrollToSection . '"); } }, 100); });</script>';
        $content = str_replace('</body>', $scrollScript . '</body>', $content);
    }
    
    return $content;
}

// Serve pages from old folder
Route::get('/', function () {
    $content = processOldHtml(base_path('old/index.html'));
    if ($content) {
        return response($content)->header('Content-Type', 'text/html');
    }
    return view('home');
});

Route::get('/about', function () {
    $content = processOldHtml(base_path('old/index.html'), 'about');
    if ($content) {
        return response($content)->header('Content-Type', 'text/html');
    }
    return redirect('/');
})->name('about');

Route::get('/contact', function () {
    $content = processOldHtml(base_path('old/index.html'), 'contact');
    if ($content) {
        return response($content)->header('Content-Type', 'text/html');
    }
    return redirect('/');
})->name('contact');

Route::get('/faq', function () {
    // Check if there's an FAQ HTML in old folder
    $content = processOldHtml(base_path('old/faq.html'));
    if ($content) {
        return response($content)->header('Content-Type', 'text/html');
    }
    // If no FAQ HTML exists, use the Laravel FAQ controller
    $request = request();
    $controller = new \App\Http\Controllers\HomeController();
    return $controller->fags($request);
})->name('faqs');

// Add named route for fags to maintain compatibility
Route::get('/fags', [\App\Http\Controllers\HomeController::class, 'fags'])->name('fags');
Route::get('/home', function () {
    return redirect('/');
})->name('home');

// Authentication Routes
Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);

Route::get('/api',function (){
    return view('home');
});

// Hot Affiliates Routes - Only for Super Admin (must be before catch-all)
Route::group(['prefix' => 'admin/hot-affiliates', 'as' => 'admin.hot-affiliates.'], function() {
    Route::get('/login', [\App\Http\Controllers\admin\HotAffiliateController::class, 'login'])->name('login');
    Route::post('/login', [\App\Http\Controllers\admin\HotAffiliateController::class, 'doLogin'])->name('doLogin');
    Route::get('/', [\App\Http\Controllers\admin\HotAffiliateController::class, 'index'])->name('index');
    Route::get('/{id}/edit', [\App\Http\Controllers\admin\HotAffiliateController::class, 'edit'])->name('edit');
    Route::put('/{id}', [\App\Http\Controllers\admin\HotAffiliateController::class, 'update'])->name('update');
    Route::delete('/{id}', [\App\Http\Controllers\admin\HotAffiliateController::class, 'destroy'])->name('destroy');
    Route::post('/logout', [\App\Http\Controllers\admin\HotAffiliateController::class, 'logout'])->name('logout');
});

// Serve assets from old folder - MUST come before catch-all route
Route::get('/old/assets/{path}', function ($path) {
    // Clean the path to prevent directory traversal
    $path = str_replace(['..', '\\'], ['', '/'], $path);
    $filePath = base_path('old/assets/' . $path);
    
    // Normalize path separators for Windows
    $filePath = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $filePath);
    
    if (file_exists($filePath) && is_file($filePath) && strpos(realpath($filePath), realpath(base_path('old/assets'))) === 0) {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'css' => 'text/css; charset=utf-8',
            'js' => 'application/javascript; charset=utf-8',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'json' => 'application/json',
            'map' => 'application/json',
        ];
        $mimeType = $mimeTypes[$extension] ?? (function_exists('mime_content_type') ? mime_content_type($filePath) : 'application/octet-stream');
        
        $content = file_get_contents($filePath);
        
        // Fix relative paths in CSS files if needed
        if ($extension === 'css') {
            // Get directory of CSS file relative to assets folder
            $cssDir = dirname($path); // e.g., 'css' or 'vendor/bootstrap/css'
            
            // Fix relative URLs in CSS files - preserve quotes if they exist
            // Replace ../../ with /old/assets/
            $content = preg_replace('/url\((["\']?)(\.\.\/)+/i', 'url($1/old/assets/', $content);
            // Replace ../ with /old/assets/ 
            $content = preg_replace('/url\((["\']?)\.\.\//i', 'url($1/old/assets/', $content);
            // Fix ./ paths (relative to same directory) - preserve quotes
            if ($cssDir !== '.' && $cssDir !== '') {
                // For files in subdirectories like css/style.css, ./ should point to css/
                $content = preg_replace('/url\((["\']?)\.\//i', 'url($1/old/assets/' . $cssDir . '/', $content);
            } else {
                // For files in root, ./ should point to assets root
                $content = preg_replace('/url\((["\']?)\.\//i', 'url($1/old/assets/', $content);
            }
        }
        
        // Add cache headers for better performance
        return response($content, 200)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'public, max-age=31536000')
            ->header('Access-Control-Allow-Origin', '*');
    }
    abort(404, 'Asset not found: ' . $path);
})->where('path', '.*');

// Serve other HTML pages from old folder
Route::get('/old/{page}.html', function ($page) {
    $htmlPath = base_path('old/' . $page . '.html');
    $content = processOldHtml($htmlPath);
    if ($content) {
        return response($content)->header('Content-Type', 'text/html');
    }
    abort(404);
})->where('page', '[a-zA-Z0-9_-]+');

Route::get('/{any}', function () {
    return view('errors.404');
})->where('any', '.*');