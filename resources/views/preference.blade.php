@extends('lw-settings::layouts.lw-settings')

@section('title', 'Site Settings Preference')

@push('styles')
    <style>
        /* Switch container */
        .switch-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 100px;
            height: 34px;
            margin: 0px 15px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            inset: 0;
            cursor: pointer;
            background-color: #dc3545;
            transition: 0.4s;
            border-radius: 34px;
        }

        /* knob */
        .slider::before {
            content: "";
            position: absolute;
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: #fff;
            transition: 0.4s;
            border-radius: 50%;
            z-index: 2;
        }

        /* TEXT */
        .slider::after {
            content: 'Disabled';
            position: absolute;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            top: 50%;
            left: 62px;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        /* Enabled */
        input:checked + .slider {
            background-color: #28a745;
        }

        input:checked + .slider::before {
            transform: translateX(66px);
        }

        input:checked + .slider::after {
            content: 'Enabled';
            left: 35px !important;
        }

    </style>
@endpush

@section('content')

<div class="container">

    <!-- Package Style Toggle -->
    
    <form method="POST" action="{{ route('site-settings.package-layout-toggle') }}" id="layoutForm">
        @csrf
        <div class="switch-container">
            <span id="layoutLabel" class="ml-2">
                Default Layout
            </span>
            <label class="switch">
                <input type="checkbox" id="layoutSwitch" {{ setting('default_layout') ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>
    </form>

    <form action="{{ route('settings.cache.clear.all') }}" method="POST"
        onsubmit="return confirm('Are you sure? This will clear all settings cache!')">
        @csrf
        <button type="submit" class="btn btn-lg btn-warning">
            Clear Settings Cache
        </button>
    </form>

</div>
@endsection

@push('scripts')
    <script>
        // Toggle Package Layout Switch
        document.getElementById('layoutSwitch').addEventListener('change', function() {
            var layoutLabel = document.getElementById('layoutLabel');
            
            // Toggle text based on switch state
            // if (this.checked) {
            //     layoutLabel.innerText = 'Disable default layout'; // Change text when enabled
            // } else {
            //     layoutLabel.innerText = 'Enable default layout'; // Change text when disabled
            // }

            // Automatically submit the form when the switch is toggled
            document.getElementById('layoutForm').submit();
        });

    </script>
@endpush