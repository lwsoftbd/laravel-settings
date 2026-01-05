@extends('lw-settings::layouts.lw-settings')

@section('title', 'Site Settings')

@push('styles')
    <style>
        .search-container {
            margin-left: 20px;
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

    <div class="navbar-top">
        <div class="d-flex">
            <!-- Trigger Button for Modal -->
            <button class="btn btn-sm btn-primary" onclick="openModal()">Add New Settings</button>

            <div class="search-container">
                <input type="text" id="search" class="form-control" placeholder="Search Settings..." />
            </div>
        </div>
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

    <!-- Custom Modal -->
    <div id="customModal" class="modal-overlay">
        <div class="modal-container p-0">
            <div class="modal-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4>Add New Setting</h4>
                        <button type="button" class="close-btn" onclick="closeModal()">&times;</button>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('site.settings.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Group -->
                            <div class="mb-3">
                                <label class="form-label">Group</label>
                                <input type="text" name="group" class="form-control" placeholder="Eg. General, Appearance, System etc">
                                @error('group')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Key -->
                            <div class="mb-3">
                                <label class="form-label">Key <span class="text-danger">*</span></label>
                                <input type="text" name="key" class="form-control" placeholder="Eg. site_title, site_tagline, logo etc" required>
                            </div>

                            <!-- Type -->
                            <div class="mb-3">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-select" required onchange="changeInputType()">
                                    <option value="">-- Select Type --</option>
                                    <option value="text">Text</option>
                                    <option value="textarea">Textarea</option>
                                    <option value="number">Number</option>
                                    <option value="email">Email</option>
                                    <option value="url">URL</option>
                                    <option value="date">Date</option>
                                    <option value="password">Password</option>
                                    <option value="color">Color</option>
                                    <option value="boolean">Boolean</option>
                                    <option value="json">JSON</option>
                                    <option value="image">Image</option>
                                    <option value="file">File</option>
                                </select>
                            </div>

                            <!-- Value -->
                            <div class="mb-3" id="value-wrapper">
                                <label class="form-label">Value</label>
                                <input type="text" name="value" class="form-control" placeholder="Eg. Website name, Company phone etc">
                            </div>

                            <!-- Image Preview -->
                            <div id="imagePreviewWrapper" class="mt-3" style="display: none;">
                                <div class="position-relative">
                                    <img id="imagePreview" src="" alt="Image Preview" style="max-width: 100%; display: block;">
                                    <!-- Close button -->
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2" onclick="closePreview()"></button>
                                </div>
                            </div>

                            <!-- File Inputs (Image & File) -->
                            <div id="fileInputsWrapper" class="mt-3 d-none">
                                <!-- Image File -->
                                <label class="form-label">Upload Image</label>
                                <input type="file" id="imageInput" name="image_value" class="form-control" onchange="previewImage()">

                                <!-- File Input -->
                                <label class="form-label mt-3">Upload File</label>
                                <input type="file" id="fileInput" name="file_value" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-lg btn-primary btn-center mt-3">
                                Save Setting
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Function to open the modal
        function openModal() {
            document.getElementById('customModal').style.display = 'flex';
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById('customModal').style.display = 'none';
        }
    </script>

    <!-- JS -->
    <script>
        function changeInputType() {
            let type = document.getElementById('type').value;
            let wrapper = document.getElementById('value-wrapper');
            let fileInputsWrapper = document.getElementById('fileInputsWrapper');

            // Hide image preview when type changes
            document.getElementById('imagePreviewWrapper').style.display = 'none'; 
            document.getElementById('imageInput').value = ''; // Reset image input

            let html = '';

            switch (type) {
                case 'textarea':
                    html = `
                        <label class="form-label">Value</label>
                        <textarea name="value" class="form-control" rows="4"></textarea>
                    `;
                    break;

                case 'boolean':
                    html = `
                        <label class="form-label">Value</label>
                        <select name="value" class="form-select">
                            <option value="1">True</option>
                            <option value="0">False</option>
                        </select>
                    `;
                    break;

                case 'json':
                    html = `
                        <label class="form-label">Value (JSON)</label>
                        <textarea name="value" class="form-control" rows="4" placeholder='{"key":"value"}'></textarea>
                    `;
                    break;

                case 'image':
                    html = `
                        <label class="form-label">Upload Image</label>
                        <input type="file" id="imageInput" name="value" class="form-control" onchange="previewImage()">
                    `;
                    break;

                case 'file':
                    html = `
                        <label class="form-label">Upload File</label>
                        <input type="file" id="fileInput" name="value" class="form-control">
                    `;
                    break;

                case 'number':
                    html = `
                        <label class="form-label">Value</label>
                        <input type="number" name="value" class="form-control">
                    `;
                    break;

                case 'email':
                    html = `
                        <label class="form-label">Value</label>
                        <input type="email" name="value" class="form-control">
                    `;
                    break;

                case 'url':
                    html = `
                        <label class="form-label">Value</label>
                        <input type="url" name="value" class="form-control">
                    `;
                    break;

                case 'date':
                    html = `
                        <label class="form-label">Value</label>
                        <input type="date" name="value" class="form-control">
                    `;
                    break;

                case 'password':
                    html = `
                        <label class="form-label">Value</label>
                        <input type="password" name="value" class="form-control">
                    `;
                    break;

                case 'color':
                    html = `
                        <label class="form-label">Value</label>
                        <input type="color" name="value" class="form-control">
                    `;
                    break;

                default:
                    html = `
                        <label class="form-label">Value</label>
                        <input type="text" name="value" class="form-control">
                    `;
            }

            wrapper.innerHTML = html;
        }

        function previewImage() {
            const file = document.getElementById('imageInput').files[0]; // Get file from input
            const reader = new FileReader(); // Create file reader to preview the image

            reader.onload = function(e) {
                const preview = document.getElementById('imagePreview');
                preview.src = e.target.result; // Set preview image src
                document.getElementById('imagePreviewWrapper').style.display = 'block';  // Show the image preview section
            }

            // Read the file as a data URL
            reader.readAsDataURL(file);
        }

        function closePreview() {
            // Hide the image preview section
            document.getElementById('imagePreviewWrapper').style.display = 'none'; 
            // Reset the file input and value field
            document.getElementById('imageInput').value = ''; 
            document.getElementById('value-wrapper').innerHTML = `
                <label class="form-label">Value</label>
                <input type="text" name="value" class="form-control">
            `;
        }
    </script>

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