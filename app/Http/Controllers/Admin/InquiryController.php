<?php
namespace App\Http\Controllers\Admin;

use App\Models\Query;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InquiryController extends Controller
{
    // Display a listing of the inquiries.
    public function index()
    {
        $inquiries = Query::orderBy('created_at', 'desc')->get();
        return view('backend.pages.inquiries.index', compact('inquiries'));
    }

    // Remove the specified inquiry from storage.
    public function destroy(Query $inquiry)
    {
        $inquiry->delete();

        return redirect()->route('backend.inquiries.index')->with('success', 'Inquiry deleted successfully.');
    }
}