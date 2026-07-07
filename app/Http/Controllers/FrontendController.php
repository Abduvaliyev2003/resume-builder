<?php

namespace App\Http\Controllers;

use App\Domains\Resume\Repositories\ResumeRepositoryInterface;
use App\Domains\Template\Repositories\TemplateRepositoryInterface;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FrontendController extends Controller
{
    public function __construct(
        protected ResumeRepositoryInterface $resumeRepository,
        protected TemplateRepositoryInterface $templateRepository,
        protected \App\Domains\Resume\Services\ResumeTemplateRenderer $templateRenderer
    ) {}

    // Guest Auth Views
    public function login()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function register()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function forgotPassword()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.forgot-password');
    }

    public function verifyEmailNotice(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $email = $request->user()->email;
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1];
        if (strlen($name) > 4) {
            $maskedName = substr($name, 0, 2) . '***' . substr($name, -2);
        } else {
            $maskedName = substr($name, 0, 1) . '***';
        }
        $maskedEmail = $maskedName . '@' . $domain;

        return view('auth.verify-email', [
            'email' => $maskedEmail
        ]);
    }

    public function verifyEmailCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $verified = app(\App\Domains\User\Services\EmailVerificationService::class)
            ->verifyCode($user, $request->input('code'));

        if (!$verified) {
            return back()->withErrors(['code' => 'The verification code is invalid or has expired.']);
        }

        event(new \Illuminate\Auth\Events\Verified($user));

        return redirect()->route('dashboard')->with('status', 'email-verified');
    }

    public function sendVerificationNotification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-code-sent');
    }

    // Protected Views
    public function dashboard(Request $request)
    {
        $resumes = $this->resumeRepository->getUserResumes(auth()->id());

        // Calculate basic stats for user feedback dashboard
        $totalResumes = $resumes->count();
        $averageScore = $resumes->avg('score') ?? 0;
        $totalExports = $resumes->sum(fn($r) => $r->versions()->count()); // Simple heuristic for activity

        return view('dashboard', [
            'resumes' => $resumes,
            'stats' => [
                'total_resumes' => $totalResumes,
                'average_score' => round($averageScore),
                'total_exports' => $totalExports
            ]
        ]);
    }

    public function templates()
    {
        $templates = $this->templateRepository->allActive();
        return view('templates.index', ['templates' => $templates]);
    }

    public function builder(string $id)
    {
        $resume = $this->resumeRepository->findById($id);

        if (!$resume) {
            abort(404, 'Resume not found.');
        }

        // Authorize access
        if ($resume->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to resume.');
        }

        $templates = $this->templateRepository->allActive();

        // Structure sections nicely by section_type
        $sections = $resume->sections->keyBy('section_type');

        return view('resumes.builder', [
            'resume' => $resume,
            'templates' => $templates,
            'sections' => $sections,
        ]);
    }

    public function preview(string $id)
    {
        $resume = $this->resumeRepository->findById($id);

        if (!$resume) {
            abort(404, 'Resume not found.');
        }

        // Authorize access
        if ($resume->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to resume.');
        }

        $renderedHtml = $this->templateRenderer->render($resume, false);

        return view('resumes.preview', [
            'resume' => $resume,
            'renderedHtml' => $renderedHtml,
        ]);
    }

    public function shared(string $id)
    {
        $resume = $this->resumeRepository->findById($id);

        if (!$resume) {
            abort(404, 'Resume not found.');
        }

        $renderedHtml = $this->templateRenderer->render($resume, false);

        // Public preview has no auth check
        return view('resumes.preview', [
            'resume' => $resume,
            'isShared' => true,
            'renderedHtml' => $renderedHtml,
        ]);
    }

    public function aiFeedback(string $id)
    {
        $resume = $this->resumeRepository->findById($id);

        if (!$resume) {
            abort(404, 'Resume not found.');
        }

        if ($resume->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to resume.');
        }

        $reviews = $resume->aiReviews()->orderBy('created_at', 'desc')->get();

        return view('resumes.ai-feedback', [
            'resume' => $resume,
            'reviews' => $reviews,
        ]);
    }
}
