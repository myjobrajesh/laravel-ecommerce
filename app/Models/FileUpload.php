<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Image;
use Storage;
class FileUpload extends Model {

    /**
     * @var string
     */
    protected $table = 'entity_file';

    
    
    
    private static function generateFolderPath($type    =   null) {
        
		$folderPath         = date("Y")."/".intval(date("m"))."/";
		return ($type) ? $type.'/'.$folderPath : $folderPath;
	}
	
    /**
     * Make a Filename, based on the uploaded file.
     *
     * @return string
     */
    private static function generateFilename($fileObj) {

        // Get the file name original name
        // and encrypt it with sha1
        $name = sha1 (
            time() . $fileObj->getClientOriginalName()
        );

        // Get the extension of the photo.
        $extension = $fileObj->getClientOriginalExtension();

        // Then set name = merge those together.
        return "{$name}.{$extension}";
    
        /*$image = md5_file($_FILES['image']['tmp_name']);
        // you can add a random number to the file name just to make sure your images will be "unique"
        $image = md5(mt_rand().$image);
        $folder = $image[0]."/".$image[1]."/".$image[2]."/";
        */
    }

    /**
     * get filename with full path
     * save this path to db and folder
     * @return string
     */
    public static function generateFilepath($fileObj, $type=null) {

        $folderPath =   self::generateFolderPath($type);
        $ret        =   $folderPath. self::generateFilename($fileObj);
        return $ret;
    }
    
    /**
     * display file path with filename 
     * for viewing
     * @return string
     */
    public static function getFilepath($fileObj) {

        //make condition here....
        $ret        =   $fileObj->filepath;
        return $ret;
    }
    
    //make filevalidation
    public static function fileValidation($type) {
		if(\Request::hasFile("file")) {
                    $filesArr = \Request::file("file");
					$maxSize = ($type=="mailbox") ? config('app.maxUploadMailboxSize') : config('app.maxUploadSize');
                    $maxSize = ($type=="chat") ? config('app.maxUploadChatSize') : $maxSize;
                    //print_r($filesArr);die;
					foreach($filesArr as $file) {
						//$file =  $filesArr[0];
						$originalExt = strtolower($file->getClientOriginalExtension());
						$ext = (!$originalExt) ? strtolower($file->guessExtension()): $originalExt;
						$attachExt = (!$originalExt) ? ".".$ext : "";
						$validExt = config('app.validFileTypes.'.$type);
						if(!in_array($ext, $validExt)) {
							$response = array("error" => "Allowed filetypes : ".implode(',', $validExt));
							return \Response::json($response);
						}
						//get size validation
						
						if($maxSize < $file->getSize()) {//bytes
							$response = array("error" => "Cannot upload large file");
							return \Response::json($response);
						}
					}
				}
	}
    
    /* remove files from folder
     * @param string $fileobj with filename
     * @param string $type
     * return
     */
	public static function removeFilesFromFolder($fileObj, $type) {
        if(isset($fileObj->filepath) && is_object($fileObj)) {
            $mainFilepath   =   $fileObj->filepath;
            $fileDimensions 	= config('app.thumbSize');
            $filename = substr(strrchr($mainFilepath, '/'), 1);
            try {
                if(isset($fileDimensions[$type])) {
                    foreach($fileDimensions[$type] as $pref=>$dim) {
                        $newFilename = $pref.'_'.$filename;
                        $thumbPath = str_replace($filename, $newFilename, $mainFilepath);
                        \Storage::disk('buzz')->delete($thumbPath);
                    }
                }
                \Storage::disk('buzz')->delete($mainFilepath);
            } catch (\Exception $e) {
                $response = array("error" => "Cannot delete file");
                return \Response::json($response);
            }
        }
	}
    
    /* upload image and create thumbnail and store into db and update db
     */
    public static function uploadFileAndThumbnail($type, $thumbSizeType = null) {
        $filePathToStore = null;
        if(\Request::hasFile("file")) {
			$files = \Request::file("file");
			if(!is_array($files)) {
                $filesArr[] = $files;
            } else {
                $filesArr = $files;
            }
            $attachObj = [];
            $sizeType = ($thumbSizeType) ? $thumbSizeType : $type;
			foreach($filesArr as $file) {
				$filenameToSave     =   self::generateFilename($file);
                $filePathToStore    =   self::generateFilepath($file, $type);
                //\Storage::disk('buzz')->put($filePathToStore,  \File::get($file));//big file
                self::uploadFile($file, $type);
                self::generateThumbs($filePathToStore, $filenameToSave, \File::get($file), $sizeType);
			}
		}
        return $filePathToStore;
    }
    
    /*
     * upload orifinal file
     */
    public static function uploadFile($file, $type) {
			$filenameToSave     =   self::generateFilename($file);
            $filePathToStore    =   self::generateFilepath($file, $type);
            \Storage::disk('buzz')->put($filePathToStore,  \File::get($file));//big file
        return $filePathToStore;
    }
    
    private static function generateThumbs($originalFilepath, $originalFilename, $fileObj, $type) {
        $size = \Config::get('app.thumbSize');
        if(isset($size[$type])) {
            $fileSizes = $size[$type];
            foreach ($fileSizes as $k => $fileSize) {
                $destination = str_replace($originalFilename, $k."_".$originalFilename, $originalFilepath);
                $img = Image::make($fileObj)
                    ->resize($fileSize[0], $fileSize[1]);
                Storage::disk('buzz')->put($destination, $img->stream());
            }
        }
   }

    //save model
    public static function saveFileModel($obj, $type, $filepathToSave){
                $filename = substr(strrchr($filepathToSave, '/'), 1);
                if(!$obj->file) {
						$fileObj = \App::make('\App\Models\Entities\EntityFile');
						$fileObj->filename = $filename;
                        $fileObj->filepath = $filepathToSave;
						$fileObj->entityType = $type;
						$obj->file()->save($fileObj);
					 } else {// blog testd
						$obj->file->filename = $filename;
                        $obj->file->filepath = $filepathToSave;
						$obj->file->save();	
					 }
    }
    
    
}