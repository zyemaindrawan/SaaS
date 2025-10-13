@include('templates.dealer-honda.components.seo-head', [
    'content' => $content,
    'websiteContent' => $website ?? null,
    'config' => $config
])

@include('templates.dealer-honda.components.header', [
    'content' => $content,
    'config' => $config
])

@include('templates.dealer-honda.components.hero', [
    'content' => $content,
    'config' => $config
])

@include('templates.dealer-honda.components.about', [
    'content' => $content,
    'config' => $config
])

@if(!empty($content['products']))
    @include('templates.dealer-honda.components.products', [
        'content' => $content,
        'config' => $config
    ])
@endif

@if(!empty($content['gallery_photos']))
    @include('templates.dealer-honda.components.gallery', [
        'content' => $content,
        'config' => $config
    ])
@endif

@if(!empty($content['testimonials']))
    @include('templates.dealer-honda.components.testimonials', [
        'content' => $content,
        'config' => $config
    ])
@endif

@include('templates.dealer-honda.components.contact', [
    'content' => $content,
    'config' => $config
])

@include('templates.dealer-honda.components.footer', [
    'content' => $content,
    'config' => $config
])

@include('templates.dealer-honda.components.whatsapp-float', [
    'content' => $content,
    'config' => $config
])
