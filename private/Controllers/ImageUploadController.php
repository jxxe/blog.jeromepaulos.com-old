<?php

namespace Blog\Controllers;

class ImageUploadController {

    public static function POST_image(): void {
        http_response_code(400); // Invalid file provided
        if(empty($_FILES['image'])) die;

        $file_explode = explode('.', $_FILES['image']['name']);
        $file_name = bin2hex(random_bytes(10)) . '.' . end($file_explode);
        $temp_path = $_FILES['image']['tmp_name'];

        http_response_code(400); // Invalid file provided
        if($_FILES['image']['error'] !== UPLOAD_ERR_OK) die;

        http_response_code(413); // File too large
        if(filesize($temp_path) > 1024*1024*2) die;

        http_response_code(415); // Type not allowed
        if(!str_starts_with(mime_content_type($temp_path), 'image/')) die;

        $image = file_get_contents($temp_path);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "https://api.github.com/repos/{$_ENV['UPLOADS_REPO']}/contents/images/$file_name");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($curl, CURLOPT_USERPWD, "jxxe:{$_ENV['GITHUB_TOKEN']}");
        curl_setopt($curl, CURLOPT_USERAGENT, 'File Upload App');

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
            'message' => "Added $file_name",
            'committer' => [
                'name' => 'Upload from API',
                'email' => 'noreply@jeromepaulos.com'
            ],
            'content' => base64_encode($image)
        ]));

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Accept: application/vnd.github.v3+json',
            'Content-Type: application/x-www-form-urlencoded'
        ]);

        http_response_code(500); // Server error
        $response = curl_exec($curl);
        if(curl_errno($curl)) die;
        curl_close($curl);

        http_response_code(200);

        header('Content-Type: application/json');
        echo json_encode(['data' => ['filePath' => "{$_ENV['UPLOADS_BASE_URL']}/$file_name"]]);

        // Uncomment
        // $response = json_decode($response);
        // echo json_encode(['data' => ['filePath' => $response->content->download_url]]);
    }

}