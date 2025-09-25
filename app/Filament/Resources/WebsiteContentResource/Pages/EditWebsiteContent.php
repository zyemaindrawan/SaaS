<?php

namespace App\Filament\Resources\WebsiteContentResource\Pages;

use App\Filament\Resources\WebsiteContentResource;
use App\Models\WebsiteContent;
use App\Models\Template;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditWebsiteContent extends EditRecord
{
    protected static string $resource = WebsiteContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->icon('heroicon-o-eye')
                ->color('info'),
            
            Actions\Action::make('preview')
                ->label('Preview Website')
                ->icon('heroicon-o-globe-alt')
                ->color('success')
                ->url(fn (): string => $this->record->getPublicUrl())
                ->openUrlInNewTab()
                ->visible(fn (): bool => $this->record->canPreview()),
            
            Actions\Action::make('reset_content')
                ->label('Reset to Template Defaults')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('warning')
                ->action(function () {
                    $this->resetToTemplateDefaults();
                })
                ->requiresConfirmation()
                ->modalHeading('Reset Content Data')
                ->modalDescription('This will replace all current content with template default values. This action cannot be undone.')
                ->modalSubmitActionLabel('Reset Content'),
            
            Actions\Action::make('change_template')
                ->label('Change Template')
                ->icon('heroicon-o-document-duplicate')
                ->color('info')
                ->form([
                    \Filament\Forms\Components\Select::make('new_template_slug')
                        ->label('New Template')
                        ->options(Template::active()->pluck('name', 'slug'))
                        ->required()
                        ->searchable()
                        ->helperText('Changing template will reset content data to new template defaults'),
                ])
                ->action(function (array $data) {
                    $this->changeTemplate($data['new_template_slug']);
                })
                ->requiresConfirmation()
                ->modalHeading('Change Website Template')
                ->modalDescription('This will change the template and reset content data. Current content will be lost.')
                ->visible(fn (): bool => in_array($this->record->status, ['draft', 'previewed'])),
            
            Actions\Action::make('duplicate')
                ->label('Duplicate Website')
                ->icon('heroicon-o-document-duplicate')
                ->color('gray')
                ->action(function () {
                    $this->duplicateWebsite();
                })
                ->requiresConfirmation()
                ->modalHeading('Duplicate Website')
                ->modalDescription('This will create a copy of this website with the same content for the same user.')
                ->modalSubmitActionLabel('Duplicate'),
            
            Actions\Action::make('change_status')
                ->label('Change Status')
                ->icon('heroicon-o-flag')
                ->color('warning')
                ->form([
                    \Filament\Forms\Components\Select::make('new_status')
                        ->label('New Status')
                        ->options([
                            'draft' => 'Draft',
                            'previewed' => 'Previewed',
                            'paid' => 'Paid',
                            'active' => 'Active',
                            'suspended' => 'Suspended',
                        ])
                        ->required()
                        ->default($this->record->status),
                    
                    \Filament\Forms\Components\DateTimePicker::make('activated_at')
                        ->label('Activated At')
                        ->helperText('Set activation date (only for active status)')
                        ->visible(fn (\Filament\Forms\Get $get) => $get('new_status') === 'active'),
                ])
                ->action(function (array $data) {
                    $updateData = ['status' => $data['new_status']];
                    
                    if ($data['new_status'] === 'active' && isset($data['activated_at'])) {
                        $updateData['activated_at'] = $data['activated_at'];
                    } elseif ($data['new_status'] === 'active' && !$this->record->activated_at) {
                        $updateData['activated_at'] = now();
                    }
                    
                    $this->record->update($updateData);
                    $this->notify('success', "Status changed to {$data['new_status']}");
                }),
            
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->before(function () {
                    // Check if there are active payments
                    $activePayments = $this->record->payments()->whereIn('status', ['pending', 'paid'])->count();
                    if ($activePayments > 0) {
                        Notification::make()
                            ->danger()
                            ->title('Cannot Delete Website')
                            ->body('This website has active payments. Please resolve them first.')
                            ->persistent()
                            ->send();
                        
                        $this->halt();
                    }
                }),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Website Content Updated')
            ->body('The website content has been updated successfully.')
            ->duration(5000);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure subdomain uniqueness if changed
        if (isset($data['subdomain']) && $data['subdomain'] !== $this->record->getOriginal('subdomain')) {
            $originalSubdomain = $data['subdomain'];
            $counter = 1;
            
            while (WebsiteContent::where('subdomain', $data['subdomain'])
                ->where('id', '!=', $this->record->id)
                ->exists()) {
                $data['subdomain'] = $originalSubdomain . '-' . $counter;
                $counter++;
            }
        }
        
        // Ensure custom domain uniqueness if changed
        if (isset($data['custom_domain']) && $data['custom_domain'] !== $this->record->getOriginal('custom_domain')) {
            if (WebsiteContent::where('custom_domain', $data['custom_domain'])
                ->where('id', '!=', $this->record->id)
                ->exists()) {
                
                Notification::make()
                    ->danger()
                    ->title('Domain Already Exists')
                    ->body('This custom domain is already in use by another website.')
                    ->persistent()
                    ->send();
                
                $this->halt();
            }
        }
        
        // Auto-set activated_at when status changes to active
        if (isset($data['status']) && $data['status'] === 'active' && 
            $this->record->getOriginal('status') !== 'active' && 
            !$data['activated_at']) {
            $data['activated_at'] = now();
        }
        
        return $data;
    }

    protected function afterSave(): void
    {
        // Log the update
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->record)
            ->withProperties([
                'old_status' => $this->record->getOriginal('status'),
                'new_status' => $this->record->status,
            ])
            ->log('Website content updated');
    }

    private function resetToTemplateDefaults(): void
    {
        if (!$this->record->template) {
            $this->notify('danger', 'Cannot reset: Template not found');
            return;
        }
        
        $config = $this->record->template->getConfigData();
        if (!isset($config['fields'])) {
            $this->notify('warning', 'No template configuration found');
            return;
        }
        
        $defaultData = [];
        foreach ($config['fields'] as $field) {
            $defaultData[$field['key']] = $field['default'] ?? '';
        }
        
        $this->record->update(['content_data' => $defaultData]);
        $this->notify('success', 'Content has been reset to template defaults');
    }

    private function changeTemplate(string $newTemplateSlug): void
    {
        $newTemplate = Template::where('slug', $newTemplateSlug)->first();
        if (!$newTemplate) {
            $this->notify('danger', 'New template not found');
            return;
        }
        
        // Get default content for new template
        $config = $newTemplate->getConfigData();
        $defaultData = [];
        
        if (isset($config['fields'])) {
            foreach ($config['fields'] as $field) {
                $defaultData[$field['key']] = $field['default'] ?? '';
            }
        }
        
        $this->record->update([
            'template_slug' => $newTemplateSlug,
            'content_data' => $defaultData,
        ]);
        
        $this->notify('success', "Template changed to {$newTemplate->name} and content reset");
    }

    private function duplicateWebsite(): void
    {
        $duplicate = $this->record->replicate();
        $duplicate->status = 'draft';
        $duplicate->activated_at = null;
        $duplicate->subdomain = $this->generateUniqueSubdomain($this->record->subdomain);
        $duplicate->custom_domain = null; // Clear custom domain for duplicate
        $duplicate->expires_at = now()->addYear();
        $duplicate->save();
        
        $this->notify('success', 'Website duplicated successfully');
        $this->redirect(WebsiteContentResource::getUrl('edit', ['record' => $duplicate]));
    }

    private function generateUniqueSubdomain(string $baseSubdomain): string
    {
        $subdomain = $baseSubdomain . '-copy';
        $counter = 1;
        
        while (WebsiteContent::where('subdomain', $subdomain)->exists()) {
            $subdomain = $baseSubdomain . '-copy-' . $counter;
            $counter++;
        }
        
        return $subdomain;
    }

    public function getTitle(): string
    {
        return "Edit: {$this->record->user->name}'s Website";
    }

    public function getSubheading(): ?string
    {
        return "Template: {$this->record->template->name} • Status: {$this->record->status}";
    }
}
