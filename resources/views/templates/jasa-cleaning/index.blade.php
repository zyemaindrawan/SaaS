@include('templates.jasa-cleaning.components.seo-head', [
    'content' => $content,
    'websiteContent' => $website ?? null,
    'config' => $config
])

@include('templates.jasa-cleaning.components.header', [
    'content' => $content,
    'config' => $config
])

@include('templates.jasa-cleaning.components.hero', [
    'content' => $content,
    'config' => $config
])

@include('templates.jasa-cleaning.components.about', [
    'content' => $content,
    'config' => $config
])

@if(!empty($content['products']))
    @include('templates.jasa-cleaning.components.products', [
        'content' => $content,
        'config' => $config
    ])
@endif

@if(!empty($content['gallery_photos']))
    @include('templates.jasa-cleaning.components.gallery', [
        'content' => $content,
        'config' => $config
    ])
@endif

@if(!empty($content['testimonials']))
    @include('templates.jasa-cleaning.components.testimonials', [
        'content' => $content,
        'config' => $config
    ])
@endif

@include('templates.jasa-cleaning.components.contact', [
    'content' => $content,
    'config' => $config
])

@include('templates.jasa-cleaning.components.footer', [
    'content' => $content,
    'config' => $config
])

@include('templates.jasa-cleaning.components.whatsapp-float', [
    'content' => $content,
    'config' => $config
])
