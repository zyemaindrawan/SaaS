<?php

namespace App\Filament\Resources\TemplateResource\Pages;

use App\Filament\Resources\TemplateResource;
use App\Models\Template;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\File;

class EditTemplate extends EditRecord
{
    protected static string $resource = TemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Preview Template')
                ->icon('heroicon-o-globe-alt')
                ->color('success')
                ->url(fn (): string => route('templates.show', $this->record->slug))
                ->openUrlInNewTab(),
            
            Actions\Action::make('sync_config')
                ->label('Sync Config File')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->action(function () {
                    $this->syncConfigFile();
                })
                ->requiresConfirmation()
                ->modalHeading('Sync Configuration File')
                ->modalDescription('This will update the config.json file to match the current template settings in the database.')
                ->modalSubmitActionLabel('Sync Config'),
            
            Actions\Action::make('edit_config')
                ->label('Edit Config File')
                ->icon('heroicon-o-cog')
                ->color('info')
                ->form([
                    \Filament\Forms\Components\Textarea::make('config_content')
                        ->label('Configuration JSON')
                        ->rows(20)
                        ->default(function () {
                            $configPath = resource_path("views/templates/{$this->record->slug}/config.json");
                            if (file_exists($configPath)) {
                                return file_get_contents($configPath);
                            }
                            return json_encode($this->getDefaultConfig(), JSON_PRETTY_PRINT);
                        })
                        ->helperText('Edit the template configuration in JSON format')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $this->updateConfigFile($data['config_content']);
                })
                ->modalWidth('4xl'),
            
            Actions\Action::make('regenerate_files')
                ->label('Regenerate Template Files')
                ->icon('heroicon-o-document-plus')
                ->color('gray')
                ->action(function () {
                    $this->regenerateTemplateFiles();
                })
                ->requiresConfirmation()
                ->modalHeading('Regenerate Template Files')
                ->modalDescription('This will recreate the basic template files. Existing files will be backed up.')
                ->modalSubmitActionLabel('Regenerate Files'),

        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Template Updated')
            ->body('The template has been updated successfully.')
            ->duration(5000);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // If slug is changed, we need to handle directory rename
        if (isset($data['slug']) && $data['slug'] !== $this->record->getOriginal('slug')) {
            $this->handleSlugChange($this->record->getOriginal('slug'), $data['slug']);
        }
        
        return $data;
    }

    protected function afterSave(): void
    {
        // Update config file to reflect changes
        $this->syncConfigFile();
    }

    private function handleSlugChange(string $oldSlug, string $newSlug): void
    {
        $oldPath = resource_path("views/templates/{$oldSlug}");
        $newPath = resource_path("views/templates/{$newSlug}");
        
        if (File::exists($oldPath) && !File::exists($newPath)) {
            File::move($oldPath, $newPath);
        }
    }

    private function syncConfigFile(): void
    {
        $configPath = resource_path("views/templates/{$this->record->slug}/config.json");
        $configDir = dirname($configPath);
        
        if (!File::exists($configDir)) {
            File::makeDirectory($configDir, 0755, true);
        }
        
        $config = [
            'name' => $this->record->name,
            'description' => $this->record->description,
            'category' => $this->record->category,
            'price' => $this->record->price,
            'is_active' => $this->record->is_active,
            'sort_order' => $this->record->sort_order,
            'version' => '1.0.0',
            'author' => 'DR-SaaS',
            'updated_at' => now()->toISOString(),
        ];
        
        // Merge with existing config to preserve field definitions
        if (File::exists($configPath)) {
            $existingConfig = json_decode(File::get($configPath), true);
            if (isset($existingConfig['fields'])) {
                $config['fields'] = $existingConfig['fields'];
            }
            if (isset($existingConfig['version'])) {
                $config['version'] = $existingConfig['version'];
            }
        }
        
        File::put($configPath, json_encode($config, JSON_PRETTY_PRINT));
        
        Notification::make()
            ->success()
            ->title('Success')
            ->body("Configuration file synchronized")
            ->send();
    }

    private function updateConfigFile(string $content): void
    {
        try {
            // Validate JSON
            $decoded = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON format');
            }
            
            $configPath = resource_path("views/templates/{$this->record->slug}/config.json");
            $configDir = dirname($configPath);
            
            if (!File::exists($configDir)) {
                File::makeDirectory($configDir, 0755, true);
            }
            
            File::put($configPath, $content);
            
                Notification::make()
                    ->success()
                    ->title('Success')
                    ->body("Configuration file updated successfully")
                    ->send();
        } catch (\Exception $e) {
            Notification::make()
                    ->danger()
                    ->title('Error')
                    ->body("Failed to update config file: " . $e->getMessage())
                    ->send();
        }
    }

    private function regenerateTemplateFiles(): void
    {
        $templatePath = resource_path("views/templates/{$this->record->slug}");
        
        // Create backup if files exist
        if (File::exists($templatePath)) {
            $backupPath = $templatePath . '_backup_' . now()->format('Y-m-d_H-i-s');
            File::copyDirectory($templatePath, $backupPath);
        } else {
            File::makeDirectory($templatePath, 0755, true);
        }
        
        $this->createBasicTemplateFiles($templatePath);
        
        Notification::make()
            ->success()
            ->title('Success')
            ->body("Template files regenerated. Backup created if files existed.")
            ->send(); 
    }

    private function createBasicTemplateFiles(string $path): void
    {
        // Create main template file
        $templateName = $this->record->name;
        $templateDescription = $this->record->description;
        
        $indexContent = <<<BLADE
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \$content['site_title'] ?? '{$templateName}' }}</title>
    <meta name="description" content="{{ \$content['site_description'] ?? '{$templateDescription}' }}">
    <!-- Add your styles here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        header {
            background: #333;
            color: white;
            padding: 1rem 0;
        }
        main {
            padding: 2rem 0;
        }
        .hero {
            background: #f4f4f4;
            padding: 2rem 0;
            text-align: center;
        }
        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>{{ \$content['site_title'] ?? '{$templateName}' }}</h1>
        </div>
    </header>
    
    <main>
        <section class="hero">
            <div class="container">
                <h2>{{ \$content['hero_title'] ?? 'Welcome to Our Website' }}</h2>
                <p>{{ \$content['hero_description'] ?? 'This is a sample template.' }}</p>
            </div>
        </section>
        
        <section class="content">
            <div class="container">
                {!! \$content['main_content'] ?? '<p>Add your main content here.</p>' !!}
            </div>
        </section>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} {{ \$content['site_title'] ?? '{$templateName}' }}. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
BLADE;
        
        File::put($path . '/index.blade.php', $indexContent);
        
        // Create CSS file
        $cssContent = <<<CSS
/* {$templateName} Template Styles */

:root {
    --primary-color: #007bff;
    --secondary-color: #6c757d;
    --success-color: #28a745;
    --danger-color: #dc3545;
    --warning-color: #ffc107;
    --info-color: #17a2b8;
    --light-color: #f8f9fa;
    --dark-color: #343a40;
}

/* Add your custom styles here */
CSS;
        
        File::put($path . '/style.css', $cssContent);
        
        // Create README file - Fixed the ternary operator issue
        $priceText = $this->record->price > 0 
            ? 'Rp ' . number_format($this->record->price, 0, ',', '.') 
            : 'Free';
            
        $readmeContent = <<<MARKDOWN
# {$templateName} Template

## Description
{$templateDescription}

## Category
{$this->record->category}

## Price
{$priceText}

## Files
- `index.blade.php` - Main template file
- `style.css` - Template styles
- `config.json` - Template configuration
- `preview.png` - Template preview image

## Configuration
Check the `config.json` file for available fields and their configurations.

## Development
1. Edit the template files in this directory
2. Update the `config.json` file to define available fields
3. Test the template in the preview mode
4. Update the preview image

---
Generated by DR-SaaS Template System
MARKDOWN;
        
        File::put($path . '/README.md', $readmeContent);
    }

    private function getDefaultConfig(): array
    {
        return [
            'name' => $this->record->name,
            'description' => $this->record->description,
            'category' => $this->record->category,
            'price' => $this->record->price,
            'version' => '1.0.0',
            'author' => 'DR-SaaS',
            'fields' => [
                [
                    'key' => 'site_title',
                    'label' => 'Site Title',
                    'type' => 'text',
                    'required' => true,
                    'default' => $this->record->name
                ],
                [
                    'key' => 'site_description',
                    'label' => 'Site Description',
                    'type' => 'textarea',
                    'required' => false,
                    'default' => $this->record->description
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
                    'default' => '<p>Add your main content here.</p>'
                ]
            ]
        ];
    }

    public function getTitle(): string
    {
        return "Edit {$this->record->name}";
    }

    public function getSubheading(): ?string
    {
        return "Slug: {$this->record->slug} • Category: {$this->record->category}";
    }
}
