<?php
namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\WebsiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function create(Template $template)
    {
        if (!$template->is_active) {
            abort(404);
        }

        $user = Auth::user();

        // Check if user already has a website content with this template in draft/previewed status
        $existingContent = WebsiteContent::where('user_id', $user->id)
            ->where('template_slug', $template->slug)
            ->whereIn('status', ['draft', 'previewed'])
            ->first();

        if ($existingContent) {
            return redirect()->route('content.edit', $existingContent)
                ->with('info', 'You already have a draft with this template. Please complete it first.');
        }

        // Create website content
        $websiteContent = WebsiteContent::create([
            'user_id' => $user->id,
            'template_slug' => $template->slug,
            'content_data' => $this->getDefaultContentData($template),
            'status' => 'draft',
            'subdomain' => $this->generateSubdomain($user->name),
            'expires_at' => now()->addYear(),
        ]);

        // If template is free, skip payment
        if ($template->price == 0) {
            $websiteContent->update(['status' => 'paid']);
            
            return redirect()->route('content.edit', $websiteContent)
                ->with('success', 'Free template activated! You can now customize your website.');
        }

        // Redirect to payment
        return redirect()->route('payment.checkout', $websiteContent);
    }

    private function getDefaultContentData(Template $template): array
    {
        // Basic default content structure
        $user = Auth::user();
        
        return [
            'site_title' => $user->name . "'s Website",
            'site_description' => 'Welcome to my website',
            'contact_email' => $user->email,
            'contact_phone' => $user->phone ?? '',
            'primary_color' => '#2563eb',
            'secondary_color' => '#1e40af',
        ];
    }

    private function generateSubdomain(string $name): string
    {
        $baseSubdomain = Str::slug(strtolower($name));
        $subdomain = $baseSubdomain;
        $counter = 1;

        while (WebsiteContent::where('subdomain', $subdomain)->exists()) {
            $subdomain = $baseSubdomain . '-' . $counter;
            $counter++;
        }

        return $subdomain;
    }
}
