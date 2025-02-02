<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Scholarship;
use App\Models\Requirements;
use App\Models\Scholar;
use App\Models\SubmittedRequirements;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScholarshipController extends Controller
{
    public function index()
    {
        $scholarships = Scholarship::all();
        return inertia('Super_Admin/Scholarships/Index', ['scholarships' => $scholarships]);
    }

    public function scholarship(Sponsor $sponsors)
    {

        $scholarships = Scholarship::all();
        $sponsors = Sponsor::all();
        // $scholarships = $sponsors->scholarships;

        return inertia('Super_Admin/Scholarships/ViewScholarships', [
        'sponsors' => $sponsors,
        'scholarships' => $scholarships,
        ]);
    }

    // for dashboard
    // public function dashboard_scholarship(Sponsor $sponsors)
    // {
    //     $scholarships = Scholarship::all();
    //     $sponsors = Sponsor::all();
    //     // $scholarships = $sponsors->scholarships;

    //     return inertia('Components/Admin/Dashboard/ActiveScholarship', [
    //     'sponsors' => $sponsors,
    //     'scholarships' => $scholarships,
    //     ]);
    // }

    public function show(Scholarship $scholarship)
    {
        
        $scholars = $scholarship->scholars;

        // $requirements = Requirements::where('scholarship_id', $scholarship->id)->get();

        // $reqID = $requirements->pluck('id')->first();

        // $submitRequirements = SubmittedRequirements::where('id', $reqID)->get();

        return Inertia::render('Super_Admin/Scholarships/Scholarship', [
            'scholarship' => $scholarship,
            'scholars' => $scholars,
        ]);
    }

    public function store(Request $request, Sponsor $sponsor)
    {
        $request->validate([
            'sponsor_id' => 'required|int',
            'name' => 'required|string|max:255',
            'scholarshipType' => 'required|string|max:255',
            // 'requirements' => 'required|array'
            // 'application' => 'required|date',
            // 'deadline' => 'required|date',
        ]);
        
        //dd($request);
        Scholarship::create($request->all());

        return redirect()->route('sponsor.index')->with('success', 'Check out view scholarships');
    }

    public function send(Scholarship $scholarship)
    {
        $scholars = $scholarship->scholars;

        return Inertia::render('Super_Admin/Scholarships/SendingAccess', [
            'scholarship' => $scholarship,
            'scholars' => $scholars,
        ]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // dd($request);

        $scholarship = Scholarship::findOrFail($id);
        $scholarship->update($request->all());

        return redirect()->route('scholarships.index');
    }
    // public function destroy($id)
    // {
    //     try {
    //         $scholarship = Scholarship::findOrFail($id); // Find the scholarship by ID
    //         $scholarship->delete(); // Delete the scholarship

    //         return response()->json(['message' => 'Scholarship deleted successfully']);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Failed to delete scholarship', 'details' => $e->getMessage()], 500);
    //     }
    // }

    public function requirementsTab(Scholarship $scholarship)
    {
        $scholars = $scholarship->scholars;

        return Inertia::render('Super_Admin/Scholarships/ScholarshipTabs/Requirements', [
            'scholarship' => $scholarship,
            'scholars' => $scholars,
        ]);
    }
}


