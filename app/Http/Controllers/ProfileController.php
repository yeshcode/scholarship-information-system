<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Enrollment;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $isAdminLike = $user->hasRole('Super Admin') || $user->hasRole('Scholarship Coordinator');

        // ✅ Student detection
        $isStudent = ($user->userType->name ?? null) === 'Student';

        // ✅ Always define defaults so compact() won't error
        $activeEnrollment = null;
        $semesterLabel = 'N/A';
        $scholarRecord = null;

        // ✅ Only students load academic + scholar data
        if ($isStudent && !$isAdminLike) {

            $activeEnrollment = $user->enrollments()
                ->with(['semester', 'course.college']) // course->college
                ->where('status', Enrollment::STATUS_ENROLLED) // enrolled enum
                ->latest('id')
                ->first();

            $semesterLabel = ($activeEnrollment && $activeEnrollment->semester)
                ? $activeEnrollment->semester->term . ' ' . $activeEnrollment->semester->academic_year
                : 'N/A';

            // ✅ Scholar info (blank if none)
            if (method_exists($user, 'isScholar') && $user->isScholar()) {
                $scholarRecord = $user->scholarsAsStudent()
                    ->with('scholarship')
                    ->latest('id')
                    ->first();
            }
        }

        return view('profile', compact(
            'user',
            'isAdminLike',
            'isStudent',
            'activeEnrollment',
            'semesterLabel',
            'scholarRecord'
        ));
    }

    // ✅ Contact Number only (since it's in users table)
   public function updateContact(Request $request)
    {
        $user = Auth::user();
        $isAdminLike = $user->hasRole('Super Admin') || $user->hasRole('Scholarship Coordinator');
        $isStudent = ($user->userType->name ?? null) === 'Student';

        abort_if(!$isStudent || $isAdminLike, 403);

        $request->validate([
            'contact_no' => 'nullable|string|max:20',
        ]);

        $user->update([
            'contact_no' => $request->contact_no,
        ]);

        return back()->with('success', 'Contact number updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers(),
            ],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // ✅ Always hash (works even without model cast)
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }
}
