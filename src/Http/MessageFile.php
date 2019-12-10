<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
//use ImageOptimizer;

class MessageFile extends Model
{
    //
    protected $fillable = ['message_id', 'file'];

    protected static $extensions = ['jpg', 'jpeg', 'png'];

    public static function setMessageId($fileId, $messageId) {
        $file = self::find($fileId);
        if ($file) {
            $file->message_id = $messageId;
            $file->save();
        }
    }

    public static function uploadFile(Request $request) {
        if($request->has('file')) {
            if (in_array($request->file->getClientOriginalExtension(), self::$extensions)) {
                $file = $request->file('file');
                $name = time() . '.png';
                $storePath = 'img/msg/' . date('m.Y', time()) . '/';
                $file->storeAs('public/' . $storePath, $name);
                $photo = new MessagePhoto();
                $photo->file = $storePath . $name;
                $photo->save();
                
                if(file_exists(storage_path("app/public/" . $storePath . $name))){
                    //ImageOptimizer::optimize(storage_path("app/public/".$storePath . $name));
                }

                return $photo;
            }

            return "Error format";
            
        }

        return null;
    }

}