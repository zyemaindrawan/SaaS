<?php
// File: app/Helpers/helpers.php

if (!function_exists('resolveAssetPath')) {
    function resolveAssetPath($path) {
        if (empty($path)) {
            return null;
        }
        
        // Handle array input - this can happen with file upload fields
        if (is_array($path)) {
            // If it's an array, try to get the 'url' property first, then first element
            if (isset($path['url'])) {
                $path = $path['url'];
            } elseif (empty($path) || !isset($path[0])) {
                return null;
            } else {
                $path = $path[0];
            }
        }
        
        // Handle object input
        if (is_object($path)) {
            if (isset($path->url)) {
                $path = $path->url;
            } else {
                return null;
            }
        }
        
        // Ensure we have a string now
        if (!is_string($path)) {
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
