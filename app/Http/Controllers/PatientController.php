<?php

namespace App\Http\Controllers;

use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    // Retrieve all patients
    public function index()
    {
        $patients = Patient::with('user')->get();
        //return response()->json($patients);
        return PatientResource::collection($patients);
    }

    // Store a new patient
    public function store(Request $request)
    {
        // Validate user data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'allergies' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create a new user for the patient
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password
            'role' => 'Patient', // Assign the role as Patient
            'phone' => $request->phone ?? null,
        ]);

        // Create the patient record
        $patient = Patient::create([
            'user_id' => $user->id, // Use the newly created user's ID
            'dob' => $request->dob,
            'gender' => $request->gender,
            'address' => $request->address,
            'medical_history' => $request->medical_history,
            'allergies' => $request->allergies,
        ]);

        return response()->json($patient, 201);
    }
    // Retrieve a single patient by ID
    public function show($id)
    {
        $patient = Patient::with('user')->find($id);
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }
        return response()->json($patient);
    }

    // Update a patient
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'address' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'allergies' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        $patient->update($request->all());
        return response()->json($patient);
    }

    // Delete a patient
    public function destroy($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        $patient->delete();
        return response()->json(['message' => 'Patient deleted successfully']);
    }
}
