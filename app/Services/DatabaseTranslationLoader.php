<?php

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
        // احصل على الترجمات من الملفات أولاً (كـ fallback)
        $fileTranslations = parent::load($locale, $group, $namespace);
        
        // إذا كان هناك namespace، ارجع ترجمات الملفات فقط
        if ($namespace !== null) {
            return $fileTranslations;
        }

        // احصل على ترجمات قاعدة البيانات
        $databaseTranslations = $this->loadFromDatabase($locale, $group);
        
        // ادمج ترجمات قاعدة البيانات مع ترجمات الملفات (قاعدة البيانات لها الأولوية)
        return array_merge($fileTranslations, $databaseTranslations);
    }

    protected function loadFromDatabase($locale, $group)
    {
return Cache::remember("translations.{$locale}.{$group}", 0, function () use ($locale, $group) {
            try {
                // احصل على اللغة بالكود
                $language = Language::where('code', $locale)->first();
                
                if (!$language) {
                    return [];
                }

                // احصل على الترجمات لهذه اللغة والمجموعة
                $translations = Translation::where('language_id', $language->id)
                    ->where('group', $group)
                    ->pluck('value', 'key')
                    ->toArray();

                return $translations;
            } catch (\Exception $e) {
                // ارجع مصفوفة فارغة إذا لم تكن قاعدة البيانات متاحة
                return [];
            }
        });
    }

    public function addNamespace($namespace, $hint)
    {
        $this->hints[$namespace] = $hint;
    }
}