@extends('site-settings::layouts.app')

@section('title', 'Site Settings')

@section('content')
<div class="container">
    <h2>Site Settings</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('site.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @foreach($settings as $group => $groupSettings)
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">{{ $group ? ucfirst($group) : 'General' }}</div>
                <div class="card-body">
                    @foreach($groupSettings as $setting)
                        <div class="mb-3">
                            <label class="form-label">{{ ucwords(str_replace('_',' ',$setting->key)) }}</label>

                            @php
                                $fieldName = "settings.{$setting->key}";
                            @endphp

                            @switch($setting->type)
                                @case('text')
                                @case('email')
                                @case('url')
                                @case('password')
                                @case('number')

                                @case('color')
                                    <input type="{{ $setting->type }}" 
                                        class="form-control @error($fieldName) is-invalid @enderror"
                                        name="settings[{{ $setting->key }}]" 
                                        value="{{ old('settings.' . $setting->key, $setting->value) }}">
                                    @error($fieldName)
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                @break

                                @case('date')
                                    <input type="{{ $setting->type }}" 
                                        class="form-control @error($fieldName) is-invalid @enderror"
                                        name="settings[{{ $setting->key }}]" 
                                        value="{{ old('settings.' . $setting->key, $setting->value) }}">
                                    @error($fieldName)
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                @break

                                @case('textarea')
                                    <textarea name="settings[{{ $setting->key }}]" 
                                            class="form-control @error($fieldName) is-invalid @enderror" rows="3">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
                                    @error($fieldName)
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                @break

                                @case('boolean')
                                    <div class="form-check">
                                        <input type="hidden" name="settings[{{ $setting->key }}]" value="0">
                                        <input type="checkbox" class="form-check-input @error($fieldName) is-invalid @enderror"
                                            name="settings[{{ $setting->key }}]" value="1" 
                                            id="check_{{ $setting->key }}"
                                            {{ old('settings.' . $setting->key, $setting->value) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="check_{{ $setting->key }}">Enabled</label>
                                    </div>
                                    @error($fieldName)
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                @break

                                @case('json')
                                    <textarea name="settings[{{ $setting->key }}]" 
                                            class="form-control @error($fieldName) is-invalid @enderror" rows="5">{{ old('settings.' . $setting->key, json_encode(json_decode($setting->value), JSON_PRETTY_PRINT)) }}</textarea>
                                    @error($fieldName)
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                @break

                                @case('image')
                                    <input type="file" name="settings[{{ $setting->key }}]" class="form-control file-input @error($fieldName) is-invalid @enderror" data-preview="preview_{{ $setting->key }}">
                                    @error($fieldName)
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <div class="mt-2">
                                        <img id="preview_{{ $setting->key }}" src="{{ $setting->value ? asset('storage/' . $setting->value) : '' }}" alt="Preview" style="max-height: 150px;">
                                    </div>
                                @break

                                @case('file')
                                    @if($setting->value)
                                        <p>
                                            <a href="{{ asset('storage/' . $setting->value) }}" target="_blank">View Current File</a>
                                        </p>
                                    @endif
                                    <input type="file" name="settings[{{ $setting->key }}]" class="form-control @error($fieldName) is-invalid @enderror">
                                    @error($fieldName)
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                @break

                                @default
                                    <input type="text" 
                                        class="form-control @error($fieldName) is-invalid @enderror"
                                        name="settings[{{ $setting->key }}]" 
                                        value="{{ old('settings.' . $setting->key, $setting->value) }}">
                                    @error($fieldName)
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                            @endswitch
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-success">Update Settings</button>
    </form>

</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Select all file inputs with class "file-input"
            const fileInputs = document.querySelectorAll('.file-input');

            fileInputs.forEach(function(input) {
                input.addEventListener('change', function(e) {
                    const previewId = input.dataset.preview;
                    const preview = document.getElementById(previewId);

                    if (input.files && input.files[0]) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            preview.src = e.target.result;
                        }

                        reader.readAsDataURL(input.files[0]);
                    }
                });
            });
        });
    </script>

@endpush