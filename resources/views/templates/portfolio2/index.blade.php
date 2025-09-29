<!DOCTYPE html>
<html lang="id">
<head>
    @include('templates.portfolio2.components.seo-head', [
        'content' => $content,
        'websiteContent' => $website ?? null,
        'config' => $config
    ])
</head>
<body>

    @include('templates.portfolio2.components.header', [
        'content' => $content,
        'config' => $config
    ])

    @include('templates.portfolio2.components.hero', [
        'content' => $content,
        'config' => $config
    ])
    
    @if(!empty($content['services']))
        @include('templates.portfolio2.components.services', [
            'content' => $content,
            'config' => $config
        ])
    @endif
    
    @if(!empty($content['about_content']))
        @include('templates.portfolio2.components.about', [
            'content' => $content,
            'config' => $config
        ])
    @endif
    
    @if(!empty($content['gallery_photos']))
        @include('templates.portfolio2.components.gallery', [
            'content' => $content,
            'config' => $config
        ])
    @endif
    
    @if(!empty($content['testimonials']))
        @include('templates.portfolio2.components.testimonials', [
            'content' => $content,
            'config' => $config
        ])
    @endif
    
    @include('templates.portfolio2.components.contact', [
        'content' => $content,
        'config' => $config
    ])

    @include('templates.portfolio2.components.footer', [
        'content' => $content,
        'config' => $config
    ])

    @include('templates.portfolio2.components.whatsapp-float', [
        'content' => $content,
        'config' => $config
    ])

</body>
</html>