<?php

namespace Database\Seeders;

use App\Models\WebsiteContent;
use App\Models\User;
use Illuminate\Database\Seeder;

class WebsiteContentSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'demo@demo.com')->first();

        WebsiteContent::create([
            'user_id' => $user->id,
            'template_slug' => 'corporate',
            'website_name' => 'Demo Corp',
            'content_data' => [
                'company_name' => 'Demo Corp Indonesia',
                'slogan' => 'Solusi Digital Bisnis Anda',
                'hero_title' => 'Solusi Bisnis Terbaik',
                'hero_subtitle' => 'Kami memberikan layanan terbaik untuk mengembangkan bisnis Anda',
                'primary_color' => '#2146ff',
                'secondary_color' => '#64748b',
                
                // WhatsApp Configuration
                'whatsapp_enabled' => true,
                'whatsapp_number' => '6281234567890',
                'whatsapp_message' => 'Halo {company_name}, saya tertarik dengan layanan Anda dan ingin konsultasi lebih lanjut.',
                'whatsapp_position' => 'bottom-right',
                'whatsapp_color' => '#25D366',
                'whatsapp_greeting_text' => 'Butuh bantuan? Chat dengan kami!',
                
                // Contact info
                'contact_email' => 'info@democorp.com',
                'contact_phone' => '+62 812-3456-7890',
                
                'services' => [
                    [
                        'title' => 'Konsultasi Bisnis',
                        'description' => 'Strategi dan perencanaan bisnis yang tepat sasaran',
                        'icon' => 'fas fa-chart-line'
                    ],
                    [
                        'title' => 'Digital Marketing',
                        'description' => 'Solusi digital marketing untuk meningkatkan penjualan',
                        'icon' => 'fas fa-laptop-code'
                    ]
                ]
            ],
            'subdomain' => 'democorp',
            'status' => 'active'
        ]);
    }
}
