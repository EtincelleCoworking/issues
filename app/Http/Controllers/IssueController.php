<?php

namespace App\Http\Controllers;

use App\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IssueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $issues = Issue::with('reporter')
            ->with('responsible')
            ->get();
        return view('issues/index', ['issues' => $issues]);
    }

    public function create()
    {
        $issue = new Issue();
        $issue->title = '';
        $issue->description = '';
        $issue->reporter = Auth::user();
        $issue->created_at = date('Y-m-d H:i:s');
        $issue->updated_at = $issue->created_at;
        return view('issues/show', [
            'issue' => $issue
        ]);
    }

    public function show($id){
        $issue = Issue::with('reporter')
            ->with('responsible')
            ->with('comments')
            ->findOrFail($id);
        return view('issues/show', ['issue' => $issue]);
    }
}
