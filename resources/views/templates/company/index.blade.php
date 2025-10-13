@include('templates.company.components.seo-head', [
    'content' => $content,
    'websiteContent' => $website ?? null,
    'config' => $config
])

@include('templates.company.components.header', [
    'content' => $content,
    'config' => $config
])

@include('templates.company.components.hero', [
    'content' => $content,
    'config' => $config
])

@include('templates.company.components.about', [
    'content' => $content,
    'config' => $config
])

@if(!empty($content['products']))
    @include('templates.company.components.products', [
        'content' => $content,
        'config' => $config
    ])
@endif

@if(!empty($content['gallery_photos']))
    @include('templates.company.components.gallery', [
        'content' => $content,
        'config' => $config
    ])
@endif

@if(!empty($content['testimonials']))
    @include('templates.company.components.testimonials', [
        'content' => $content,
        'config' => $config
    ])
@endif

@include('templates.company.components.contact', [
    'content' => $content,
    'config' => $config
])

@include('templates.company.components.footer', [
    'content' => $content,
    'config' => $config
])

@include('templates.company.components.whatsapp-float', [
    'content' => $content,
    'config' => $config
])
