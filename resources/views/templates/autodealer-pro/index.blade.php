<!DOCTYPE html>
<html lang="id">
<head>
    @include('templates.autodealer-pro.components.seo-head', [
        'content' => $content,
        'websiteContent' => $website ?? null,
        'config' => $config
    ])
</head>
<body>
    @include('templates.autodealer-pro.components.header', [
        'content' => $content,
        'config' => $config
    ])

    @include('templates.autodealer-pro.components.hero', [
        'content' => $content,
        'config' => $config
    ])

    @include('templates.autodealer-pro.components.promo', [
        'content' => $content,
        'config' => $config
    ])

    @if(!empty($content['cars']))
        @include('templates.autodealer-pro.components.cars', [
            'content' => $content,
            'config' => $config
        ])
    @endif

    @if(!empty($content['testimonials']))
        @include('templates.autodealer-pro.components.testimonials', [
            'content' => $content,
            'config' => $config
        ])
    @endif

    @if(!empty($content['gallery_photos']))
    @include('templates.autodealer-pro.components.gallery', [
        'content' => $content,
        'config' => $config
    ])
    @endif

    @include('templates.autodealer-pro.components.contact', [
        'content' => $content,
        'config' => $config
    ])

    @include('templates.autodealer-pro.components.footer', [
        'content' => $content,
        'config' => $config
    ])

    @include('templates.autodealer-pro.components.whatsapp-float', [
        'content' => $content,
        'config' => $config
    ])

</body>
</html>