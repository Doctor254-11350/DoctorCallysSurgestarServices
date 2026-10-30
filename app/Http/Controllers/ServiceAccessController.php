<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class ServiceAccessController extends Controller
{
    public function show(Request $request, string $service)
    {
        $services = [

            'education' => [
                'name' => 'Educational Resources',
                'resources' => [
                    [
                        'title' => 'Khan Academy',
                        'url' => 'https://www.khanacademy.org/',
                    ],
                    [
                        'title' => 'Coursera',
                        'url' => 'https://www.coursera.org/',
                    ],
                    [
                        'title' => 'edX',
                        'url' => 'https://www.edx.org/',
                    ],
                    [
                        'title' => 'MIT OpenCourseWare',
                        'url' => 'https://ocw.mit.edu/',
                    ],
                    [
                        'title' => 'Google Digital Garage',
                        'url' => 'https://grow.google/',
                    ],
                ],
            ],

            'programming' => [
                'name' => 'Programming & Coding',
                'resources' => [
                    [
                        'title' => 'W3Schools',
                        'url' => 'https://www.w3schools.com/',
                    ],
                    [
                        'title' => 'MDN Web Docs',
                        'url' => 'https://developer.mozilla.org/',
                    ],
                    [
                        'title' => 'PHP Documentation',
                        'url' => 'https://www.php.net/docs.php',
                    ],
                    [
                        'title' => 'Laravel Documentation',
                        'url' => 'https://laravel.com/docs',
                    ],
                    [
                        'title' => 'Python Documentation',
                        'url' => 'https://docs.python.org/3/',
                    ],
                ],
            ],

            'website' => [
                'name' => 'Website Development',
                'resources' => [
                    [
                        'title' => 'WordPress',
                        'url' => 'https://wordpress.org/',
                    ],
                    [
                        'title' => 'GitHub',
                        'url' => 'https://github.com/',
                    ],
                    [
                        'title' => 'Netlify',
                        'url' => 'https://www.netlify.com/',
                    ],
                    [
                        'title' => 'Vercel',
                        'url' => 'https://vercel.com/',
                    ],
                    [
                        'title' => 'Google Search Central',
                        'url' => 'https://developers.google.com/search',
                    ],
                ],
            ],

            'online' => [
                'name' => 'Online Services',
                'resources' => [
                    [
                        'title' => 'Canva',
                        'url' => 'https://www.canva.com/',
                    ],
                    [
                        'title' => 'Google Workspace',
                        'url' => 'https://workspace.google.com/',
                    ],
                    [
                        'title' => 'Microsoft 365',
                        'url' => 'https://www.microsoft.com/microsoft-365',
                    ],
                    [
                        'title' => 'Google Drive',
                        'url' => 'https://drive.google.com/',
                    ],
                    [
                        'title' => 'Upwork',
                        'url' => 'https://www.upwork.com/',
                    ],
                ],
            ],

        ];

        abort_unless(isset($services[$service]), 404);

        $serviceDetails = $services[$service];

        // Check whether this customer has paid for this service.
        $hasAccess = Payment::where('user_id', $request->user()->id)
            ->where('status', 'paid')
            ->where('service_name', $serviceDetails['name'])
            ->exists();

        if (!$hasAccess) {
            return redirect()
    ->route('checkout', ['service' => $service])
    ->with('error', 'Please purchase this service to access its resources.');
        }

        return view('service', [
            'title' => $serviceDetails['name'],
            'description' => 'Access the learning resources and tools available in this service.',
            'items' => collect($serviceDetails['resources'])
                ->pluck('title')
                ->all(),
            'resources' => $serviceDetails['resources'],
        ]);
    }
}