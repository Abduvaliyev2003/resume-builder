<?php

namespace App\Domains\Profile\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Profile\Actions\DeleteAccountAction;
use App\Domains\Profile\Actions\UpdatePasswordAction;
use App\Domains\Profile\Actions\UpdateProfileAction;
use App\Domains\Profile\Actions\UpdateSettingsAction;
use App\Domains\Profile\DTOs\UpdatePasswordDTO;
use App\Domains\Profile\DTOs\UpdateProfileDTO;
use App\Domains\Profile\DTOs\UpdateSettingsDTO;
use App\Domains\Profile\Requests\DeleteAccountRequest;
use App\Domains\Profile\Requests\UpdatePasswordRequest;
use App\Domains\Profile\Requests\UpdateProfileRequest;
use App\Domains\Profile\Requests\UpdateSettingsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        protected UpdateProfileAction  $updateProfileAction,
        protected UpdatePasswordAction $updatePasswordAction,
        protected UpdateSettingsAction $updateSettingsAction,
        protected DeleteAccountAction  $deleteAccountAction,
    ) {}

    /**
     * Show the user profile page.
     */
    public function show(Request $request): View
    {
        $user    = $request->user()->load('profile');
        $profile = $user->profile;
        $resumes = $user->resumes()->orderBy('updated_at', 'desc')->get();

        // Get active sessions count (works only with database driver)
        $activeSessions = $this->getActiveSessions($request);

        return view('profile.show', [
            'user'           => $user,
            'profile'        => $profile,
            'resumes'        => $resumes,
            'activeSessions' => $activeSessions,
            'section'        => $request->query('section', 'profile'),
        ]);
    }

    /**
     * Update personal information and avatar.
     */
    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        $user   = $request->user();
        $dto    = UpdateProfileDTO::fromRequest($request);
        $avatar = $request->file('avatar');

        if ($request->boolean('remove_avatar')) {
            $this->updateProfileAction->removeAvatar($user);
        }

        $this->updateProfileAction->execute($user, $dto, $avatar);

        return redirect()->route('profile.show', ['section' => 'profile'])
            ->with('success_section', 'profile')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update password.
     */
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $dto = UpdatePasswordDTO::fromRequest($request);

        try {
            $this->updatePasswordAction->execute($request->user(), $dto);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput()->with('active_section', 'password');
        }

        return redirect()->route('profile.show', ['section' => 'password'])
            ->with('success_section', 'password')
            ->with('success', 'Password changed successfully.');
    }

    /**
     * Update account / notification / resume settings.
     */
    public function updateSettings(UpdateSettingsRequest $request): RedirectResponse
    {
        $dto = UpdateSettingsDTO::fromRequest($request);
        $this->updateSettingsAction->execute($request->user(), $dto);

        $section = $request->input('settings_section', 'account');

        return redirect()->route('profile.show', ['section' => $section])
            ->with('success_section', $section)
            ->with('success', 'Settings saved successfully.');
    }

    /**
     * Logout from all other devices (invalidate other sessions).
     */
    public function logoutOtherDevices(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        try {
            Auth::logoutOtherDevices($request->input('password'));
        } catch (\Exception $e) {
            return back()->withErrors(['password' => 'The provided password is incorrect.']);
        }

        // Also revoke other Sanctum tokens (keep current one)
        $currentToken = $request->user()->currentAccessToken();
        $request->user()->tokens()->when($currentToken, function ($q) use ($currentToken) {
            $q->where('id', '!=', $currentToken->id);
        })->delete();

        return redirect()->route('profile.show', ['section' => 'security'])
            ->with('success_section', 'security')
            ->with('success', 'All other sessions have been terminated.');
    }

    /**
     * Delete the user account permanently.
     */
    public function deleteAccount(DeleteAccountRequest $request): RedirectResponse
    {
        try {
            $this->deleteAccountAction->execute($request->user(), $request->input('password'));
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->with('active_section', 'danger');
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Your account has been permanently deleted.');
    }

    /**
     * Get active sessions from the database sessions table.
     */
    private function getActiveSessions(Request $request): array
    {
        try {
            $sessions = DB::table('sessions')
                ->where('user_id', $request->user()->id)
                ->orderBy('last_activity', 'desc')
                ->get(['id', 'ip_address', 'user_agent', 'last_activity']);

            return $sessions->map(fn ($s) => [
                'id'            => $s->id,
                'ip_address'    => $s->ip_address ?? 'Unknown',
                'user_agent'    => $s->user_agent ?? 'Unknown Browser',
                'last_activity' => \Carbon\Carbon::createFromTimestamp($s->last_activity)->diffForHumans(),
                'is_current'    => $s->id === $request->session()->getId(),
            ])->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
}
