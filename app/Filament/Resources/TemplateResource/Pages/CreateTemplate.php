<?php

namespace App\Filament\Resources\TemplateResource\Pages;

use App\Filament\Resources\TemplateResource;
use App\Models\Template;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\File;

class CreateTemplate extends CreateRecord
{
    protected static string $resource = TemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back to Templates')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(TemplateResource::getUrl('index')),
        ];
    }

    protected function getCreateAnotherFormAction(): Actions\Action
    {
        // Hide "Create & create another" button as requested
        return parent::getCreateAnotherFormAction()->hidden();
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Template Created')
            ->body('The template has been created successfully. Don\'t forget to create the template files in the resources/views/templates directory.')
            ->duration(8000);
    }

    protected function afterCreate(): void
    {
        $template = $this->record;
        
        // Create template directory structure
        $this->createTemplateDirectory($template);
        
        // Create config.json file
        $this->createConfigFile($template);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure slug is unique and properly formatted
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['name']);
        
        // Set default sort order if not provided
        if (!isset($data['sort_order'])) {
            $data['sort_order'] = Template::max('sort_order') + 1;
        }
        
        return $data;
    }

    private function generateUniqueSlug(string $name): string
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;
        
        while (Template::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    private function createTemplateDirectory(Template $template): void
    {
        $templatePath = resource_path("views/templates/{$template->slug}");
        
        if (!File::exists($templatePath)) {
            File::makeDirectory($templatePath, 0755, true);
            
            // Create basic template files
            $this->createTemplateFiles($template, $templatePath);
        }
    }

    private function createTemplateFiles(Template $template, string $path): void
    {
        // Create main template file
        $indexContent = <<<BLADE
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \$content['site_title'] ?? '{$template->name}' }}</title>
    <meta name="description" content="{{ \$content['site_description'] ?? '{$template->description}' }}">
    <!-- Add your styles here -->
</head>
<body>
    <header>
        <h1>{{ \$content['site_title'] ?? '{$template->name}' }}</h1>
    </header>
    
    <main>
        <section class="hero">
            <h2>{{ \$content['hero_title'] ?? 'Welcome to Our Website' }}</h2>
            <p>{{ \$content['hero_description'] ?? 'This is a sample template.' }}</p>
        </section>
        
        <section class="content">
            <p>{{ \$content['main_content'] ?? 'Add your main content here.' }}</p>
        </section>
    </main>
    
    <footer>
        <p>&copy; {{ date('Y') }} {{ \$content['site_title'] ?? '{$template->name}' }}. All rights reserved.</p>
    </footer>
</body>
</html>
BLADE;
        
        File::put($path . '/index.blade.php', $indexContent);
        
        // Create preview template
        $previewContent = <<<BLADE
@extends('layouts.preview')

@section('title', '{$template->name} - Preview')

@section('content')
    @include('templates.{$template->slug}.index')
@endsection
BLADE;
        
        File::put($path . '/preview.blade.php', $previewContent);
    }

    private function createConfigFile(Template $template): void
    {
        $configPath = resource_path("views/templates/{$template->slug}/config.json");
        
        $config = [
            'name' => $template->name,
            'description' => $template->description,
            'category' => $template->category,
            'price' => $template->price,
            'version' => '1.0.0',
            'author' => 'DR-SaaS',
            'fields' => [
                [
                    'key' => 'site_title',
                    'label' => 'Site Title',
                    'type' => 'text',
                    'required' => true,
                    'default' => $template->name
                ],
                [
                    'key' => 'site_description',
                    'label' => 'Site Description',
                    'type' => 'textarea',
                    'required' => false,
                    'default' => $template->description
                ],
                [
                    'key' => 'hero_title',
                    'label' => 'Hero Title',
                    'type' => 'text',
                    'required' => true,
                    'default' => 'Welcome to Our Website'
                ],
                [
                    'key' => 'hero_description',
                    'label' => 'Hero Description',
                    'type' => 'textarea',
                    'required' => false,
                    'default' => 'This is a sample template description.'
                ],
                [
                    'key' => 'main_content',
                    'label' => 'Main Content',
                    'type' => 'rich_text',
                    'required' => false,
                    'default' => 'Add your main content here.'
                ]
            ]
        ];
        
        File::put($configPath, json_encode($config, JSON_PRETTY_PRINT));
    }

    public function getTitle(): string
    {
        return 'Create New Template';
    }

    public function getSubheading(): ?string
    {
        return 'Create a new website template with configuration files';
    }
}
