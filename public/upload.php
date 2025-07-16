<?php

$tempName = $_FILES['upload']['tmp_name'];
$fileName = uniqid() . $_FILES['upload']['name'];
$uploadPath = 'uploads/ck_analysis/' . $fileName;
$path = 'uploads/ck_analysis/';
if (!file_exists($path)) {
    mkdir($path, 0777, true);
}
$imageUrl = url() . '/uploads/ck_analysis/' . $fileName;

$success = move_uploaded_file($_FILES["upload"]["tmp_name"], $uploadPath);

$success = true;

$html = '<script>window.parent.CKEDITOR.tools.callFunction(%s, "%s", "%s");</script>';
// $message = $success ? 'Uploaded successfully.' : 'Upload failed.';
$message = '';
echo sprintf($html, $_GET['CKEditorFuncNum'], $imageUrl, $message);

function url()
{
    if (isset($_SERVER['HTTPS'])) {
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    } else {
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'];
}
