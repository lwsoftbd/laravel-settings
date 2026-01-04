@extends('site-settings::layouts.app')

@section('title', 'Site Settings')

@push('styles')
    <style>
        .search-container {
            margin-bottom: 20px;
        }
        pre {
            display: inline; /* Make sure it doesn't take full width */
        }
        pre:before {
            content: "\007B\007B "; /* Unicode for { */
        }
        pre:after {
            content: "\007D\007D"; /* Unicode for } */
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
@endpush

@section('content')
<div class="container">
    <h2>Site Settings</h2>

    <div class="search-container">
        <input type="text" id="search" class="form-control" placeholder="Search Settings..." />
    </div>

    <table>
        <thead>
            <tr>
                <th>Group</th>
                <th>Name</th>
                <th>Keyword</th>
                <th>Value</th>
                <th>Code</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="settingsTableBody">
            @foreach($settings as $setting)
                <tr>
                    <td>{{ $setting->group }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $setting->key)) }}</td>
                    <td>{{ $setting->key }}</td>
                    <td>{{ $setting->value }}</td>
                    <td>
                        <code>
                            <pre id="codeSnippet"> $setting('{{ $setting->key }}') </pre>
                        </code>
                    </td>
                    <td>
                        <button class="copy-btn btn btn-primary btn-sm" onclick="copyCode('{{ $setting->key }}')">Copy</button>
                        
                        <form action="{{ route('settings.cache.clear', $setting->key) }}"
                            method="POST"
                            style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">
                                Clear Cache
                            </button>
                        </form>
                        
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection

@push('scripts')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            // Live search functionality
            $('#search').keyup(function(){
                var query = $(this).val();

                if (query.length > 2) { // Start search after 3 characters
                    $.ajax({
                        url: "{{ route('settings.search') }}",
                        method: 'GET',
                        data: { query: query },
                        success: function(data) {
                            // Clear the table body and re-render
                            $('#settingsTableBody').empty();

                            if (data.length > 0) {
                                // If data exists, loop through and append
                                $.each(data, function(index, setting) {
                                    $('#settingsTableBody').append(
                                        '<tr>' +
                                            '<td>' + setting.group + '</td>' +
                                            '<td>' + setting.key.replace(/_/g, ' ') + '</td>' +
                                            '<td>' + setting.key + '</td>' +
                                            '<td>' + setting.value + '</td>' +
                                            '<td>' +
                                                '<code>' +
                                                    '<pre id="codeSnippet"> $setting(\'' + setting.key + '\') </pre>' +
                                                '</code>' +
                                            '</td>' +
                                            '<td>' +
                                                '<button class="copy-btn" onclick="copyCode(\'' + setting.key + '\')">Copy</button>' +
                                            '</td>' +
                                        '</tr>'
                                    );
                                });
                            } else {
                                // If no results, show "No results found" message
                                $('#settingsTableBody').append('<tr><td colspan="6">No results found</td></tr>');
                            }
                        },
                        error: function() {
                            alert('Error fetching data');
                        }
                    });
                } else {
                    // If query is empty, fetch all settings again
                    $.ajax({
                        url: "{{ route('settings.search') }}",
                        method: 'GET',
                        data: { query: '' }, // Empty query fetches all settings
                        success: function(data) {
                            // Clear and append all settings
                            $('#settingsTableBody').empty();
                            $.each(data, function(index, setting) {
                                $('#settingsTableBody').append(
                                    '<tr>' +
                                        '<td>' + setting.group + '</td>' +
                                        '<td>' + setting.key.replace(/_/g, ' ') + '</td>' +
                                        '<td>' + setting.key + '</td>' +
                                        '<td>' + setting.value + '</td>' +
                                        '<td>' +
                                            '<code>' +
                                                '<pre id="codeSnippet"> $setting(\'' + setting.key + '\') </pre>' +
                                            '</code>' +
                                        '</td>' +
                                        '<td>' +
                                            '<button class="copy-btn" onclick="copyCode(\'' + setting.key + '\')">Copy</button>' +
                                        '</td>' +
                                    '</tr>'
                                );
                            });
                        }
                    });
                }
            });
        });


    </script>

    <script>
        function copyCode(settingKey) {
            // Create the string that needs to be copied
            const codeText = `$setting('${settingKey}')`;

            // Copy the string to clipboard
            navigator.clipboard.writeText(codeText)
                .then(() => {
                    alert('Code copied to clipboard!');
                })
                .catch(err => {
                    alert('Failed to copy: ' + err);
                });
        }
    </script>
@endpush