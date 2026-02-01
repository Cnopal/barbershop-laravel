#!/usr/bin/env php
<?php
/**
 * Gmail Login Configuration Verification Script
 * Run this script to verify your Gmail login setup
 */

$checks = [];

// Check 1: .env file has Google credentials
$checks[] = [
    'name' => 'Google Credentials in .env',
    'check' => function () {
        $clientId = getenv('GOOGLE_CLIENT_ID');
        $clientSecret = getenv('GOOGLE_CLIENT_SECRET');
        $redirectUri = getenv('GOOGLE_REDIRECT_URI');
        
        return $clientId && $clientSecret && $redirectUri;
    },
    'message' => 'Check that GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, and GOOGLE_REDIRECT_URI are set in .env',
];

// Check 2: Laravel Socialite is installed
$checks[] = [
    'name' => 'Laravel Socialite Package',
    'check' => function () {
        return class_exists('Laravel\Socialite\Facades\Socialite');
    },
    'message' => 'Run: composer require laravel/socialite',
];

// Check 3: Database migration was run
$checks[] = [
    'name' => 'OAuth Columns in Database',
    'check' => function () {
        // This check runs if we can get a database connection
        try {
            if (function_exists('config') && function_exists('DB')) {
                $columns = \DB::getSchemaBuilder()->getColumnListing('users');
                return in_array('google_id', $columns) && 
                       in_array('google_token', $columns) && 
                       in_array('google_refresh_token', $columns);
            }
        } catch (\Exception $e) {
            return null; // Skip this check if database isn't accessible
        }
        return false;
    },
    'message' => 'Run: php artisan migrate',
];

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "  Gmail Login Configuration Verification\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

$allPassed = true;

foreach ($checks as $check) {
    echo "Checking: {$check['name']}...\n";
    
    try {
        $result = $check['check']();
        
        if ($result === null) {
            echo "  ⊘ SKIPPED (Database not accessible)\n";
        } elseif ($result) {
            echo "  ✓ PASSED\n";
        } else {
            echo "  ✗ FAILED\n";
            echo "  → {$check['message']}\n";
            $allPassed = false;
        }
    } catch (\Exception $e) {
        echo "  ! ERROR: {$e->getMessage()}\n";
        $allPassed = false;
    }
    
    echo "\n";
}

echo "═══════════════════════════════════════════════════════════════\n";

if ($allPassed) {
    echo "✓ All checks passed! Gmail login should work.\n\n";
    echo "Next steps:\n";
    echo "1. Make sure the redirect URI in Google Cloud Console matches:\n";
    echo "   http://127.0.0.1:8000/auth/google/callback\n";
    echo "2. Test the login at: http://127.0.0.1:8000/login\n";
} else {
    echo "✗ Some checks failed. Please fix the issues above.\n";
}

echo "\n";
?>
