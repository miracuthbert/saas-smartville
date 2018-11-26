<?php

namespace Smartville\Http\Tenant\Controllers\Property;

use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Http\Tenant\Requests\PropertyImageStoreRequest;

class PropertyImageUploadController extends Controller
{
    /**
     * @var ImageManager
     */
    private $imageManager;

    /**
     * PropertyImageUploadController constructor.
     * @param ImageManager $imageManager
     */
    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PropertyImageStoreRequest $request
     * @param Property $property
     * @return \Illuminate\Http\Response
     */
    public function store(PropertyImageStoreRequest $request, Property $property)
    {
        if (Gate::denies('create property') || Gate::denies('update property')) {
            return response()->json(null, 404);
        }

        $processedImage = $this->imageManager->make($request->file('upload')->getPathName())
            ->fit(1024, 768, function ($c) {
                $c->aspectRatio();
            })->encode('png')
            ->save($path = config('image.path.properties.absolute') . '/' . uniqid(true) . '.png');
        //store original in local storage

        $path = Storage::disk('public')->putFileAs(
            $property->getImageStorageDir(),
            new File($path),
            $image = uniqid(true) . '.png'
        );

        // update property image
        $property->update([
            'image' => $path
        ]);

        // delete processed image
        $processedImage->destroy();

        return response()->json([
            'data' => [
                'image' => $property->image,
                'imageUrl' => $property->imageUrl,
            ]
        ]);
    }
}
