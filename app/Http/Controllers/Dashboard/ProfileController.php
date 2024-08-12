<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Languages;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('dashboard.profile.edit' , [
            'user' => $user,
            'countries' => Countries::getNames(),
            'locals' => Languages::getNames(),
            ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'required|date|nullable|before:today',
            'gender' => 'in:male,female',
            'country' => 'required|string|size:2',
        ]);
        $user = $request->user();

        //fill()-> if the model is empty it fills it if not empty it updates it
        $user->profile->fill($request->all())->save();

        return redirect()->route('dashboard.profile.edit')->with('success', 'Profile updated');

//        $profile = $user->profile;
//        if ($profile->first_name) {
//            $profile->update($request->all());
//        }else {
////            $request->merge([
////                'user_id' => $user->id
////            ]);
////            Profile::create($request->all()); instead
//            $user->profile()->create($request->all());
//        }
    }
}
