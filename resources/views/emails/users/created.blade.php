<div
    style="font-family: Arial, sans-serif; line-height: 1.6; max-width: 600px; margin: 0 auto; color: #333 border: 1px solid #eee; padding: 20px; border-radius: 8px;">
    <h2 style="color: #4F46E5;">Welcome to {{ config('app.name') }}!</h2>
    <p>Hi {{ $user->name }},</p>
    <p>An administrator has just created an account for you. Below are your login credentials to access the platform:
    </p>

    <div style="background: #F3F4F6; padding: 15px; border-radius: 8px; margin: 20px 0;">
        <p style="margin: 0 0 10px 0;"><strong>Username / Email:</strong> {{ $user->email }}</p>
        <p style="margin: 0;"><strong>Password:</strong> {{ $password }}</p>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ route('login') }}"
            style="display: inline-block; padding: 12px 24px; background-color: #4F46E5; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">Login
            to Your Account</a>
    </div>

    <p>If you encounter any issues, please contact your administrator.</p>
</div>