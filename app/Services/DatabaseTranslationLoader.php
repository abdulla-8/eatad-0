<?php

namespace App\Services;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use App\Models\Translation;
use App\Models\Language;
use Illuminate\Support\Facades\DB;

class DatabaseTranslationLoader extends FileLoader
{
    public function __construct(Filesystem $files, $path)
    {
        parent::__construct($files, $path);
    }

    public function load($locale, $group, $namespace = null)
    {
        // Load file translations first
        $fileTranslations = parent::load($locale, $group, $namespace);
        
        // Don't load database translations for namespaced translations
        if ($namespace !== null) {
            return $fileTranslations;
        }

        // Load database translations
        $databaseTranslations = $this->loadFromDatabase($locale, $group);
        
        // Merge - database translations override file translations
        return array_merge($fileTranslations, $databaseTranslations);
    }

    protected function loadFromDatabase($locale, $group)
    {
        try {
            // Check if tables exist
            if (!$this->tablesExist()) {
                return [];
            }

            $language = Language::where('code', $locale)->first();
            
            if (!$language) {
                return [];
            }

            return Translation::where('language_id', $language->id)
                ->where('group', $group)
                ->pluck('value', 'key')
                ->toArray();

        } catch (\Exception $e) {
            return [];
        }
    }

    protected function tablesExist()
    {
        try {
            $schema = DB::getSchemaBuilder();
            return $schema->hasTable('languages') && $schema->hasTable('translations');
        } catch (\Exception $e) {
            return false;
        }
    }

    public function addNamespace($namespace, $hint)
    {
        $this->hints[$namespace] = $hint;
    }
}