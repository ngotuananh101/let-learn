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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
                    'date_of_birth' => $request->manager['dob'],
                    'status' => 'active',
                    'email_verified_at' => Carbon::now(),
                ]);
            }else{
                // assign manager for user
                $manager->role_id = Role::where('name', 'manager')->first()->id;
                $manager->save();
            }
            // attach manager to school with role manager
            $school->managers()->attach($manager, ['role' => $request->manager['department']]);

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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
