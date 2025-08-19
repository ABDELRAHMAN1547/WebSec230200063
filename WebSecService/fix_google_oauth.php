<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Laravel\Socialite\Facades\Socialite;

echo "🔍 Google OAuth Configuration Analysis:\n";
echo "=====================================\n\n";

// تحليل التكوين الحالي
echo "📋 Current Configuration:\n";
echo "Client ID: " . config('services.google.client_id') . "\n";
echo "Client Secret: " . (config('services.google.client_secret') ? 'SET ✅' : 'NOT SET ❌') . "\n";
echo "Redirect URI: " . config('services.google.redirect') . "\n";
echo "App URL: " . config('app.url') . "\n\n";

// اختبار Socialite
echo "🧪 Testing Socialite Driver:\n";
try {
    $driver = Socialite::driver('google');
    echo "Socialite Google Driver: OK ✅\n";
    
    // اختبار redirect URL
    $redirectUrl = $driver->redirectUrl('http://localhost:8000/oauth/google/callback');
    echo "Custom Redirect URL: OK ✅\n";
    
} catch (\Exception $e) {
    echo "Socialite Error: " . $e->getMessage() . " ❌\n";
}

echo "\n🔧 Recommended Solutions:\n";
echo "========================\n";
echo "1. Add to Google Console Authorized redirect URIs:\n";
echo "   - http://localhost:8000/oauth/google/callback\n";
echo "   - http://127.0.0.1:8000/oauth/google/callback\n\n";

echo "2. Ensure APP_URL in .env is set to:\n";
echo "   APP_URL=http://localhost:8000\n\n";

echo "3. Clear Laravel config cache:\n";
echo "   php artisan config:clear\n\n";

echo "🎯 Test URLs:\n";
echo "============\n";
echo "Login Page: http://localhost:8000/login\n";
echo "OAuth Debug: http://localhost:8000/oauth-debug\n";
echo "Direct Test: http://localhost:8000/test-google-oauth\n\n";

echo "✅ Configuration analysis complete!\n";
