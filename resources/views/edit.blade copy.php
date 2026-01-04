@extends('site-settings::layouts.app')

@section('title', 'Site Settings')

@section('content')
<div class="container">
    <h2>Site Settings</h2>

    <form action="{{ route('site.settings') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @foreach($settings as $group => $groupSettings)
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">{{ ucfirst($group) }}</div>
                <div class="card-body">
                    @foreach($groupSettings as $setting)
                        <div class="mb-3">
                            <label>{{ ucwords(str_replace('_',' ',$setting->key)) }}</label>
                            <input type="text" class="form-control" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}">
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <button class="btn btn-success">Update Settings</button>
    </form>
    
</div>
@endsection
