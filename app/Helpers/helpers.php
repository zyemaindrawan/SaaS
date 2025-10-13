<?php
// File: app/Helpers/helpers.php

if (!function_exists('resolveAssetPath')) {
    function resolveAssetPath($path) {
        if (empty($path)) {
            return null;
        }
        
        // Jika sudah full URL, return as is
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        
        // Jika dimulai dengan 'website-assets/', tambahkan '/storage/' di depan
        if (str_starts_with($path, 'website-assets/')) {
            return url('storage/' . $path);
        }
        
        // Jika dimulai dengan 'storage/', return as is dengan url()
        if (str_starts_with($path, 'storage/')) {
            return url($path);
        }
        
        // Default: anggap relatif dari storage
        return url('storage/' . $path);
    }
}
