@extends('layouts.admin_account')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Laravel Log - Last 20 Lines</h4>
    </div>
    <div class="card-body">
        @if(count($lines) > 0)
            <div class="log-container" style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 1rem; max-height: 500px; overflow-y: auto; font-family: monospace; font-size: 0.875rem;">
                @foreach(array_reverse($lines) as $line)
                    @if(!empty($line))
                        <div class="log-line" style="margin-bottom: 0.25rem; white-space: pre-wrap;">{{ $line }}</div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
                No log entries found.
            </div>
        @endif
    </div>
</div>
@endsection