@section('title', 'Import Buyers')

<x-app-layout>
    <!-- Header Widget -->
    <div class="widget mb-3">
        <div class="widget-body d-flex align-items-center">
            @include('pages.buyer.menu')

            <div class="ms-auto">
                <button type="button" class="btn icon lg rounded" title="Go back" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Body Widget -->
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="widget shadow-sm border-0">
                <div class="widget-body">
                    <form action="{{ route('buyer.import') }}" method="POST" enctype="multipart/form-data"
                          onsubmit="return confirmAndShowLoading();">
                        @csrf

                        <!-- File Upload -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold w-100">
                                <span class="d-flex align-items-center gap-2">
                                    <i class="bi bi-file-earmark-spreadsheet-fill fs-4 text-primary"></i>
                                    Select Excel File <span class="text-danger">*</span>
                                </span>
                            </label>

                            <div id="file-drop" class="border border-dashed rounded p-4 text-center position-relative" style="cursor: pointer;">
                                <i class="bi bi-upload fs-1 text-muted"></i>
                                <p id="file-text" class="mt-2 text-muted small">Drag & Drop your file here or click to select</p>
                                <input type="file"
                                       name="file"
                                       id="file-input"
                                       class="form-control position-absolute top-0 start-0 w-100 h-100 opacity-0"
                                       accept=".xlsx,.xls,.csv"
                                       required>
                            </div>

                            @error('file')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror

                            <div class="form-text mt-1 small">
                                Allowed formats: <strong>.xlsx, .xls, .csv</strong>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <x-form.reset />
                            <x-form.save id="submit-btn" name="Import Buyers" />
                        </div>
                    </form>

                    <!-- Sample Download -->
                    <div class="text-end mt-3">
                        <a href="{{ route('buyer.export') }}" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-file-earmark-spreadsheet-fill me-1"></i>
                            Download Sample Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" style="display:none; position:fixed; inset:0; background:rgba(255,255,255,0.8); z-index:9999; text-align:center;">
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 fw-semibold">Importing data, please wait...</p>
        </div>
    </div>

    <!-- JS -->
    <script>
        // File input display
        const fileInput = document.getElementById('file-input');
        const fileText = document.getElementById('file-text');

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileText.textContent = this.files[0].name;
            } else {
                fileText.textContent = "Drag & Drop your file here or click to select";
            }
        });

        // Confirm and show loading
        function confirmAndShowLoading() {
            if(confirm('Are you sure you want to import this Excel file?')) {
                document.getElementById('loading-overlay').style.display = 'block';
                document.getElementById('submit-btn').disabled = true; // Prevent double submission
                return true;
            }
            return false;
        }
    </script>
</x-app-layout>
