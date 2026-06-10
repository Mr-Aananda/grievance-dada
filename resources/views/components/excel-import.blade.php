<div class="file-upload-container">
    <label for="{{ $name }}" class="file-upload-label">{{ $label }}</label>

    <!-- File Upload Box with Bootstrap Icon -->
    <div class="file-upload-box" onclick="document.getElementById('{{ $name }}').click();">
        <!-- Bootstrap Cloud Upload Icon -->
        <i class="bi bi-cloud-upload-fill file-upload-icon"></i>

        <!-- File Upload Text -->
        <p class="file-upload-text">Click or Drag to Upload</p>

        <!-- Actual file input (hidden) -->
        <input type="file" name="{{ $name }}" id="{{ $name }}" accept="{{ $accept }}" class="file-input" onchange="updateFileName(this)">
    </div>

    <!-- Display Chosen Filename -->
    <p id="file-name" class="file-name">No file selected</p>
</div>

<!-- JavaScript to Update Filename Display -->
<script>
    function updateFileName(input) {
        const fileName = input.files.length > 0 ? input.files[0].name : 'No file selected';
        document.getElementById('file-name').textContent = fileName;
    }
</script>


<style>
    /* File Upload Container */
    .file-upload-container {
        margin-bottom: 1.5rem;
        max-width: 450px;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }

    .file-upload-label {
        display: block;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #333;
        text-align: center;
    }

    .file-upload-box {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border: 3px solid #E27C22;
        border-radius: 15px;
        padding: 2rem;
        background: #fff;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 4px 15px rgba(226, 124, 34, 0.2);
    }

    .file-upload-box:hover {
        background-color: #ffecdb;
        box-shadow: 0 6px 18px rgba(226, 124, 34, 0.3);
    }

    .file-upload-icon {
        font-size: 3rem;
        color: #E27C22;
        margin-bottom: 10px;
        transition: color 0.3s ease;
    }

    .file-upload-box:hover .file-upload-icon {
        color: #fff;
    }

    .file-upload-text {
        color: #E27C22;
        font-size: 1.1rem;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .file-upload-box:hover .file-upload-text {
        color: #fff;
    }

    .file-input {
        display: none;
    }

    .file-name {
        margin-top: 0.75rem;
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
        text-align: center;
    }
</style>
