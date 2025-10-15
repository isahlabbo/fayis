<?php
namespace App\Services\Upload;

use Illuminate\Support\Facades\Storage;

trait FileUpload
{
    public function storeFile($model, $field, $file, $location)
    {
        $filename = time().$file->getClientOriginalName();

        $fullPath = $location.$filename;
        
        $file->storeAs($location, $filename, $this->fileSystem());

        $model->update([$field => $fullPath]);
        
    }

    public function updateFile($model, $field, $newFile, $newLocation)
    {
        Storage::disk($this->fileSystem())->delete($model->$field);

        $this->storeFile($model, $field, $newFile, $newLocation);
    }

    private function fileSystem()
    {
        return app()->environment('production') ? 's3' : 'public';
    }

    public function deleteFile()
    {
        if($this->image)
            Storage::disk($this->fileSystem())->delete($this->image);
    }
}
