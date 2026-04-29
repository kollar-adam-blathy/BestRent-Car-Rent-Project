<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    private const PHONE_NUMBER_REGEX = '/^(?:\\+36|06)(?:[ -]?)(?:20|30|31|50|70)(?:[ -]?)(?:\\d{3})(?:[ -]?)(?:\\d{4})$/';

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['required', 'string', 'max:20', 'regex:' . self::PHONE_NUMBER_REGEX, 'unique:users,phone_number'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'phone_number.regex' => 'A telefonszám formátuma nem megfelelő. Példa: +36201234567 vagy 06201234567.',
        ]);

        $user = User::create($validated);
        Auth::login($user);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Sikeres regisztráció.',
                'user' => $user,
            ], 201);
        }

        return redirect('/')->with('success', 'Sikeres regisztráció!');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Hibás email vagy jelszó.',
                ], 422);
            }

            return back()->withErrors([
                'email' => 'Hibás email vagy jelszó.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Sikeres bejelentkezés.',
                'user' => $request->user(),
            ]);
        }

        return redirect('/')->with('success', 'Sikeres bejelentkezés!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Sikeres kijelentkezés.',
            ]);
        }

        return redirect('/')->with('success', 'Sikeres kijelentkezés!');
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    public static function phoneNumberRules(?int $ignoreUserId = null, bool $required = true): array
    {
        $rules = [
            $required ? 'required' : 'nullable',
            'string',
            'max:20',
            'regex:' . self::PHONE_NUMBER_REGEX,
            Rule::unique('users', 'phone_number')->ignore($ignoreUserId),
        ];

        return $rules;
    }
}
