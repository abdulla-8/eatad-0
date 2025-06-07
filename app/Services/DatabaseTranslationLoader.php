<?php

// app/Services/DatabaseTranslationLoader.php
namespace App\Services;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use App\Models\Translation;
use App\Models\Language;
use Illuminate\Support\Facades\Cache;

class DatabaseTranslationLoader extends FileLoader
{
    public function __construct(Filesystem $files, $path)
    {
        parent::__construct($files, $path);
    }

    public function load($locale, $group, $namespace = null)
    {
        $fileTranslations = parent::load($locale, $group, $namespace);
        
        if ($namespace !== null) {
            return $fileTranslations;
        }

        $databaseTranslations = $this->loadFromDatabase($locale, $group);
        
        return array_merge($fileTranslations, $databaseTranslations);
    }

    protected function loadFromDatabase($locale, $group)
    {
        try {
            $language = Language::where('code', $locale)->first();
            
            if (!$language) {
                return [];
            }

            $translations = Translation::where('language_id', $language->id)
                ->where('group', $group)
                ->pluck('value', 'key')
                ->toArray();

            return $translations;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function addNamespace($namespace, $hint)
    {
        $this->hints[$namespace] = $hint;
    }
}
