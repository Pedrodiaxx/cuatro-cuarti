<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Models\Doctor;
use App\Models\Speciality;
use App\Models\User;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with(['user', 'speciality'])->latest()->paginate(10);
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        $doctor = new Doctor();
        $users = User::orderBy('name')->get();
        $specialities = Speciality::orderBy('name')->get();

        return view('admin.doctors.create', compact('doctor', 'users', 'specialities'));
    }

    public function store(StoreDoctorRequest $request)
    {
        Doctor::create($request->validated());
        return redirect()->route('admin.doctors.index');
    }

    public function show(Doctor $doctor)
    {
        $doctor->load(['user', 'speciality']);
        return view('admin.doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        $users = User::orderBy('name')->get();
        $specialities = Speciality::orderBy('name')->get();

        return view('admin.doctors.edit', compact('doctor', 'users', 'specialities'));
    }

    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        $doctor->update($request->validated());
        return redirect()->route('admin.doctors.index');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->route('admin.doctors.index');
    }
}