# Microsoft OAuth Integration Setup Guide

## Overview
This guide will help you set up Microsoft OAuth authentication for your Laravel application, allowing users to sign in with their Microsoft accounts.

## Prerequisites
- Laravel Socialite package (already installed)
- Microsoft Azure account
- Access to Azure Active Directory

## Step 1: Create Microsoft Azure App Registration

### 1.1 Access Azure Portal
1. Go to [Azure Portal](https://portal.azure.com)
2. Sign in with your Microsoft account
3. Navigate to **Azure Active Directory**

### 1.2 Register New Application
1. In Azure AD, go to **App registrations**
2. Click **New registration**
3. Fill in the application details:
   - **Name**: WebSecService (or your preferred name)
   - **Supported account types**: Select appropriate option (usually "Accounts in any organizational directory and personal Microsoft accounts")
   - **Redirect URI**: 
     - Type: Web
     - URI: `http://127.0.0.1:8000/auth/microsoft/callback` (for development)
     - For production: `https://yourdomain.com/auth/microsoft/callback`

### 1.3 Get Application Credentials
1. After registration, go to **Overview** tab
2. Copy the **Application (client) ID**
3. Go to **Certificates & secrets** tab
4. Click **New client secret**
5. Add description and set expiration
6. Copy the **Value** (client secret) - **Important**: Save this immediately as it won't be shown again

## Step 2: Configure Laravel Application

### 2.1 Environment Variables
Add the following to your `.env` file:

```env
# Microsoft OAuth Configuration
MICROSOFT_CLIENT_ID=your_application_client_id_here
MICROSOFT_CLIENT_SECRET=your_client_secret_here
MICROSOFT_REDIRECT_URI=http://127.0.0.1:8000/auth/microsoft/callback
```

**For Production:**
```env
MICROSOFT_REDIRECT_URI=https://yourdomain.com/auth/microsoft/callback
```

### 2.2 Update Azure Redirect URIs
If deploying to production, add your production callback URL to Azure:
1. Go back to Azure Portal > App registrations > Your app
2. Go to **Authentication**
3. Add production redirect URI: `https://yourdomain.com/auth/microsoft/callback`

## Step 3: Test the Integration

### 3.1 Start Development Server
```bash
php artisan serve
```

### 3.2 Test Microsoft Login
1. Navigate to `http://127.0.0.1:8000/login`
2. Click "تسجيل الدخول باستخدام Microsoft" (Login with Microsoft)
3. You should be redirected to Microsoft login page
4. After successful authentication, you'll be redirected back to your application

## Features Implemented

### 3.1 User Authentication Flow
- **New Users**: Automatically creates account with Microsoft profile data
- **Existing Users**: Updates profile information and logs them in
- **Role Assignment**: New users are automatically assigned "user" role
- **Profile Data**: Stores name, email, Microsoft ID, and avatar

### 3.2 Security Features
- **Unique Username Generation**: Automatically generates unique usernames
- **Email Verification**: Microsoft-authenticated users are automatically verified
- **Random Password**: Assigns secure random password for Microsoft users
- **Last Login Tracking**: Updates last login timestamp

### 3.3 User Interface
- **Modern Login Button**: Clean Microsoft-branded login button
- **Bilingual Support**: Arabic interface with English OAuth flow
- **Error Handling**: Comprehensive error messages and fallbacks

## Database Schema

The following fields were added to the `users` table:
- `microsoft_id` (string, nullable): Microsoft user ID
- `avatar` (string, nullable): Profile picture URL from Microsoft

## Routes Added

```php
// Microsoft OAuth Authentication
Route::get('/auth/microsoft', [MicrosoftAuthController::class, 'redirectToMicrosoft'])->name('auth.microsoft');
Route::get('/auth/microsoft/callback', [MicrosoftAuthController::class, 'handleMicrosoftCallback'])->name('auth.microsoft.callback');
Route::post('/auth/logout', [MicrosoftAuthController::class, 'logout'])->name('auth.logout');
```

## Files Created/Modified

### New Files:
- `app/Http/Controllers/MicrosoftAuthController.php` - OAuth controller
- `database/migrations/2025_08_11_001619_add_microsoft_oauth_fields_to_users_table.php` - Database migration
- `MICROSOFT_OAUTH_SETUP.md` - This documentation

### Modified Files:
- `config/services.php` - Added Microsoft OAuth configuration
- `app/Models/User.php` - Added Microsoft OAuth fields to fillable
- `resources/views/auth/login.blade.php` - Added Microsoft login button
- `routes/web.php` - Added Microsoft OAuth routes
- `.env.example` - Added Microsoft OAuth environment variables

## Troubleshooting

### Common Issues:

1. **"Invalid redirect URI"**
   - Ensure the redirect URI in Azure matches exactly with your application URL
   - Check for trailing slashes and HTTP vs HTTPS

2. **"Client secret expired"**
   - Generate a new client secret in Azure Portal
   - Update the `MICROSOFT_CLIENT_SECRET` in your `.env` file

3. **"Application not found"**
   - Verify the `MICROSOFT_CLIENT_ID` is correct
   - Ensure the application is properly registered in Azure

4. **Users can't access permissions**
   - New Microsoft users are assigned "user" role by default
   - Super Admin needs to manually assign appropriate roles

### Debugging:
- Check Laravel logs: `storage/logs/laravel.log`
- Enable debug mode: `APP_DEBUG=true` in `.env`
- Use `dd()` or `Log::info()` for debugging OAuth flow

## Security Considerations

1. **Environment Variables**: Never commit `.env` file to version control
2. **HTTPS in Production**: Always use HTTPS for production OAuth callbacks
3. **Client Secret**: Store client secret securely and rotate regularly
4. **User Permissions**: Review and assign appropriate roles to OAuth users

## Next Steps

1. **Set up Azure App Registration** with your credentials
2. **Configure environment variables** in your `.env` file
3. **Test the integration** in development environment
4. **Deploy to production** with proper HTTPS configuration
5. **Assign roles** to Microsoft-authenticated users as needed

## Support

For issues related to:
- **Azure Configuration**: Check Microsoft Azure documentation
- **Laravel Socialite**: Check Laravel Socialite documentation
- **Application Issues**: Check application logs and error messages

The Microsoft OAuth integration is now fully functional and ready for use!
