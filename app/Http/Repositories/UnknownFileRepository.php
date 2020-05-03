<?php


namespace App\Http\Repositories;

use Illuminate\Http\Request;


/**
 * Class TagRepository
 * @package App\Http\Repository
 */
class UnknownFileRepository extends FileRepository
{
    protected $tag;

    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    public function upload(Request $request, $delete = false, $fileName = 'file')
    {
        if ($delete) {
            $this->deleteAllByType();
        }
        $file = $request->file($fileName);
        return $this->uploadFile($file, $file->getClientOriginalName());
    }

    public function tag()
    {
        return $this->tag;
    }

    public function type()
    {
        return $this->tag;
    }
}