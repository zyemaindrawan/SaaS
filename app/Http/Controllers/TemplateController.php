<?php
namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = Template::where('is_active', true);

        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Filter by price range
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Search by name or description
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('description', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'sort_order');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
        }

        $templates = $query->paginate(12)->withQueryString();

        // Transform templates to include proper preview URLs
        $templates->getCollection()->transform(function ($template) {
            $template->preview_image = $template->getPreviewUrl();
            return $template;
        });

        // Get unique categories
        $categories = Template::where('is_active', true)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        // Get price range
        $priceRange = Template::where('is_active', true)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        return Inertia::render('Templates/Index', [
            'templates' => $templates,
            'categories' => $categories,
            'priceRange' => $priceRange,
            'filters' => $request->only(['search', 'category', 'price_min', 'price_max', 'sort']),
            'app' => [
                'name' => config('app.name'),
            ],
        ]);
    }

    public function show(Template $template)
    {
        if (!$template->is_active) {
            abort(404);
        }

        // Simulate loading delay for demo (remove in production)
        // sleep(1);

        // Get related templates
        $relatedTemplates = Template::where('is_active', true)
            ->where('category', $template->category)
            ->where('id', '!=', $template->id)
            ->orderBy('sort_order')
            ->limit(4)
            ->get(['id', 'name', 'slug', 'preview_image', 'price', 'category']);

        // Transform related templates to include proper preview URLs
        $relatedTemplates->transform(function ($relatedTemplate) {
            $relatedTemplate->preview_image = $relatedTemplate->getPreviewUrl();
            return $relatedTemplate;
        });

        // Get template with optimized data
        $templateData = [
            'id' => $template->id,
            'name' => $template->name,
            'slug' => $template->slug,
            'description' => $template->description,
            'category' => $template->category,
            'preview_image' => $template->getPreviewUrl(),
            'price' => $template->price,
            'demo_url' => $template->demo_url,
            'created_at' => $template->created_at,
            'features' => $this->getTemplateFeatures($template),
            'tech_specs' => $this->getTechSpecs($template),
        ];

        return Inertia::render('Templates/Show', [
            'template' => $templateData,
            'relatedTemplates' => $relatedTemplates,
            'breadcrumbs' => [
                ['name' => 'Templates', 'href' => '/templates'],
                ['name' => $template->name, 'href' => null],
            ],
            'app' => [
                'name' => config('app.name'),
            ],
        ]);
    }

    private function getTemplateFeatures($template)
    {
        return [
            'Responsive Design' => 'Optimized for all devices',
            'SEO Ready' => 'Built-in SEO optimization',
            'Fast Loading' => 'Optimized performance',
            'Modern Design' => 'Contemporary layouts',
            'Easy Customization' => 'Simple content management',
            'Cross Browser' => 'Works on all browsers',
            '24/7 Support' => 'Dedicated customer support',
            'Regular Updates' => 'Continuous improvements',
        ];
    }

    private function getTechSpecs($template)
    {
        return [
            'Framework' => 'Laravel + Vue.js',
            'Styling' => 'Tailwind CSS',
            'Database' => 'MySQL/PostgreSQL',
            'Hosting' => 'Cloud optimized',
            'SSL' => 'Free SSL certificate',
            'CDN' => 'Global content delivery',
        ];
    }

    public function preview(Template $template)
    {
        if (!$template->is_active || !$template->demo_url) {
            abort(404);
        }

        return redirect($template->demo_url);
    }
}
