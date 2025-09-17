<!DOCTYPE html>
<html lang="id">
<head>
    @include('templates.corporate.components.seo-head', [
        'content' => $content,
        'websiteContent' => $website ?? null,
        'config' => $config
    ])
</head>
<body>
    @include('templates.corporate.components.analytics-body', [
        'content' => $content
    ])

    @include('templates.corporate.components.header', [
        'content' => $content,
        'config' => $config
    ])

    @include('templates.corporate.components.hero', [
        'content' => $content,
        'config' => $config
    ])
    
    @if(!empty($content['services']))
        @include('templates.corporate.components.services', [
            'content' => $content,
            'config' => $config
        ])
    @endif
    
    @if(!empty($content['gallery_photos']))
        @include('templates.corporate.components.gallery', [
            'content' => $content,
            'config' => $config
        ])
    @endif
    
    @include('templates.corporate.components.footer', [
        'content' => $content,
        'config' => $config
    ])

    @include('templates.corporate.components.footer', [
        'content' => $content,
        'config' => $config
    ])

    @include('templates.corporate.components.whatsapp-float', [
        'content' => $content,
        'config' => $config
    ])
    
    @include('templates.corporate.components.analytics-scripts', [
        'content' => $content
    ])
</body>
</html>