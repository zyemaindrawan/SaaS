<?php

namespace App\Filament\Resources\WebsiteContentResource\Pages;

use App\Filament\Resources\WebsiteContentResource;
use App\Models\WebsiteContent;
use App\Models\Template;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class CreateWebsiteContent extends CreateRecord
{
    protected static string $resource = WebsiteContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back to Websites')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(WebsiteContentResource::getUrl('index')),
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
            ->title('Website Content Created')
            ->body('The website content has been created successfully. You can now preview and configure it.')
            ->duration(6000);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate subdomain if not provided
        if (empty($data['subdomain'])) {
            $data['subdomain'] = $this->generateUniqueSubdomain($data);
        }
        
        // Set default expiry date if not provided (1 year from now)
        if (empty($data['expires_at'])) {
            $data['expires_at'] = now()->addYear();
        }
        
        // Initialize content_data with template defaults if empty
        if (empty($data['content_data']) && !empty($data['template_slug'])) {
            $data['content_data'] = $this->getDefaultContentData($data['template_slug']);
        }
        
        // Set default status if not provided
        if (empty($data['status'])) {
            $data['status'] = 'draft';
        }
        
        return $data;
    }

    protected function afterCreate(): void
    {
        $websiteContent = $this->record;
        
        // Log the creation
        activity()
            ->causedBy(auth()->user())
            ->performedOn($websiteContent)
            ->withProperties([
                'template_slug' => $websiteContent->template_slug,
                'status' => $websiteContent->status,
            ])
            ->log('Website content created');
    }

    private function generateUniqueSubdomain(array $data): string
    {
        // Get user info for subdomain generation
        $user = \App\Models\User::find($data['user_id']);
        if (!$user) {
            $baseSubdomain = 'website-' . Str::random(6);
        } else {
            $baseSubdomain = Str::slug($user->name);
        }
        
        $subdomain = $baseSubdomain;
        $counter = 1;
        
        // Ensure subdomain is unique
        while (WebsiteContent::where('subdomain', $subdomain)->exists()) {
            $subdomain = $baseSubdomain . '-' . $counter;
            $counter++;
        }
        
        return $subdomain;
    }

    private function getDefaultContentData(string $templateSlug): array
    {
        $template = Template::where('slug', $templateSlug)->first();
        if (!$template) {
            return [];
        }
        
        $config = $template->getConfigData();
        if (!isset($config['fields'])) {
            return [];
        }
        
        $defaultData = [];
        foreach ($config['fields'] as $field) {
            $defaultData[$field['key']] = $field['default'] ?? '';
        }
        
        return $defaultData;
    }

    public function getTitle(): string
    {
        return 'Create New Website Content';
    }

    public function getSubheading(): ?string
    {
        return 'Create a new website for a user with selected template';
    }
}
