<?php
/**
 * Created by PhpStorm.
 * User: AHMED HASSAN
 */



if (!function_exists('_isImage')) {
    function _isImage($extension) {
        return in_array(strtolower($extension), ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg']);
    }
}



if (!function_exists('_search')) {
    function _search($query, $search_fields = []) {
        $new_query = $query;
        foreach ($search_fields as $key => $value) {
            if (request()->{$key} !== null) {
                $new_query->where($value, 'LIKE', '%' . request()->{$key} . '%');
            }
        }
        return $new_query;
    }
}



if (!function_exists('_symlink')) {
    function _symlink($rootName, $projectName) {
        symlink('/home/'. $rootName .'/'. $projectName .'/storage/app/public','/home/'. $rootName .'/public_html/storage');
    }
}



if (!function_exists('_imagePath')) {
    function _imagePath($path) {
        return trim(str_replace(['//', '\\', '/'], '/', $path));
    }
}


if (!function_exists('_prepareSearchString')) {
    function _prepareSearchString($search)
    {
        $chars = [
            ['أ', 'ا', 'آ'],
            ['ة', 'ه'],
            ['ي', 'ى'],
            ['ئ', 'ء', 'ؤ']
        ];


        $searches = [];

        foreach ($chars as $replaces) {
            foreach ($replaces as $replace) {
                if (strpos($search, $replace) !== false) {
                    array_map(function ($r) use (&$searches, $replace, $search) {
                        $searches[] = str_replace($replace, $r, $search);
                    }, $replaces);
                }
            }
        }

        $searches = array_unique($searches);

        return empty($searches) ? $search : $searches;
    }
}



if (!function_exists('_geolocation')) {
    function _geolocation($accessKey = 'ea6fde4fdb42e4e1faf6b02e67685a28') {
        $ip = request()->ip();
        $curl = curl_init('http://api.ipstack.com/'. $ip .'?access_key=' . $accessKey);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curl);
        curl_close($curl);

        return json_decode($data);
    }
}



if (!function_exists('_setting')) {
    function _setting($key, $table = 'settings') {
        return \Illuminate\Support\Facades\DB::table($table)->where('key', $key)->value('value');
    }
}
