@extends('reports.report-layout')

@section('content')

@php
    $__role = strtolower(Auth::user()->role ?? 'bhw');
    $__baseUrl = "/{$__role}/reports";
@endphp

<div class="page-title">Patient Reports</div>
<p style="color:#6B7280; margin-bottom:24px;">Select a report to view.</p>

<div style="display:grid; grid-template-columns:repeat(2,1fr); gap:18px;">

    <a href="{{ $__baseUrl }}/patient/demographic" style="display:flex; align-items:center; gap:15px; padding:20px; border:1px solid #E5E7EB; border-radius:12px; text-decoration:none; color:inherit; background:white; min-height:90px;">
        <img src="/icons/patient-records.png" width="28" style="opacity:0.85;">
        <div>
            <div style="font-size:15px; font-weight:600;">Patient Demographic Report</div>
            <div style="font-size:13px; color:#6B7280; margin-top:2px;">Age, gender, and barangay distribution of registered patients</div>
        </div>
    </a>

    <a href="{{ $__baseUrl }}/patient/registration" style="display:flex; align-items:center; gap:15px; padding:20px; border:1px solid #E5E7EB; border-radius:12px; text-decoration:none; color:inherit; background:white; min-height:90px;">
        <img src="/icons/patient-records.png" width="28" style="opacity:0.85;">
        <div>
            <div style="font-size:15px; font-weight:600;">Patient Registration Report</div>
            <div style="font-size:13px; color:#6B7280; margin-top:2px;">Full list of registered patients with registration date and status</div>
        </div>
    </a>

</div>

<a href="{{ $__baseUrl }}" style="display:inline-block; margin-top:24px; color:#1A73E8; text-decoration:none; font-size:13px;">&larr; Back to Reports Overview</a>

@endsection