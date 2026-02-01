@extends('customer.sidebar')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-white py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl mb-6 shadow-lg">
                <i class="fas fa-cut text-white text-2xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-3">
                Hair Style Analysis Result
            </h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Personalized recommendations based on your facial structure analysis
            </p>
            @if(isset($original_filename))
            <div class="inline-flex items-center px-4 py-2 bg-gray-100 rounded-full mt-4">
                <i class="fas fa-image text-gray-500 mr-2"></i>
                <span class="text-sm font-medium text-gray-700">{{ $original_filename }}</span>
            </div>
            @endif
        </div>

        <div class="card-body">
            @if(isset($data['success']) && $data['success'])
                <!-- Success Alert -->
                <div class="mb-8">
                    <div class="max-w-3xl mx-auto">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 rounded-r-lg p-6 shadow-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Analysis Complete</h3>
                                    <p class="mt-1 text-gray-600">Your face has been successfully analyzed with {{ $data['confidence'] ?? '95%' }} confidence</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Result Card -->
                <div class="max-w-6xl mx-auto mb-12">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                        <div class="md:flex">
                            <!-- Left Panel - Face Shape -->
                            <div class="md:w-2/5 bg-gradient-to-br from-blue-500 to-purple-600 p-8 md:p-12">
                                <div class="text-center text-white">
                                    <div class="inline-flex items-center justify-center w-24 h-24 bg-white/20 backdrop-blur-sm rounded-3xl mb-6">
                                        <i class="fas fa-user text-white text-4xl"></i>
                                    </div>
                                    <h2 class="text-4xl font-bold mb-2">{{ $data['face_shape'] }}</h2>
                                    <p class="text-blue-100 mb-6">Face Shape</p>
                                    
                                    <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full">
                                        <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse mr-2"></div>
                                        <span class="font-semibold">{{ $data['confidence'] ?? '95%' }}</span>
                                        <span class="ml-1 text-blue-100">confidence</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Panel - Description -->
                            <div class="md:w-3/5 p-8 md:p-12">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Face Analysis</h3>
                                    <p class="text-gray-700 mb-6 leading-relaxed">{{ $data['description'] }}</p>
                                    
                                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-6 border border-gray-100">
                                        <div class="flex items-start">
                                            <i class="fas fa-star text-yellow-500 mt-1 mr-3"></i>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 mb-2">Key Characteristics</h4>
                                                <p class="text-gray-700">{{ $data['characteristics'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Measurements Section -->
                <div class="max-w-6xl mx-auto mb-12">
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Face Measurements</h3>
                                <p class="text-gray-600">Precise facial dimensions in millimeters</p>
                            </div>
                            <div class="inline-flex items-center px-4 py-2 bg-blue-50 rounded-full">
                                <i class="fas fa-ruler-combined text-blue-500 mr-2"></i>
                                <span class="text-sm font-medium text-blue-700">All in mm</span>
                            </div>
                        </div>
                        
                        @if(isset($data['measurements_mm']))
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                            @php
                                $measurements = $data['measurements_mm'];
                                $measurementCards = [
                                    'jaw_width' => ['icon' => 'fa-square', 'label' => 'Jaw Width', 'color' => 'from-blue-400 to-blue-500'],
                                    'cheekbone_width' => ['icon' => 'fa-circle', 'label' => 'Cheekbone', 'color' => 'from-purple-400 to-purple-500'],
                                    'forehead_width' => ['icon' => 'fa-arrows-alt-h', 'label' => 'Forehead', 'color' => 'from-indigo-400 to-indigo-500'],
                                    'face_length' => ['icon' => 'fa-arrows-alt-v', 'label' => 'Face Length', 'color' => 'from-teal-400 to-teal-500'],
                                    'inter_eye_distance' => ['icon' => 'fa-eye', 'label' => 'Eye Distance', 'color' => 'from-emerald-400 to-emerald-500'],
                                    'face_area' => ['icon' => 'fa-expand', 'label' => 'Face Area', 'color' => 'from-cyan-400 to-cyan-500']
                                ];
                            @endphp
                            
                            @foreach($measurementCards as $key => $info)
                                @if(isset($measurements[$key]))
                                <div class="bg-gradient-to-br {{ $info['color'] }} rounded-xl p-6 text-white shadow-lg transform hover:-translate-y-1 transition-transform duration-300">
                                    <div class="flex items-center justify-center w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl mb-4 mx-auto">
                                        <i class="fas {{ $info['icon'] }} text-white text-lg"></i>
                                    </div>
                                    <h4 class="text-center font-semibold mb-2">{{ $info['label'] }}</h4>
                                    <div class="text-center">
                                        <span class="text-2xl font-bold">{{ $measurements[$key] }}</span>
                                        <span class="text-sm opacity-90 ml-1">{{ $key == 'face_area' ? 'mm²' : 'mm' }}</span>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Proportions Section -->
                <div class="max-w-6xl mx-auto mb-12">
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-8">Face Proportions</h3>
                        
                        @if(isset($data['proportions']))
                        <div class="space-y-6">
                            @php
                                $ratios = $data['proportions'];
                                $ratioInfo = [
                                    'face_length_to_width' => [
                                        'name' => 'Face Length to Width',
                                        'ideal' => [1.3, 1.5],
                                        'description' => 'Indicates face elongation'
                                    ],
                                    'jaw_to_cheek' => [
                                        'name' => 'Jaw to Cheek Ratio',
                                        'ideal' => [0.9, 1.0],
                                        'description' => 'Shows jaw prominence'
                                    ],
                                    'forehead_to_cheek' => [
                                        'name' => 'Forehead to Cheek Ratio',
                                        'ideal' => [0.95, 1.05],
                                        'description' => 'Indicates forehead width'
                                    ]
                                ];
                            @endphp
                            
                            @foreach($ratios as $key => $value)
                                @php
                                    $info = $ratioInfo[$key] ?? ['name' => ucfirst(str_replace('_', ' ', $key)), 'ideal' => [0, 1]];
                                    $percentage = min(100, max(0, ($value - $info['ideal'][0]) / ($info['ideal'][1] - $info['ideal'][0]) * 100));
                                    $isIdeal = $value >= $info['ideal'][0] && $value <= $info['ideal'][1];
                                @endphp
                                
                                <div class="border border-gray-100 rounded-xl p-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $info['name'] }}</h4>
                                            <p class="text-sm text-gray-600">{{ $info['description'] ?? '' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-2xl font-bold text-gray-900">{{ number_format($value, 3) }}</span>
                                            <div class="text-sm {{ $isIdeal ? 'text-green-600' : 'text-amber-600' }}">
                                                {{ $isIdeal ? 'Ideal' : 'Not ideal' }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <div class="flex justify-between text-sm text-gray-600">
                                            <span>Low</span>
                                            <span>Ideal Range</span>
                                            <span>High</span>
                                        </div>
                                        <div class="relative h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="absolute inset-0 flex">
                                                <div class="flex-1"></div>
                                                <div class="w-1/3 bg-gradient-to-r from-green-100 via-green-300 to-green-100"></div>
                                                <div class="flex-1"></div>
                                            </div>
                                            <div class="absolute top-1/2 left-0 right-0 h-1 bg-transparent">
                                                <div class="absolute w-3 h-3 bg-white border-2 {{ $isIdeal ? 'border-green-500' : 'border-amber-500' }} rounded-full shadow-lg transform -translate-y-1/2"
                                                     style="left: {{ $percentage }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Recommendations Section -->
                <div class="max-w-6xl mx-auto mb-12">
                    <div class="text-center mb-10">
                        <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-50 to-purple-50 rounded-full mb-4">
                            <i class="fas fa-cut text-blue-500 mr-2"></i>
                            <span class="font-semibold text-blue-700">Personalized Recommendations</span>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-900">Hair Styles for {{ $data['face_shape'] }} Face</h3>
                        <p class="text-gray-600 mt-2">Carefully selected to complement your facial features</p>
                    </div>
                    
                    @if(isset($data['recommendations']) && count($data['recommendations']) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($data['recommendations'] as $index => $recommendation)
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center mr-3">
                                            <span class="text-white font-bold">{{ $index + 1 }}</span>
                                        </div>
                                        <h4 class="font-bold text-gray-900">{{ $recommendation }}</h4>
                                    </div>
                                    <button class="text-blue-500 hover:text-blue-600" onclick="openModal({{ $index }})">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </div>
                                
                                @php
                                    $styleDescriptions = [
                                        'Classic Pompadour' => 'Volume on top with short sides for a timeless look',
                                        'Side Part' => 'Clean and professional with a defined part',
                                        'Textured Undercut' => 'Modern contrast with textured top and short sides',
                                        'Layered Medium Length' => 'Versatile layers that add movement and dimension',
                                        'Modern Quiff' => 'Contemporary volume with textured finish',
                                        'Slick Back' => 'Sleek, polished style for a sophisticated look',
                                        'Textured Crop' => 'Low maintenance with modern texture',
                                        'High Fade Quiff' => 'Sharp fade combined with voluminous top',
                                        'Side Part with Fade' => 'Classic styling with modern fade edges',
                                        'Angular Fringe' => 'Geometric fringe that frames the face',
                                        'Spiky Hair' => 'Edgy texture with defined spikes',
                                        'Asymmetric Cut' => 'Modern asymmetry for a unique look',
                                        'Buzz Cut' => 'Ultra-short and easy to maintain',
                                        'Crew Cut' => 'Traditional short cut with clean lines',
                                        'French Crop' => 'Short with a blunt, textured fringe',
                                        'Faux Hawk' => 'Modern take on the mohawk style',
                                        'Short Textured' => 'Short length with added texture',
                                        'Flat Top' => 'Classic flat top for retro style',
                                        'Side Swept Fringe' => 'Soft fringe swept to one side',
                                        'Medium Length Layers' => 'Face-framing layers at medium length',
                                        'Long Top Short Sides' => 'Dramatic contrast in length',
                                        'Textured Quiff' => 'Modern quiff with added texture',
                                        'Messy Layers' => 'Intentional messy, textured look',
                                        'Textured Fringe' => 'Fringe with natural texture',
                                        'Side Part Pompadour' => 'Combination of classic styles',
                                        'Modern Caesar Cut' => 'Contemporary short cut with fringe',
                                        'Full Fringe' => 'Full coverage fringe style',
                                        'Layered Cut with Bangs' => 'Layered cut complemented by bangs',
                                        'Side Swept' => 'Natural side-swept style'
                                    ];
                                @endphp
                                
                                <p class="text-gray-600 text-sm mb-4">
                                    {{ $styleDescriptions[$recommendation] ?? 'Perfectly complements your facial features and proportions' }}
                                </p>
                                
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Recommended
                                    </span>
                                    <button onclick="openModal({{ $index }})" 
                                            class="text-sm font-medium text-blue-600 hover:text-blue-700">
                                        View details →
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="max-w-6xl mx-auto">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="{{ route('customer.ai-hair.index') }}" 
                           class="w-full sm:w-auto px-8 py-4 bg-white border-2 border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-redo mr-3"></i>
                            Analyze Another Image
                        </a>
                        
                        <button onclick="window.print()"
                                class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl">
                            <i class="fas fa-print mr-3"></i>
                            Print Report
                        </button>
                        
                        
                    </div>
                </div>

                <!-- Recommendation Modal -->
                <div id="recommendationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-2xl max-w-md w-full max-h-[90vh] overflow-auto">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h3 id="modalTitle" class="text-xl font-bold text-gray-900"></h3>
                                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times text-lg"></i>
                                </button>
                            </div>
                            
                            <div id="modalContent" class="space-y-4"></div>
                            
                            <div class="mt-8 pt-6 border-t border-gray-100">
                                <div class="flex gap-3">
                                    <button onclick="closeModal()"
                                            class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                        Close
                                    </button>
                                    <button  class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all">
                                        <i class="fas fa-calendar mr-2"></i>
                                        <a href="{{ route('customer.appointments.create') }}">Book Appointment</a>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif(isset($data['error']))
                <!-- Error State -->
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-red-100 to-red-200 rounded-2xl mb-6">
                                <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-3">Analysis Failed</h2>
                            <p class="text-gray-600 mb-6">{{ $data['error'] }}</p>
                            
                            @if(isset($data['suggestions']))
                            <div class="bg-gray-50 rounded-xl p-6 mb-8">
                                <h4 class="font-semibold text-gray-900 mb-4">Suggestions:</h4>
                                <ul class="space-y-3">
                                    @foreach($data['suggestions'] as $suggestion)
                                    <li class="flex items-start">
                                        <i class="fas fa-lightbulb text-yellow-500 mt-1 mr-3"></i>
                                        <span class="text-gray-700">{{ $suggestion }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            
                            <a href="{{ route('customer.ai-hair.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300">
                                <i class="fas fa-camera mr-3"></i>
                                Try Again
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fadeInUp {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    .card-body > * {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
    
    .card-body > *:nth-child(1) { animation-delay: 0.1s; }
    .card-body > *:nth-child(2) { animation-delay: 0.2s; }
    .card-body > *:nth-child(3) { animation-delay: 0.3s; }
    .card-body > *:nth-child(4) { animation-delay: 0.4s; }
    .card-body > *:nth-child(5) { animation-delay: 0.5s; }
</style>

<script>
    // Modal functions
    function openModal(index) {
        const modal = document.getElementById('recommendationModal');
        const title = document.getElementById('modalTitle');
        const content = document.getElementById('modalContent');
        
        const recommendation = @json($data['recommendations'] ?? [])[index];
        const faceShape = @json($data['face_shape'] ?? '');
        
        const benefits = {
            'Oval': [
                'Balances your natural proportions',
                'Adds definition without overwhelming features',
                'Enhances your naturally harmonious features',
                'Complements your balanced facial structure'
            ],
            'Round': [
                'Creates angles to balance roundness',
                'Adds height to elongate your face',
                'Defines your jawline for more structure',
                'Adds dimension to soft features'
            ],
            'Square': [
                'Softens strong angles for harmony',
                'Adds texture to complement defined features',
                'Balances your prominent jawline',
                'Creates a more proportional appearance'
            ],
            'Heart': [
                'Balances your wider forehead',
                'Adds width at the jawline for harmony',
                'Softens a pointed chin',
                'Creates symmetry in your features'
            ],
            'Diamond': [
                'Adds width at the forehead area',
                'Softens prominent cheekbones',
                'Balances a narrow chin',
                'Creates overall facial harmony'
            ],
            'Oblong': [
                'Adds width to elongate face shape',
                'Breaks up length visually',
                'Adds volume at the sides',
                'Creates balanced proportions'
            ]
        };
        
        const shapeBenefits = benefits[faceShape] || [
            'Enhances your natural features',
            'Complements your facial proportions',
            'Creates balance and harmony',
            'Highlights your best features'
        ];
        
        title.textContent = recommendation;
        content.innerHTML = `
            <div class="space-y-4">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Why this style works:</h4>
                    <ul class="space-y-2">
                        ${shapeBenefits.map(benefit => `
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                <span class="text-gray-700">${benefit}</span>
                            </li>
                        `).join('')}
                    </ul>
                </div>
                
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4">
                    <div class="flex">
                        <i class="fas fa-lightbulb text-yellow-500 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Stylist Tip</h4>
                            <p class="text-gray-700 text-sm">Bring this recommendation to your hairstylist and discuss customization options based on your hair type and lifestyle.</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    function closeModal() {
        const modal = document.getElementById('recommendationModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
    
    // Close modal on outside click
    document.getElementById('recommendationModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // Save report functionality
    document.getElementById('saveReportBtn')?.addEventListener('click', function() {
        // Create a toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-xl shadow-lg transform translate-x-full opacity-0 transition-all duration-300 z-50';
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                <span>Report saved successfully!</span>
            </div>
        `;
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        }, 10);
        
        // Remove after 5 seconds
        setTimeout(() => {
            toast.classList.remove('translate-x-0', 'opacity-100');
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 5000);
        
        // Here you could add actual save functionality (API call to save to database, etc.)
        // For now, we'll just show the toast
    });
    
    // Initialize animations
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation classes
        const elements = document.querySelectorAll('.card-body > *');
        elements.forEach((el, index) => {
            el.style.animationDelay = `${(index + 1) * 0.1}s`;
        });
    });
</script>

<!-- Include Font Awesome for icons -->
 <!-- In your main layout file -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

@endsection