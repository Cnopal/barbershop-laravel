@extends('customer.sidebar')

@section('content')
<!-- In your main layout file -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<div class="customer-page ai-hair-page min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                AI Hair Style Recommendation
            </h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Upload your photo and let our AI analyze your face shape to suggest the perfect hairstyle
            </p>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column - Upload Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Upload Your Photo</h2>
                        <p class="text-gray-500">Get personalized hairstyle recommendations</p>
                    </div>
                </div>

                <form action="{{ route('customer.ai-hair.analyze') }}" method="POST" enctype="multipart/form-data" id="uploadForm" class="space-y-6">
                    @csrf
                    
                    <!-- Camera Capture -->
                    <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Camera Capture</h3>
                                <p class="text-sm text-gray-600">Open your camera, snap a frontal photo, then analyze it.</p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" id="openCameraBtn" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-blue-700">
                                    Open Camera
                                </button>
                                <button type="button" id="capturePhotoBtn" class="hidden inline-flex items-center justify-center rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-green-700">
                                    Snap Photo
                                </button>
                                <button type="button" id="retakePhotoBtn" class="hidden inline-flex items-center justify-center rounded-xl bg-purple-600 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-purple-700">
                                    Retake
                                </button>
                                <button type="button" id="closeCameraBtn" class="hidden inline-flex items-center justify-center rounded-xl bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-gray-800">
                                    Close
                                </button>
                            </div>
                        </div>

                        <div id="cameraPanel" class="hidden mt-5">
                            <div class="camera-frame rounded-2xl shadow-inner">
                                <video id="cameraVideo" autoplay playsinline muted></video>
                            </div>
                            <canvas id="cameraCanvas" class="hidden"></canvas>
                        </div>

                        <p id="cameraStatus" class="mt-3 text-sm text-gray-600"></p>
                    </div>
                    <!-- Drag & Drop Upload Area -->
                    <div class="relative">
                        <input type="file" name="image" id="image" class="hidden" accept="image/*" capture="user" required>
                        
                        <label for="image" class="cursor-pointer block">
                            <div id="dropArea" class="border-3 border-dashed border-gray-300 rounded-2xl p-8 text-center transition-all duration-300 hover:border-blue-400 hover:bg-blue-50">
                                <div class="max-w-sm mx-auto">
                                    <div class="w-20 h-20 bg-gradient-to-r from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Click to upload or drag & drop</h3>
                                    <p class="text-gray-500 text-sm mb-4">JPG, PNG, GIF up to 5MB</p>
                                </div>
                            </div>
                        </label>

                        <div id="imagePreview" class="mt-4 hidden rounded-2xl border border-gray-200 bg-gray-50 p-4 text-center">
                            <img id="previewImage" class="max-h-64 mx-auto rounded-xl shadow-md" src="" alt="Preview">
                            <div id="fileName" class="mt-3 text-sm text-gray-600 hidden"></div>
                            <button type="button" id="changePhotoBtn" class="mt-4 inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100">
                                Choose Another Photo
                            </button>
                        </div>
                    </div>

                    <!-- Tips Section -->
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-blue-500 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">For Best Results:</h4>
                                <ul class="space-y-2 text-gray-600">
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Use a clear, well-lit photo</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Face should be looking directly at camera</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Remove glasses and hats if possible</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Use a plain background for better detection</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" id="analyzeBtn" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold py-4 px-6 rounded-xl hover:from-blue-600 hover:to-purple-700 transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl">
                            <svg id="loadingSpinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span id="btnText">Analyze My Face</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Column - Information -->
            <div class="space-y-8">
                <!-- How It Works -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">How It Works</h3>
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-100 to-blue-200 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                <span class="text-blue-600 font-bold text-lg">1</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Upload Photo</h4>
                                <p class="text-gray-600">Take or upload a clear frontal photo of your face</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-100 to-purple-200 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                <span class="text-purple-600 font-bold text-lg">2</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">AI Analysis</h4>
                                <p class="text-gray-600">Our AI analyzes facial landmarks and proportions</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-100 to-green-200 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                <span class="text-green-600 font-bold text-lg">3</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Get Recommendations</h4>
                                <p class="text-gray-600">Receive personalized hairstyle suggestions</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Supported Face Shapes -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Supported Face Shapes</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl hover:shadow-md transition-shadow">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full mx-auto mb-3 flex items-center justify-center">
                                <span class="text-white font-bold">O</span>
                            </div>
                            <span class="font-semibold text-gray-900">Oval</span>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl hover:shadow-md transition-shadow">
                            <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-green-600 rounded-full mx-auto mb-3 flex items-center justify-center">
                                <span class="text-white font-bold">R</span>
                            </div>
                            <span class="font-semibold text-gray-900">Round</span>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl hover:shadow-md transition-shadow">
                            <div class="w-16 h-16 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full mx-auto mb-3 flex items-center justify-center">
                                <span class="text-white font-bold">S</span>
                            </div>
                            <span class="font-semibold text-gray-900">Square</span>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-red-50 to-red-100 rounded-xl hover:shadow-md transition-shadow">
                            <div class="w-16 h-16 bg-gradient-to-r from-red-400 to-red-600 rounded-full mx-auto mb-3 flex items-center justify-center">
                                <span class="text-white font-bold">H</span>
                            </div>
                            <span class="font-semibold text-gray-900">Heart</span>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl hover:shadow-md transition-shadow">
                            <div class="w-16 h-16 bg-gradient-to-r from-purple-400 to-purple-600 rounded-full mx-auto mb-3 flex items-center justify-center">
                                <span class="text-white font-bold">D</span>
                            </div>
                            <span class="font-semibold text-gray-900">Diamond</span>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl hover:shadow-md transition-shadow">
                            <div class="w-16 h-16 bg-gradient-to-r from-indigo-400 to-indigo-600 rounded-full mx-auto mb-3 flex items-center justify-center">
                                <span class="text-white font-bold">O</span>
                            </div>
                            <span class="font-semibold text-gray-900">Oblong</span>
                        </div>
                    </div>
                </div>

                <!-- FAQ -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Frequently Asked Questions</h3>
                    <div class="space-y-4">
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <h4 class="font-semibold text-gray-900 mb-1">Is my photo data secure?</h4>
                            <p class="text-gray-600 text-sm">Yes, all photos are processed securely and are not stored on our servers.</p>
                        </div>
                        <div class="border-l-4 border-purple-500 pl-4 py-2">
                            <h4 class="font-semibold text-gray-900 mb-1">How accurate is the analysis?</h4>
                            <p class="text-gray-600 text-sm">Our AI uses advanced facial recognition with over 95% accuracy for well-lit, frontal photos.</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <h4 class="font-semibold text-gray-900 mb-1">Can I get recommendations for different lengths?</h4>
                            <p class="text-gray-600 text-sm">Yes, our recommendations include various hair lengths suitable for your face shape.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-8 max-w-md mx-4">
        <div class="text-center">
            <div class="w-20 h-20 bg-gradient-to-r from-green-100 to-green-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Upload Successful!</h3>
            <p class="text-gray-600 mb-6">Your image is being analyzed. Please wait...</p>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                <div id="progressBar" class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full w-0 transition-all duration-500"></div>
            </div>
            <p id="progressText" class="text-sm text-gray-500">Analyzing facial features...</p>
        </div>
    </div>
</div>

<style>
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .float-animation {
        animation: float 3s ease-in-out infinite;
    }
    
    .drag-over {
        border-color: #3B82F6 !important;
        background-color: #EFF6FF !important;
    }

    .camera-frame {
        aspect-ratio: 4 / 3;
        background: #111827;
        overflow: hidden;
    }

    .camera-frame video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(-1);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('image');
        const dropArea = document.getElementById('dropArea');
        const fileName = document.getElementById('fileName');
        const imagePreview = document.getElementById('imagePreview');
        const previewImage = document.getElementById('previewImage');
        const changePhotoBtn = document.getElementById('changePhotoBtn');
        const uploadForm = document.getElementById('uploadForm');
        const analyzeBtn = document.getElementById('analyzeBtn');
        const btnText = document.getElementById('btnText');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const successModal = document.getElementById('successModal');
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        const openCameraBtn = document.getElementById('openCameraBtn');
        const capturePhotoBtn = document.getElementById('capturePhotoBtn');
        const retakePhotoBtn = document.getElementById('retakePhotoBtn');
        const closeCameraBtn = document.getElementById('closeCameraBtn');
        const cameraPanel = document.getElementById('cameraPanel');
        const cameraVideo = document.getElementById('cameraVideo');
        const cameraCanvas = document.getElementById('cameraCanvas');
        const cameraStatus = document.getElementById('cameraStatus');

        const maxSize = 5 * 1024 * 1024;
        let cameraStream = null;

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropArea.classList.add('drag-over');
        }

        function unhighlight() {
            dropArea.classList.remove('drag-over');
        }

        dropArea.addEventListener('drop', function(e) {
            handleFiles(e.dataTransfer.files, 'upload');
        }, false);

        fileInput.addEventListener('change', function() {
            handleFiles(this.files, 'upload');
        });

        openCameraBtn.addEventListener('click', startCamera);
        retakePhotoBtn.addEventListener('click', startCamera);
        capturePhotoBtn.addEventListener('click', capturePhoto);
        closeCameraBtn.addEventListener('click', function() {
            stopCamera();
            cameraStatus.textContent = 'Camera closed.';
        });
        changePhotoBtn.addEventListener('click', resetSelectedImage);
        window.addEventListener('beforeunload', stopCamera);

        async function startCamera() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                showError('Camera is not supported in this browser. Please upload a photo instead.');
                cameraStatus.textContent = 'Camera is not supported in this browser.';
                return;
            }

            try {
                stopCamera(false);
                cameraStatus.textContent = 'Opening camera...';
                cameraStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user',
                        width: { ideal: 1280 },
                        height: { ideal: 960 }
                    },
                    audio: false
                });

                cameraVideo.srcObject = cameraStream;
                await cameraVideo.play();

                cameraPanel.classList.remove('hidden');
                capturePhotoBtn.classList.remove('hidden');
                closeCameraBtn.classList.remove('hidden');
                openCameraBtn.classList.add('hidden');
                retakePhotoBtn.classList.add('hidden');
                cameraStatus.textContent = 'Camera ready.';
            } catch (error) {
                console.error('Camera error:', error);
                cameraStatus.textContent = 'Camera permission denied or unavailable. Try upload instead.';
                showError('Unable to open camera. Please allow camera access or upload a photo.');
            }
        }

        function stopCamera(hidePanel = true) {
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }

            cameraVideo.srcObject = null;
            capturePhotoBtn.classList.add('hidden');
            closeCameraBtn.classList.add('hidden');
            openCameraBtn.classList.remove('hidden');

            if (hidePanel) {
                cameraPanel.classList.add('hidden');
            }
        }

        function capturePhoto() {
            if (!cameraStream || !cameraVideo.videoWidth || !cameraVideo.videoHeight) {
                showError('Camera is not ready yet');
                return;
            }

            cameraCanvas.width = cameraVideo.videoWidth;
            cameraCanvas.height = cameraVideo.videoHeight;
            const context = cameraCanvas.getContext('2d');
            context.drawImage(cameraVideo, 0, 0, cameraCanvas.width, cameraCanvas.height);

            cameraCanvas.toBlob(blob => {
                if (!blob) {
                    showError('Unable to capture photo. Please try again.');
                    return;
                }

                const file = new File([blob], `ai-hair-camera-${Date.now()}.jpg`, { type: 'image/jpeg' });
                if (!setFileInput(file)) {
                    return;
                }
                handleFiles(fileInput.files, 'camera');
                stopCamera();
                retakePhotoBtn.classList.remove('hidden');
                cameraStatus.textContent = 'Photo captured. Ready to analyze.';
            }, 'image/jpeg', 0.92);
        }

        function setFileInput(file) {
            if (typeof DataTransfer === 'undefined') {
                showError('Your browser cannot attach camera photos automatically. Please upload a photo instead.');
                return false;
            }

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            return true;
        }

        function resetSelectedImage() {
            fileInput.value = '';
            previewImage.src = '';
            fileName.textContent = '';
            fileName.classList.add('hidden');
            imagePreview.classList.add('hidden');
            dropArea.classList.remove('hidden');
            retakePhotoBtn.classList.add('hidden');
            cameraStatus.textContent = '';
        }

        function handleFiles(files, source = 'upload') {
            if (files.length === 0) return;

            const file = files[0];

            if (!file.type.match('image.*')) {
                showError('Please select an image file (JPG, PNG, GIF)');
                return;
            }

            if (file.size > maxSize) {
                showError('File size exceeds 5MB limit');
                return;
            }

            if (source === 'upload') {
                stopCamera();
                retakePhotoBtn.classList.add('hidden');
                cameraStatus.textContent = '';
            }

            fileName.textContent = `Selected: ${file.name}`;
            fileName.classList.remove('hidden');

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                imagePreview.classList.remove('hidden');
                dropArea.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }

        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const files = fileInput.files;

            if (files.length === 0) {
                showError('Please select or snap an image first');
                return;
            }

            const file = files[0];

            if (!file.type.match('image.*')) {
                showError('Please select an image file (JPG, PNG, GIF)');
                return;
            }

            if (file.size > maxSize) {
                showError('File size exceeds 5MB limit');
                return;
            }

            stopCamera();
            loadingSpinner.classList.remove('hidden');
            btnText.textContent = 'Analyzing...';
            analyzeBtn.disabled = true;
            analyzeBtn.classList.remove('hover:from-blue-600', 'hover:to-purple-700', 'hover:-translate-y-1', 'hover:shadow-xl');
            successModal.classList.remove('hidden');

            let progress = 0;
            const interval = setInterval(() => {
                progress += 5;
                progressBar.style.width = `${progress}%`;

                if (progress <= 30) {
                    progressText.textContent = 'Uploading image...';
                } else if (progress <= 60) {
                    progressText.textContent = 'Detecting facial features...';
                } else if (progress <= 90) {
                    progressText.textContent = 'Analyzing face shape...';
                } else {
                    progressText.textContent = 'Generating recommendations...';
                }

                if (progress >= 100) {
                    clearInterval(interval);
                }
            }, 100);

            setTimeout(() => {
                uploadForm.submit();
            }, 500);
        });

        function showError(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg transform translate-x-full opacity-0 transition-all duration-300 z-50';
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');
            }, 10);

            setTimeout(() => {
                toast.classList.remove('translate-x-0', 'opacity-100');
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 5000);
        }

        successModal.addEventListener('click', function(e) {
            if (e.target === successModal) {
                successModal.classList.add('hidden');
            }
        });
    });
</script>
@endsection
