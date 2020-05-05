<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.MediaJce
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @copyright   Copyright (C) 2020 Bruce Scherzinger. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.filesystem.path');
$images = array();
$media_types = array();

if ($field->value == '') {
    return;
}

// Get picked file name and then get the directory containing it
$primaryImage = $field->value;
$path_parts = pathinfo($primaryImage);
$imagePath = $path_parts['dirname'];
$primaryImageFile = $path_parts['basename'];

//loop through allowed extensions
$types = (string) $fieldParams->get('media_types', '');
if ($types) {
    $types = htmlentities($types, ENT_COMPAT, 'UTF-8', true);
}
$media_types = explode(",",str_replace(" ","",$types));

foreach($media_types as $strExtension)
{
	$images = array_merge($images,JFolder::files(JPATH_ROOT.DIRECTORY_SEPARATOR.$imagePath, $strExtension, false, false));
}

$group = (string) $fieldParams->get('media_group', '');
if ($group) {
    $group = htmlentities($group, ENT_COMPAT, 'UTF-8', true);
}

$group_title = (string) $fieldParams->get('media_group_title', '');
if ($group_title) {
    $group_title = htmlentities($group_title, ENT_COMPAT, 'UTF-8', true);
}

$desc = (string) $fieldParams->get('media_description', '');
if ($desc) {
    $desc = htmlentities($desc, ENT_COMPAT, 'UTF-8', true);
}

$width = (string) $fieldParams->get('media_width', '');
if ($width) {
    $width = 'width="'.htmlentities($width, ENT_COMPAT, 'UTF-8', true).'px"';
}

$height = (string) $fieldParams->get('media_height', '');
if ($height) {
    $height = 'height="'.htmlentities($height, ENT_COMPAT, 'UTF-8', true).'px"';
}

$style = (string) $fieldParams->get('media_style', '');
if ($style) {
    $style = 'style="'.htmlentities($style, ENT_COMPAT, 'UTF-8', true).'"';
}

$element = '<a class="jcepopup" title="%s" href="%s" data-mediabox="1" data-mediabox-group="%s" data-mediabox-title="%s"><img %s src="%s" alt="Photo" %s %s /></a>';

// Construct main image and pop-up link.
$buffer = sprintf($element,$group_title,$primaryImage,$group,$desc,$style,$primaryImage,$width,$height);

foreach ($images as $image) {
    $path_parts = pathinfo($image);
    $filename = $path_parts['basename'];
    if ($filename != $primaryImageFile) {
        $buffer .= sprintf('<a class="jcepopup" href="%s" target="_blank" data-mediabox="1" data-mediabox-group="%s" data-mediabox-title="%s" style="display: none; visibility: hidden;"></a>',$imagePath.DIRECTORY_SEPARATOR.$filename,$group,$desc);
    }
}

echo $buffer;
?>