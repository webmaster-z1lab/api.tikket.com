<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 16/12/2018
 * Time: 13:10
 */

namespace App\Services;


use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class MakeImageService
{
    /**
     * @var string
     */
    private $extension;
    /**
     * @var int
     */
    private $quality;
    /**
     * @var string
     */
    private $path;
    /**
     * @var string
     */
    private $source;
    /**
     * @var string
     */
    private $file;

    /**
     * MakeImageService constructor.
     *
     * @param string $source
     * @param string $path
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct(string $source, string $path)
    {
        $this->extension = config('image.extension');
        $this->quality = config('image.quality');

        $this->path = config('image.path') . "/$path/";
        $this->source = $source;
        $this->file = \Storage::get($source);
    }

    /**
     * @return string
     */
    public function cover(): string
    {
        return $this->create('cover');
    }

    /**
     * @return string
     */
    public function square(): string
    {
        return $this->create('square');
    }

    /**
     * @return string
     */
    public function thumbnail(): string
    {
        return $this->create('thumbnail');
    }

    /**
     * @return string
     */
    public function basic(): string
    {
        return $this->create('basic', 'webp', FALSE);
    }

    /**
     * @return string
     */
    public function landscape(): string
    {
        return $this->create('landscape');
    }

    /**
     * @return string
     */
    public function facebookCover(): string
    {
        return $this->create('facebook_cover', 'jpg');
    }

    /**
     * Remove source Image
     */
    public function destroyOriginal(): void
    {
        \Storage::delete($this->source);
    }

    /**
     * @param string      $type
     * @param string|NULL $extension
     * @param bool        $crop
     *
     * @return string
     */
    private function create(string $type, string $extension = NULL, bool $crop = TRUE): string
    {
        $extension = $extension ?? $this->extension;
        $fileName = (string)Str::uuid() . "__$type.$extension";
        $path = $this->path . $fileName;

        $image = Image::make($this->file);

        if ($crop) {
            $this->resizeAndCrop($image, $type);
        } else {
            $this->resize($image, $type);
        }

        \Storage::put($path, $image->encode($extension, $this->quality)->__toString());

        $image->destroy();

        return $path;
    }

    /**
     * @param $image
     * @param $type
     */
    private function resizeAndCrop(&$image, $type)
    {
        $size = config("image.sizes.$type");

        $image->resize(NULL, $size[0], function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->crop($size[0], $size[1]);
    }

    /**
     * @param $image
     * @param $type
     */
    private function resize(&$image, $type)
    {
        $size = config("image.sizes.$type");

        if ($image->filesize() > $size[0]) {
            $image->resize($size[0], NULL, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
    }
}
