<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiHairController extends Controller
{
    public function index()
    {
        return view('customer.hairstyles.ai-hair');
    }

    public function analyze(Request $request)
    {
        // Validate file
        $request->validate([
            'image' => 'required|image|max:5120|mimes:jpg,jpeg,png,gif',
        ], [
            'image.required' => 'Please select an image file',
            'image.image' => 'The file must be an image',
            'image.max' => 'The image size cannot exceed 5MB',
            'image.mimes' => 'Only JPG, JPEG, PNG, and GIF files are allowed'
        ]);

        try {
            // Get the uploaded file
            $image = $request->file('image');
            
            // Log file info for debugging
            Log::info('AI Hair Analysis - Uploaded file:', [
                'name' => $image->getClientOriginalName(),
                'size' => $image->getSize(),
                'mime' => $image->getMimeType()
            ]);
            
            // Call FastAPI
            $fastapiUrl = env('FASTAPI_URL', 'http://localhost:8000/face-shape');
            
            Log::info('Calling FastAPI:', ['url' => $fastapiUrl]);
            
            $response = Http::timeout(60) // Increase timeout to 60 seconds
                ->retry(3, 1000) // Retry 3 times with 1 second delay
                ->attach(
                    'file',
                    file_get_contents($image->getRealPath()),
                    $image->getClientOriginalName()
                )->post($fastapiUrl);

            // Check response status
            if ($response->failed()) {
                Log::error('FastAPI request failed:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                $data = [
                    'success' => false,
                    'error' => 'API Service Unavailable',
                    'message' => 'Unable to connect to the face analysis service. Please try again later.'
                ];
                
                return view('customer.hairstyles.air-hair-result', compact('data'));
            }

            // Get JSON data
            $apiData = $response->json();
            
            // Debug: Log API response
            Log::info('FastAPI Response:', $apiData);
            
            // Process the response
            $data = $this->processApiData($apiData);
            
            // Tampilkan result
            return view('customer.hairstyles.air-hair-result', [
                'data' => $data,
                'original_filename' => $image->getClientOriginalName()
            ]);

        } catch (\Exception $e) {
            Log::error('AI Hair Analysis Error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            $data = [
                'success' => false,
                'error' => 'Processing Error',
                'message' => 'Failed to process the image: ' . $e->getMessage(),
                'suggestions' => [
                    'Check your internet connection',
                    'Make sure the image file is not corrupted',
                    'Try again with a different image',
                    'Contact support if the problem persists'
                ]
            ];
            
            return view('customer.hairstyles.air-hair-result', compact('data'));
        }
    }
    
    /**
     * Process API data for view
     */
    private function processApiData($apiData)
    {
        // If API returned an error
        if (isset($apiData['error'])) {
            return [
                'success' => false,
                'error' => $apiData['error'],
                'suggestions' => $apiData['suggestions'] ?? [
                    'Ensure your face is clearly visible in the photo',
                    'Make sure there is good lighting',
                    'Face should be looking directly at the camera',
                    'Avoid wearing hats or sunglasses'
                ]
            ];
        }
        
        // Success response from advanced algorithm
        if (isset($apiData['face_shape'])) {
            return [
                'success' => true,
                'face_shape' => $apiData['face_shape'],
                'confidence' => $apiData['confidence'] ?? '85%',
                'recommendations' => $apiData['recommendations'] ?? [],
                'recommendation_details' => $this->recommendationDetails($apiData['recommendations'] ?? []),
                'description' => $apiData['description'] ?? $this->getDefaultDescription($apiData['face_shape']),
                'characteristics' => $apiData['characteristics'] ?? $this->getDefaultCharacteristics($apiData['face_shape']),
                'measurements_mm' => $apiData['measurements_mm'] ?? $this->getDefaultMeasurements($apiData['face_shape']),
                'proportions' => $apiData['proportions'] ?? $this->getDefaultProportions($apiData['face_shape']),
                'detection_quality' => $apiData['detection_quality'] ?? [
                    'landmarks_detected' => true,
                    'image_processed' => true,
                    'algorithm_used' => 'Advanced Face Analysis'
                ],
                'algorithm_used' => $apiData['algorithm'] ?? 'advanced'
            ];
        }
        
        // Unexpected response format
        return [
            'success' => false,
            'error' => 'Unexpected response format from analysis service',
            'message' => 'The face analysis service returned data in an unexpected format.'
        ];
    }

    private function recommendationDetails(array $recommendations): array
    {
        return collect($recommendations)
            ->mapWithKeys(fn ($recommendation) => [
                (string) $recommendation => $this->hairstyleDetail((string) $recommendation),
            ])
            ->all();
    }

    private function hairstyleDetail(string $recommendation): array
    {
        $catalog = $this->hairstyleCatalog();

        if (isset($catalog[$recommendation])) {
            return $catalog[$recommendation];
        }

        $normalizedRecommendation = strtolower(trim($recommendation));

        foreach ($catalog as $name => $style) {
            if (strtolower($name) === $normalizedRecommendation) {
                return $style;
            }
        }

        return array_merge($catalog['Generic Salon Haircut'], [
            'name' => $recommendation,
            'description' => 'A balanced salon-ready hairstyle that can be customized based on your hair type and lifestyle.',
        ]);
    }

    private function hairstyleCatalog(): array
    {
        $sprite = 'images/hairstyles/hairstyle-sprite.png';
        $styles = [
            'Classic Pompadour' => [
                'description' => 'Volume on top with short sides for a timeless look.',
                'sprite_col' => 0,
                'sprite_row' => 0,
            ],
            'Side Part' => [
                'description' => 'Clean and professional with a defined part.',
                'sprite_col' => 1,
                'sprite_row' => 0,
            ],
            'Textured Undercut' => [
                'description' => 'Modern contrast with textured top and short sides.',
                'sprite_col' => 2,
                'sprite_row' => 0,
            ],
            'Layered Medium Length' => [
                'description' => 'Versatile layers that add movement and dimension.',
                'sprite_col' => 3,
                'sprite_row' => 0,
            ],
            'Modern Quiff' => [
                'description' => 'Contemporary volume with a textured finish.',
                'sprite_col' => 4,
                'sprite_row' => 0,
            ],
            'Slick Back' => [
                'description' => 'Sleek, polished styling for a sophisticated look.',
                'sprite_col' => 5,
                'sprite_row' => 0,
            ],
            'Textured Crop' => [
                'description' => 'Low-maintenance short hair with modern texture.',
                'sprite_col' => 0,
                'sprite_row' => 1,
            ],
            'High Fade Quiff' => [
                'description' => 'Sharp fade combined with a lifted, voluminous top.',
                'sprite_col' => 1,
                'sprite_row' => 1,
            ],
            'Side Part with Fade' => [
                'description' => 'Classic side-part styling with clean faded edges.',
                'sprite_col' => 2,
                'sprite_row' => 1,
            ],
            'Angular Fringe' => [
                'description' => 'A shaped fringe that frames the face with definition.',
                'sprite_col' => 3,
                'sprite_row' => 1,
            ],
            'Spiky Hair' => [
                'description' => 'Defined, edgy texture with extra lift on top.',
                'sprite_col' => 4,
                'sprite_row' => 1,
            ],
            'Asymmetric Cut' => [
                'description' => 'A modern uneven silhouette for a more unique profile.',
                'sprite_col' => 5,
                'sprite_row' => 1,
            ],
            'Buzz Cut' => [
                'description' => 'Ultra-short, neat, and easy to maintain.',
                'sprite_col' => 0,
                'sprite_row' => 2,
            ],
            'Crew Cut' => [
                'description' => 'Traditional short cut with clean, masculine lines.',
                'sprite_col' => 1,
                'sprite_row' => 2,
            ],
            'French Crop' => [
                'description' => 'Short sides with a blunt, textured fringe.',
                'sprite_col' => 2,
                'sprite_row' => 2,
            ],
            'Faux Hawk' => [
                'description' => 'A modern mohawk-inspired style with wearable volume.',
                'sprite_col' => 3,
                'sprite_row' => 2,
            ],
            'Short Textured' => [
                'description' => 'Short length with natural-looking texture and shape.',
                'sprite_col' => 4,
                'sprite_row' => 2,
            ],
            'Flat Top' => [
                'description' => 'A structured flat top with a bold retro edge.',
                'sprite_col' => 5,
                'sprite_row' => 2,
            ],
            'Side Swept Fringe' => [
                'description' => 'Soft fringe swept to one side for a relaxed finish.',
                'sprite_col' => 0,
                'sprite_row' => 3,
            ],
            'Medium Length Layers' => [
                'description' => 'Face-framing layers with a natural medium-length flow.',
                'sprite_col' => 1,
                'sprite_row' => 3,
            ],
            'Long Top Short Sides' => [
                'description' => 'Strong length contrast with styling flexibility on top.',
                'sprite_col' => 2,
                'sprite_row' => 3,
            ],
            'Textured Quiff' => [
                'description' => 'A lifted quiff with added texture and movement.',
                'sprite_col' => 3,
                'sprite_row' => 3,
            ],
            'Messy Layers' => [
                'description' => 'Intentional messy layers for a casual, textured look.',
                'sprite_col' => 4,
                'sprite_row' => 3,
            ],
            'Textured Fringe' => [
                'description' => 'A forward fringe with natural texture and soft shape.',
                'sprite_col' => 5,
                'sprite_row' => 3,
            ],
            'Side Part Pompadour' => [
                'description' => 'A classic side part blended with pompadour volume.',
                'sprite_col' => 0,
                'sprite_row' => 4,
            ],
            'Modern Caesar Cut' => [
                'description' => 'A contemporary short cut with a compact fringe.',
                'sprite_col' => 1,
                'sprite_row' => 4,
            ],
            'Full Fringe' => [
                'description' => 'Full fringe coverage that softens the forehead area.',
                'sprite_col' => 2,
                'sprite_row' => 4,
            ],
            'Layered Cut with Bangs' => [
                'description' => 'Layered movement with bangs to frame the face.',
                'sprite_col' => 3,
                'sprite_row' => 4,
            ],
            'Side Swept' => [
                'description' => 'A natural side-swept finish with easy everyday styling.',
                'sprite_col' => 4,
                'sprite_row' => 4,
            ],
            'Generic Salon Haircut' => [
                'description' => 'A balanced salon-ready hairstyle that can be customized to your face shape.',
                'sprite_col' => 5,
                'sprite_row' => 4,
            ],
        ];

        return collect($styles)
            ->mapWithKeys(fn ($style, $name) => [$name => array_merge($style, [
                'name' => $name,
                'image' => $sprite,
            ])])
            ->all();
    }
    
    /**
     * Get default description for face shape
     */
    private function getDefaultDescription($faceShape)
    {
        $descriptions = [
            'Oval' => 'An oval face shape is considered the most versatile and balanced. The length is about one and a half times the width, with a gently rounded jawline.',
            'Round' => 'A round face shape has approximately equal width and length, with fuller cheeks and a rounded jawline.',
            'Square' => 'A square face shape features a strong, angular jawline with the forehead, cheekbones, and jawline all having similar widths.',
            'Heart' => 'A heart-shaped face has a wider forehead and cheekbones with a narrow, pointed chin.',
            'Diamond' => 'A diamond face shape is characterized by wide cheekbones with a narrow forehead and jawline.',
            'Oblong' => 'An oblong face shape is longer than it is wide, with a straight cheek line and sometimes a rounded chin.'
        ];
        
        return $descriptions[$faceShape] ?? 'This face shape has balanced proportions and versatile styling options.';
    }
    
    /**
     * Get default characteristics for face shape
     */
    private function getDefaultCharacteristics($faceShape)
    {
        $characteristics = [
            'Oval' => 'Balanced proportions, gently rounded jaw, forehead slightly wider than chin',
            'Round' => 'Full cheeks, rounded jawline, width and length nearly equal',
            'Square' => 'Strong jawline, angular features, forehead and jaw similar width',
            'Heart' => 'Wide forehead, prominent cheekbones, narrow pointed chin',
            'Diamond' => 'Wide cheekbones, narrow forehead and chin, angular features',
            'Oblong' => 'Long face shape, straight cheek line, forehead and jaw similar width'
        ];
        
        return $characteristics[$faceShape] ?? 'Well-proportioned facial features with balanced characteristics.';
    }
    
    /**
     * Get default measurements for face shape
     */
    private function getDefaultMeasurements($faceShape)
    {
        // Default measurements based on average face sizes (in mm)
        $defaults = [
            'Oval' => [
                'jaw_width' => 125,
                'cheekbone_width' => 135,
                'forehead_width' => 140,
                'face_length' => 190,
                'inter_eye_distance' => 63,
                'face_area' => 20100
            ],
            'Round' => [
                'jaw_width' => 140,
                'cheekbone_width' => 145,
                'forehead_width' => 142,
                'face_length' => 170,
                'inter_eye_distance' => 63,
                'face_area' => 19300
            ],
            'Square' => [
                'jaw_width' => 150,
                'cheekbone_width' => 145,
                'forehead_width' => 148,
                'face_length' => 185,
                'inter_eye_distance' => 63,
                'face_area' => 21400
            ],
            'Heart' => [
                'jaw_width' => 115,
                'cheekbone_width' => 140,
                'forehead_width' => 145,
                'face_length' => 195,
                'inter_eye_distance' => 63,
                'face_area' => 21000
            ],
            'Diamond' => [
                'jaw_width' => 120,
                'cheekbone_width' => 150,
                'forehead_width' => 130,
                'face_length' => 190,
                'inter_eye_distance' => 63,
                'face_area' => 22300
            ],
            'Oblong' => [
                'jaw_width' => 130,
                'cheekbone_width' => 135,
                'forehead_width' => 132,
                'face_length' => 210,
                'inter_eye_distance' => 63,
                'face_area' => 22200
            ]
        ];
        
        return $defaults[$faceShape] ?? [
            'jaw_width' => 135,
            'cheekbone_width' => 140,
            'forehead_width' => 138,
            'face_length' => 185,
            'inter_eye_distance' => 63,
            'face_area' => 20350
        ];
    }
    
    /**
     * Get default proportions for face shape
     */
    private function getDefaultProportions($faceShape)
    {
        $defaults = [
            'Oval' => [
                'face_length_to_width' => 1.41,
                'jaw_to_cheek' => 0.93,
                'forehead_to_cheek' => 1.04
            ],
            'Round' => [
                'face_length_to_width' => 1.17,
                'jaw_to_cheek' => 0.97,
                'forehead_to_cheek' => 0.98
            ],
            'Square' => [
                'face_length_to_width' => 1.28,
                'jaw_to_cheek' => 1.03,
                'forehead_to_cheek' => 1.02
            ],
            'Heart' => [
                'face_length_to_width' => 1.39,
                'jaw_to_cheek' => 0.82,
                'forehead_to_cheek' => 1.04
            ],
            'Diamond' => [
                'face_length_to_width' => 1.27,
                'jaw_to_cheek' => 0.80,
                'forehead_to_cheek' => 0.87
            ],
            'Oblong' => [
                'face_length_to_width' => 1.56,
                'jaw_to_cheek' => 0.96,
                'forehead_to_cheek' => 0.98
            ]
        ];
        
        return $defaults[$faceShape] ?? [
            'face_length_to_width' => 1.32,
            'jaw_to_cheek' => 0.96,
            'forehead_to_cheek' => 0.99
        ];
    }
    
    /**
     * API Health Check endpoint
     */
    public function checkApiHealth()
    {
        try {
            $response = Http::timeout(10)
                ->get(env('FASTAPI_URL', 'http://localhost:8000/face-shape'));
            
            return response()->json([
                'status' => $response->successful() ? 'healthy' : 'unhealthy',
                'status_code' => $response->status(),
                'message' => $response->successful() ? 'API is running' : 'API is not responding'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot connect to API: ' . $e->getMessage()
            ], 500);
        }
    }
}
