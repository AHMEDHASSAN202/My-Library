<?php
/**
 * Created by PhpStorm.
 * User: AHMED HASSAN
 */


/**
 * Create symlink Files
 *
 * @param $rootName
 * @param $projectName
 */
if (!function_exists('_symlink')) {
    function _symlink($rootName, $projectName)
    {
        symlink('/home/'. $rootName .'/'. $projectName .'/storage/app/public','/home/'. $rootName .'/public_html/storage');
    }
}

/**
 * Get GeoLocation
 *
 * @param string $accessKey
 * @return mixed|null
 */
if (!function_exists('_geolocation')) {
    function _geolocation($accessKey = 'ea6fde4fdb42e4e1faf6b02e67685a28')
    {
        $ip = _IP();
        if (!$ip) return null;
        $curl = curl_init('http://api.ipstack.com/'. $ip .'?access_key=' . $accessKey);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curl);
        curl_close($curl);

        return json_decode($data);
    }
}

/**
 * Cut Part OF Text
 *
 * @param $number
 * @param $text
 * @return array|string
 */
if (!function_exists('_cutText')) {
    function _cutText($number, $text)
    {
        if (str_word_count($text) > $number) {
            $text = explode(' ', $text);
            $text = array_slice($text, 0, ($number-1));
            $text = implode(' ', $text) . ' ...';
        }
        return $text;
    }
}

define('OPENSSL_INFO' , [
    'cipher_method' => 'AES-128-CBC',
    'key'           => openssl_random_pseudo_bytes(62),
    'iv'            => openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-CBC'))
]);
/**
 * Encrypt Data by openssl library
 *
 * @param $data
 * @return string
 * @throws Exception
 */
if (!function_exists('_encrypt')) {
    function _encrypt($data) {
        return base64_encode(openssl_encrypt($data, OPENSSL_INFO['cipher_method'], OPENSSL_INFO['key'], $options=0, OPENSSL_INFO['iv']));
    }
}

/**
 * Decrypt Data by openssl library
 *
 * @param $data
 * @return string
 * @throws Exception
 */
if (!function_exists('_decrypt')) {
    function _decrypt($data) {
        return openssl_decrypt(base64_decode($data), OPENSSL_INFO['cipher_method'], OPENSSL_INFO['key'], $options=0, OPENSSL_INFO['iv']);
    }
}

/**
 * Clean Input
 *
 * @return valid value
 */
if (! function_exists('_cleanInput')) {
    function _cleanInput($value)
    {
        if (is_array($value)) {
            $cleanArray = [];
            foreach ($value AS $key=>$val) {
                $cleanArray[$key] = _cleanInput($val);
            }
            return $cleanArray;
        }else {
            return strip_tags(escape_tags_html($value));
        }
    }
}

/**
 * Escape Html tags
 *
 * @param $value
 */
if (! function_exists('escape_tags_html')) {
    function escape_tags_html($value)
    {
        return htmlspecialchars($value , ENT_QUOTES , 'UTF-8');
    }
}

/**
 * The diff_date function returns the difference between two Date.
 *
 * @param $date
 * @return string
 */
if (!function_exists('_diffDate')) {
    function _diffDate($date)
    {
        $date1 = date_create(date('Y-m-d-h-i', time()));
        $date2 = date_create(date('Y-m-d-h-i', $date));
        $diff = date_diff($date1, $date2);
        if ($diff->y != 0) {
            $timeAgo = ($diff->y == 1) ? $diff->y . ' year' : $diff->y . ' years';
        }elseif ($diff->m != 0) {
            $timeAgo = ($diff->m == 1) ? $diff->m . ' month' : $diff->m . ' months';
        }elseif ($diff->d != 0) {
            $timeAgo = ($diff->d == 1) ? $diff->d . ' day' : $diff->d . ' days';
        }elseif ($diff->h) {
            $timeAgo = ($diff->h == 1) ? $diff->h . ' hour' : $diff->h . ' hours';
        }else {
            $timeAgo = 'a few minuets';
        }
        return $timeAgo;
    }
}

/**
 * Find Real IP address
 *
 * @return mixed
 */
if (!function_exists('_IP')) {
    function _IP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}

/**
 * Convert Image to base64
 *
 * @param $image
 * @return null|string
 */
if (!function_exists('_image2Uri')) {
    function _image2Uri($image)
    {
        $extension = @getimagesize($image)['mime'];
        if (!$extension) return null;
        $image = file_get_contents($image);
        if (!$image) return null;
        return 'data:'.$extension.';base64,'. base64_encode($image);
    }
}

/**
 * Check if extension is image
 *
 * @param $extension
 * @return bool
 */
if (!function_exists('_isImage')) {
    function _isImage($extension)
    {
        return in_array(strtolower($extension), ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg']);
    }
}

/**
 * Right Path
 *
 * @param $path
 * @return string
 */
if (!function_exists('_imagePath')) {
    function _imagePath($path)
    {
        return trim(str_replace(['//', '\\', '/'], '/', $path));
    }
}

/**
 * Prepare Simple Arabic Search
 *
 * @param $search
 * @return array
 */
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

/**
 * Download File
 *
 * @param $file
 * @param null $new_name
 * @return null
 */
if (!function_exists('_downloadFile')) {
    function _downloadFile($file, $new_name = null)
    {
        if (!file_exists($file) || !is_readable($file)) return null;
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $file_name = pathinfo($file, PATHINFO_FILENAME);
        $name = $new_name ? $new_name.'.'.$extension : $file_name.'.'.$extension;
        header('Content-type: application/octet-stream');
        header('Content-Length: ' . filesize($file));
        header('Content-disposition: attachment; filename=' . $name);
        header('Content-transfer-encoding: binary');
        readfile($file);
    }
}

/**
 * Get Data From CSV as php array
 *
 * @param $file
 * @return Generator|null
 */
if (!function_exists('_csvArray')) {
    function _csvArray($file)
    {
        if (!file_exists($file) || !is_readable($file)) return null;
        $csv = fopen($file, 'r');
        $titles = fgetcsv($csv);
        while (($row = fgetcsv($csv)) !== false) {
            yield array_combine($titles, $row);
        }
        fclose($csv);
    }
}

/**
 * Convert RGB to hex
 *
 * @param $rgb
 * @return string
 */
if (!function_exists('_rgbToHex')) {
    function _rgbToHex($rgb)
    {
        sscanf($rgb, 'rgb(%d,%d,%d)', $r, $g, $b);
        $hex = sprintf('#%02x%02x%02x', $r, $g, $b);
        return $hex;
    }
}

/**
 * Convert hex To RGB
 *
 * @param $hex
 * @return string
 */
if (!function_exists('_hexToRgb')) {
    function _hexToRgb($hex)
    {
        sscanf($hex, '#%2x%2x%2x', $r, $g, $b);
        $rgb = sprintf('rgb(%d,%d,%d)', $r, $g, $b);
        return $rgb;
    }
}

/**
 * Get Attributes From DOM
 *
 * @param $url
 * @return array
 */
if (!function_exists('_getLinksFromOnlinePage')) {
    function _getLinksFromOnlinePage($url, $tag, $attr) {
        libxml_use_internal_errors(true);

        $doc = new DOMDocument();
        $doc->loadHTMLFile($url);
        $elements = [];

        foreach($doc->getElementsByTagName($tag) as $link) {
            $elements[] = $link->getAttribute($attr);
        }

        return $elements;
    }
//    _getLinksFromOnlinePage('https://www.php.net/manual/en/class.domdocument.php', 'a', 'href')
}

/**
 * Add Logo PNG To Group From Images Or One Image
 *
 * @param $path
 * @param $logo
 * @param $output_dir
 * @param int $logo_width
 * @param int $logo_height
 * @param int $margin_right
 * @param int $margin_bottom
 * @param bool $resize
 * @return bool|null
 */
if (!function_exists('_addLogoToImage')) {
    function _addLogoToImage($path, $logo, $output_dir, $logo_width = 100, $logo_height = 100, $margin_right = 0, $margin_bottom = 0, $new_if_exists = false, $resize = false)
    {
        //check if path not exists || not readable
        if (!file_exists($path) || !is_readable($path)) return null;

        //images on dir or one image path
        $images = is_dir($path) ? new FilesystemIterator($path) : [new SplFileInfo($path)];

        //Handle Logo
        //get logo extension
        $logoExtension = pathinfo($logo, PATHINFO_EXTENSION);
        if ($logoExtension != 'png') return null;
        //logo resource
        $logoResource = imagecreatefrompng($logo);
        //resize logo image
        $logoAfterResize = $resize ? imagecropauto($logoResource) : imagescale($logoResource, $logo_width, $logo_height);

        //Handle Images
        //loop on all images
        foreach ($images as $img) {
            $extension = $img->getExtension() != 'jpg' ?: 'jpeg'; //get image extension
            $new_image_path = $output_dir . DIRECTORY_SEPARATOR . $img->getFilename();
            //check if this image is exist
            if ($new_if_exists === false) {
                if (file_exists($new_image_path)) {
                    continue;
                }
            }
            $createResource = "imagecreatefrom$extension"; //create resource function name
            $outputImage = "image$extension"; //create output image function name
            if (!function_exists($createResource)) continue;
            if (!function_exists($outputImage)) continue;
            $resource = $createResource($img); //create image resource
            //handle logo position
            if ($margin_right > 0) {
                $dst_x = imagesx($resource) - imagesx($logoAfterResize) - $margin_right;
            }else {
                $dst_x = abs($margin_right);
            }
            if ($margin_bottom > 0) {
                $dst_y = imagesy($resource) - imagesy($logoAfterResize) - $margin_bottom;
            }else {
                $dst_y = abs($margin_bottom);
            }
            //copy from image to new image with logo
            $imageWithLogo = imagecopy(
                $resource,
                $logoAfterResize,
                $dst_x,
                $dst_y,
                0,
                0,
                imagesx($logoAfterResize),
                imagesy($logoAfterResize)
                );
            //image output
            if (!is_dir($output_dir)) mkdir($output_dir);
            $outputImage($resource, $new_image_path);
            //destroy resources
            imagedestroy($resource);
        }
        imagedestroy($logoResource);

        return true;
    }
}

/**
 * Generate Thumbnails
 *
 * @param $from
 * @param $to
 * @param null $width_thumbnail
 * @param null $height_thumbnail
 * @param null $ratio
 * @return bool|null
 */
if (!function_exists('_makeThumbnails')) {
    function _makeThumbnails($from, $to, $width_thumbnail = null, $height_thumbnail = null, $ratio = null, $new_if_exists = false)
    {
        //check if path not exists || not readable
        if (!file_exists($from) || !is_readable($from)) return null;

        //images on dir or one image path
        $images = is_dir($from) ? new FilesystemIterator($from) : [new SplFileInfo($from)];

        //check dist directory create it if not exists
        if (!is_dir($to)) mkdir($to);

        //handle images
        foreach ($images as $image) {
            $extension = $image->getExtension() != 'jpg' ?: 'jpeg'; //get image extension
            $new_image_path = $to . DIRECTORY_SEPARATOR . $image->getFilename();
            //check if this image is exist
            if ($new_if_exists === false) {
                if (file_exists($new_image_path)) {
                    continue;
                }
            }
            $createResource = "imagecreatefrom$extension"; //create resource function name
            $outputImage = "image$extension"; //create output image function name
            if (!function_exists($createResource)) continue;
            if (!function_exists($outputImage)) continue;
            $resource = $createResource($image); //create image resource

            // get original image width and height
            $width = imagesx($resource);
            $height = imagesy($resource);

            if ($ratio != null && is_numeric($ratio)) {
                if ($ratio > 1) $ratio = $ratio / 100;
                $height_thumbnail = $height * $ratio;
                $width_thumbnail = $width * $ratio;
            }

            //get image type
            $type = exif_imagetype($image->getRealPath());

            // create duplicate image based on calculated target size
            $thumbnail = imagecreatetruecolor($width_thumbnail, $height_thumbnail);

            // set transparency options for GIFs and PNGs
            if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_PNG) {

                // make image transparent
                imagecolortransparent(
                    $thumbnail,
                    imagecolorallocate($thumbnail, 0, 0, 0)
                );

                // additional settings for PNGs
                if ($type == IMAGETYPE_PNG) {
                    imagealphablending($thumbnail, false);
                    imagesavealpha($thumbnail, true);
                }
            }

            // copy entire source image to duplicate image and resize
            imagecopyresampled(
                $thumbnail,
                $resource,
                0, 0, 0, 0,
                $width_thumbnail, $height_thumbnail,
                $width, $height
            );

            //save the duplicate version of the image to disk
            $outputImage($thumbnail, $new_image_path);

            //destroy resources
            imagedestroy($resource);
            imagedestroy($thumbnail);
        }
        return true;
    }
}

/**
 * Access key from Array
 *
 * @return null || value
 */
if (!function_exists('_arrayGet')) {
    function _arrayGet($array, $key, $default = null)
    {
        if (is_null($array)) return $default;
        if (!is_array($array)) return $default;
        if (!isset($array[$key])) return $default;
        return $array[$key];
    }
}

/**
 * Access key from Object
 *
 * @return null || value
 */
if (!function_exists('_objectGet')) {
    function _objectGet($object, $key, $default)
    {
        if (is_null($object)) return $default;
        if (!is_object($object)) return $default;
        if (!property_exists($object, $key)) return $default;
        return $object->{$key};
    }
}


?>