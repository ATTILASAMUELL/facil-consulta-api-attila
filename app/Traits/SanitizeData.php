<?php

namespace App\Traits;

trait SanitizeData
{
    public function clean($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'clean'], $data);
        }

        if (is_string($data)) {
            return $this->cleanString($data);
        }

        return $data;
    }

    private function cleanString($string)
    {
        $string = trim($string);
        $string = preg_replace('/(\b(select|insert|update|delete|drop|alter|create|show|truncate|rename)\b)/i', '', $string);
        $string = preg_replace('/https?:\/\/[a-z0-9\-\.]+\.[a-z]{2,4}(\/\S*)?/i', '', $string);
        $string = preg_replace('/[\.\-]/', '', $string);
        $string = preg_replace('/[^a-zA-Z0-9\s]/', '', $string);
        $string = strip_tags($string);

        return $string;
    }

    public static function bootSanitizeData()
    {
        static::saving(function ($model) {
            foreach ($model->getFillable() as $attribute) {
                if (method_exists($model, 'clean')) {
                    $model->{$attribute} = $model->clean($model->{$attribute});
                }
            }
        });

        static::retrieved(function ($model) {
            foreach ($model->getFillable() as $attribute) {
                if (method_exists($model, 'clean')) {
                    $model->{$attribute} = $model->clean($model->{$attribute});
                }
            }
        });
    }
}
