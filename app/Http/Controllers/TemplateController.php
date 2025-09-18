<?php
// app/Http/Controllers/TemplateController.php
namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $sortDirection = $request->get('direction', 'asc');

        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', $sortDirection);
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
        }

        $templates = $query->paginate(12)->withQueryString();

        // Get unique categories for filter
        $categories = Template::where('is_active', true)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        // Get price range for filter
        $priceRange = Template::where('is_active', true)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        return view('templates.index', compact(
            'templates', 
            'categories', 
            'priceRange',
            'request'
        ));
    }

    public function show(Template $template)
    {
        if (!$template->is_active) {
            abort(404);
        }

        // Get related templates from same category
        $relatedTemplates = Template::where('is_active', true)
            ->where('category', $template->category)
            ->where('id', '!=', $template->id)
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        return view('templates.show', compact('template', 'relatedTemplates'));
    }

}
