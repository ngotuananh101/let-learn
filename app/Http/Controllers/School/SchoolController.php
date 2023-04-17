<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // check permission
            $user = auth()->user();
            if ($user->hasAnyPermission(['admin.dashboard', 'manager.dashboard'])) {
                if ($user->role->name === 'admin' || $user->role->name === 'super') {
                    // get school by slug
                    $school = School::where('slug', $id)->firstOrFail();
                } else {
                    // check if user is manager of school
                    if ($user->school->slug === $id) {
                        $school = $user->school;
                    } else {
                        return response()->json([
                            'message' => 'You are not authorized to view school'
                        ], 403);
                    }
                }
                return response()->json([
                    'school' => $school
                ], 200);
            } else {
                return response()->json([
                    'message' => 'You are not authorized to view school'
                ], 403);

            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'School not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255',
                'website' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'region' => 'required|string|max:255',
                'logo' => 'required|string',
            ]);

            // check permission
            $user = auth()->user();
            if ($user->hasAnyPermission(['admin.dashboard', 'manager.dashboard'])) {
                if ($user->role->name === 'admin' || $user->role->name === 'super') {
                    // get school by slug
                    $school = School::where('slug', $id)->firstOrFail();
                } else {
                    // check if user is manager of school
                    if ($user->school->slug === $id) {
                        $school = $user->school;
                    } else {
                        return response()->json([
                            'message' => 'You are not authorized to update school'
                        ], 403);
                    }
                }
                // convert base 64 to image
                $image = $request->logo;
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = Str::random(10) . '.png';
                Storage::put('public/images/schools/' . $imageName, base64_decode($image));
                // get image name from logo field
                $image = explode('/', $school->logo);
                $image = end($image);
                // delete old image
                Storage::delete('public/images/schools/' . $image);
                $request->merge(['logo' => '/storage/images/schools/' . $imageName]);
                $school->update($request->all());
                return response()->json([
                    'school' => $school
                ], 200);
            } else {
                return response()->json([
                    'message' => 'You are not authorized to update school'
                ], 403);

            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }
    }

}
