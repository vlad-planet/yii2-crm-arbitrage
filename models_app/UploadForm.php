<?php

namespace common\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile[]
     */
	public $id;
    public $imageFiles;	

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, zip, file', 'maxFiles' => 0 ],
        ];
    }

    public function upload($id=null)
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
				$file->saveAs("@backend/web/upload/".$id.'/'. $file->baseName . '.' . $file->extension);
			}
            return $this->imageFiles;
        } else {
            return false;
        }
    }

    public function remove()
    {
        if ($this->validate()) { 
            foreach ($this->imageFiles as $file) {
                unlink("@upload/". $file->baseName . '.' . $file->extension);
            }
            return true;
        } else {
            return false;
        }
    }
}