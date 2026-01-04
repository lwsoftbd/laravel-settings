<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Setting</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap (optional but recommended) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header">
            <h4>Add New Setting</h4>
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

                <button type="submit" class="btn btn-primary mt-3">
                    Save Setting
                </button>
            </form>
        </div>
    </div>
</div>

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

</body>
</html>