<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Utils\BusinessUtil;

class LoginController extends Controller
{
    protected $businessUtil;

    /**
     * Create a new controller instance.
     */
    public function __construct(BusinessUtil $businessUtil)
    {
       
        $this->businessUtil = $businessUtil;
    }

    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {

        //return 1;
        // Authenticate user manually (since AuthenticatesUsers was removed)
        $credentials = $request->only('username', 'password');

        if (!Auth::attempt($credentials)) {
            
            return back()->withErrors(['username' => __('auth.failed')]);
            
        }

        $user = Auth::user();

        // Set business date format
        if (!empty($user->business)) {
            // Get the business model
            $business = $user->business;
            
            // Store the entire business object in session if needed
            session(['business' => $business]);
        }

        // Business and user active checks
        if (!$user->business->is_active) {
            Auth::logout();
            return redirect('/login')->with('status', ['success' => 0, 'msg' => __('lang_v1.business_inactive')]);
        } elseif ($user->status != 'active') {
            Auth::logout();
            return redirect('/login')->with('status', ['success' => 0, 'msg' => __('lang_v1.user_inactive')]);
        }

        // MAC address validation (ensure exec() is enabled)
        if (config('app.env') !== 'live') {
            if (!$this->validateMacAddress()) {
                Auth::logout();
                return redirect('invalid_mac');
            }
        }

        // Regenerate session and redirect
        $request->session()->regenerate();
        return redirect()->intended($this->redirectTo());
    }

    /**
     * Destroy an authenticated session (logout).
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Custom username authentication instead of email.
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Determine where to redirect users after login.
     */
    protected function redirectTo()
    {
        $user = Auth::user();
        if (!$user->can('dashboard.data') && $user->can('sell.create')) {
            return '/pos/create';
        }
        return '/home';
    }

    /**
     * Validate user MAC address.
     */
    private function validateMacAddress(): bool
    {
        $permission_1 = config('app.permit_1');
        $permission_2 = config('app.permit_2');
        $permission_3 = config('app.permit_3');
        $permission_4 = config('app.permit_4');
        $permission_5 = config('app.permit_5');

        $permission_Address = $_SERVER['REMOTE_ADDR'];
        $main_permission = "";

        $arp = shell_exec("arp -a $permission_Address");
        $lines = explode("\n", $arp);

        foreach ($lines as $line) {
            $cols = preg_split('/\s+/', trim($line));
            if (isset($cols[0]) && $cols[0] == $permission_Address) {
                $main_permission = $cols[1] ?? "";
            }
        }

        return in_array($main_permission, [$permission_1, $permission_2, $permission_3, $permission_4, $permission_5, ""]);
    }
}
