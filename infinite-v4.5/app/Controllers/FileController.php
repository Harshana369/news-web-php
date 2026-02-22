<?php

namespace App\Controllers;

use App\Models\AuthModel;
use App\Models\FileModel;

class FileController extends BaseAdminController
{
    protected $fileModel;
    protected $fileCount;
    protected $filePerPage;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->fileModel = new FileModel();
        $this->fileCount = 60;
        $this->filePerPage = 60;
    }

    /*
    *------------------------------------------------------------------------------------------
    * IMAGES
    *------------------------------------------------------------------------------------------
    */

    /**
     * Upload Image File
     */
    public function uploadImageFile()
    {
        $this->fileModel->uploadImage();
        return $this->response->setJSON(['csrfToken' => csrf_hash()]);
    }

    /**
     * Get Images
     */
    public function getImages()
    {
        $images = $this->fileModel->getImages($this->fileCount);
        $data = $this->printImages($images);
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($data);
    }

    /**
     * Select Image File
     */
    public function selectImageFile()
    {
        $fileId = inputPost('file_id');
        $file = $this->fileModel->getImage($fileId);
        if (!empty($file)) {
            echo base_url($file->image_mid);
        }
    }

    /**
     * Laod More Images
     */
    public function loadMoreImages()
    {
        $lastId = inputPost('last_id');
        $images = $this->fileModel->getMoreImages($lastId, $this->filePerPage);
        $data = $this->printImages($images);
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($data);
    }

    /**
     * Search Images
     */
    public function searchImageFile()
    {
        $search = inputPost('search');
        $images = $this->fileModel->searchImages($search);
        $data = $this->printImages($images);
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($data);
    }

    /**
     * Print Images
     */
    public function printImages($images)
    {
        $data = [
            'result' => 0,
            'content' => ''
        ];
        if (!empty($images)) {
            foreach ($images as $image) {
                $imgBaseURL = getBaseURLByStorage($image->storage);
                $data['content'] .= '<div class="col-file-manager" id="img_col_id_' . $image->id . '">';
                $data['content'] .= '<div class="file-box" data-file-id="' . $image->id . '" data-file-path="' . $image->image_mid . '" data-file-path-editor="' . $image->image_big . '" data-file-base-url="' . $imgBaseURL . '" data-file-storage="' . $image->storage . '">';
                $data['content'] .= '<div class="image-container">';
                $data['content'] .= '<img src="' . $imgBaseURL . $image->image_mid . '" alt="" class="img-responsive">';
                $data['content'] .= '</div>';
                if (!empty($image->file_name)):
                    $data['content'] .= '<span class="file-name">' . limitCharacter($image->file_name . "." . $image->image_mime, 25, "..") . '</span>';
                endif;
                $data['content'] .= '</div> </div>';
            }
        }
        $data['result'] = 1;
        return $data;
    }

    /**
     * Delete File
     */
    public function deleteImageFile()
    {
        $fileId = inputPost('file_id');
        $this->fileModel->deleteImage($fileId);
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash());
    }

    /*
    *------------------------------------------------------------------------------------------
    * FILES
    *------------------------------------------------------------------------------------------
    */

    /**
     * Upload File
     */
    public function uploadFile()
    {
        $this->fileModel->UploadFile();
        return $this->response->setJSON(['csrfToken' => csrf_hash()]);
    }

    /**
     * Get Files
     */
    public function getFiles()
    {
        $files = $this->fileModel->getFiles($this->fileCount);
        $data = $this->printFiles($files);
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($data);
    }

    /**
     * Laod More Files
     */
    public function loadMoreFiles()
    {
        $lastId = inputPost('last_id');
        $files = $this->fileModel->getMoreFiles($lastId, $this->filePerPage);
        $data = $this->printFiles($files);
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($data);
    }

    /**
     * Search Files
     */
    public function searchFile()
    {
        $search = inputPost('search', true);
        $files = $this->fileModel->searchFiles($search);
        $data = $this->printFiles($files);
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($data);
    }

    /**
     * Print Files
     */
    public function printFiles($files)
    {
        $data = [
            'result' => 0,
            'content' => ''
        ];
        if (!empty($files)) {
            foreach ($files as $file) {
                $data['content'] .= '<div class="col-file-manager" id="file_col_id_' . $file->id . '">';
                $data['content'] .= '<div class="file-box" data-file-id="' . $file->id . '" data-file-name="' . $file->file_name . '">';
                $data['content'] .= '<div class="image-container icon-container">';
                $data['content'] .= '<div class="file-icon file-icon-lg" data-type="' . @pathinfo($file->file_name, PATHINFO_EXTENSION) . '"></div>';
                $data['content'] .= '</div>';
                $data['content'] .= '<span class="file-name">' . limitCharacter($file->file_name, 25, "..") . '</span>';
                $data['content'] .= '</div> </div>';
            }
        }
        $data['result'] = 1;
        return $data;
    }

    /**
     * Delete File
     */
    public function deleteFile()
    {
        $fileId = inputPost('file_id');
        $this->fileModel->deleteFile($fileId);
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash());
    }

    /**
     * Download File
     */
    public function downloadFile()
    {
        $path = inputPost('path');
        if (!empty($path)) {
            $path = str_replace('../', '', $path);
            if (file_exists($path)) {
                return $this->response->download($path, null);
            }
        }
    }
}
