<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class SchoolController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permissions:admin.schools')->only(['index']);
        $this->middleware('permissions:admin.schools.create')->only(['store']);
        $this->middleware('permissions:admin.schools.edit')->only(['update']);
        $this->middleware('permissions:admin.schools.delete')->only(['destroy']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // get all schools
            $schools = School::all();
            $schools = $schools->map(function ($school) {
                return [
                    $school->id,
                    $school->name,
                    $school->phone,
                    $school->email,
                    $school->website,
                    $school->status,
                ];
            });
            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => $schools
            ]);
        } catch (\Exception $e) {
            // Return json
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'website' => 'required|string|max:255',
                'email' => 'required|string',
                'phone' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'zip' => 'required|string|max:255',
                'logo' => 'required|url|active_url',
                'manager' => 'required|array',
            ]);

            $school = School::create([
                'name' => $request->name,
                'website' => $request->website,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'state' => $request->state,
                'city' => $request->city,
                'country' => $request->country,
                'zip' => $request->zip,
                'logo' => $request->logo,
            ]);

            // check if manager exists
            $manager = User::where('email', $request->manager['email'])->first();
            if (!$manager) {
                // create manager
                $manager = User::create([
                    'role_id' => Role::where('name', 'manager')->first()->id,
                    'username' => uniqid('manager_'),
                    'email' => $request->manager['email'],
                    'password' => Hash::make($request->manager['password']),
                    'date_of_birth' => $request->manager['dob'] ?? Carbon::now(),
                    'status' => 'active',
                    'email_verified_at' => Carbon::now(),
                ]);
            } else {
                // assign manager for user
                $manager->role_id = Role::where('name', 'manager')->first()->id;
                $manager->save();
            }
            // attach manager to school with role manager
            $school->managers()->attach($manager, ['position' => $request->manager['department']]);

            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'School created successfully'
            ], 200);
        } catch (\Exception $e) {
            // Return json
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|in:all,managers,teachers,students,search_user',
                'keyword' => 'required_if:type,search_user|string',
            ]);
            // get school
            $school = School::findOrFail($id);
            $data = match ($request->type) {
                'all' => [
                    'school' => $school,
                    // get manager with role
                    'managers' => $school->managers->map(function ($manager) {
                        return [
                            $manager->id,
                            $manager->username,
                            $manager->email,
                            $manager->pivot->position,
                            \Carbon\Carbon::parse($manager->created_at)->format('d/m/Y'),
                            \Carbon\Carbon::parse($manager->updated_at)->format('d/m/Y'),
                        ];
                    }),
                ],
                'search_user' => User::where('email', 'like', '%' . $request->keyword . '%')
                    ->orWhere('username', 'like', '%' . $request->keyword . '%')
                    ->get(),
                'managers' => $school->managers->map(function ($manager) {
                    return [
                        $manager->id,
                        $manager->username,
                        $manager->email,
                        $manager->pivot->position,
                        \Carbon\Carbon::parse($manager->created_at)->format('d/m/Y'),
                        \Carbon\Carbon::parse($manager->updated_at)->format('d/m/Y'),
                    ];
                }),
                default => throw new \Exception('Invalid type'),
            };
            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            // Return json
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|in:school,add_manager,remove_manager',
            ]);
            $school = School::findOrFail($id);
            switch ($request->type) {
                case 'school':
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'website' => 'required|string|max:255',
                        'email' => 'required|string',
                        'phone' => 'required|string|max:255',
                        'address' => 'required|string|max:255',
                        'state' => 'required|string|max:255',
                        'city' => 'required|string|max:255',
                        'country' => 'required|string|max:255',
                        'zip' => 'required|string|max:255',
                        'logo' => 'required|url|active_url',
                    ]);
                    $school->name = $request->name;
                    $school->website = $request->website;
                    $school->email = $request->email;
                    $school->phone = $request->phone;
                    $school->address = $request->address;
                    $school->state = $request->state;
                    $school->city = $request->city;
                    $school->country = $request->country;
                    $school->zip = $request->zip;
                    $school->logo = $request->logo;
                    $school->save();
                    break;
                case 'add_manager':
                    $request->validate([
                        'user_id' => 'required|array',
                    ]);
                    foreach ($request->user_id as $user_id) {
                        // find user
                        $user = User::findOrFail($user_id);
                        // check if user is manager of school
                        if ($school->managers->contains($user)) {
                            // update position
                            $school->managers()->updateExistingPivot($user, ['position' => $request->position]);
                        } else {
                            // update role
                            $user->role_id = Role::where('name', 'manager')->first()->id;
                            // Update school
                            $user->school_id = $school->id;
                            // save user info
                            $user->save();
                            // attach user to school as manager
                            $school->managers()->attach($user, ['position' => $request->position]);
                        }
                    }
                    break;
                case 'remove_manager':
                    $request->validate([
                        'user_id' => 'required|exists:users,id',
                    ]);
                    // find user
                    $user = User::findOrFail($request->user_id);
                    // check if user is manager of school
                    if ($school->managers->contains($user)) {
                        // detach user from school
                        $school->managers()->detach($user);
                        // update role
                        $user->role_id = Role::where('name', 'user')->first()->id;
                        // Update school
                        $user->school_id = null;
                        // save user info
                        $user->save();
                    }else{
                        throw new \Exception('User is not manager of school');
                    }
                    break;
                default:
                    throw new \Exception('Invalid type');
            }
            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'School updated successfully'
            ], 200);
        } catch (\Exception $e) {
            // Return json
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        //
    }
}
