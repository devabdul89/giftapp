<?php
/**
 * Created by PhpStorm.
 * User: officeaccount
 * Date: 07/03/2017
 * Time: 12:05 PM
 */

namespace Traits;


trait ImageHelper
{
    private function saveProfilePicture($image, $path = 'images/profile_pictures/'){
        $filename = uniqid().$image->getClientOriginalName();
        $image->move(public_path($path), $filename);
        return "public/".$path.$filename;
    }
}