<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\UploadedFile;

class CategoryController extends Controller
{
    protected string $upload;
    protected $sizes = [50,150,300,600,1200];
    protected function deteteImages(int $id){
        $item = Category::find($id);
        foreach ($this->sizes as $size) {
            $filePath = public_path($this->upload.$size."_".$item->image);
            if(file_exists( $filePath)){
                unlink($filePath);
            }
        }
    }
    protected function saveImages( UploadedFile $file){
        $fileName = uniqid() . '.webp';
        $manager = new ImageManager(new Driver());
        foreach ($this->sizes as $size) {
            $imageSave = $manager->read($file);
            $imageSave->scale(width: $size);
            $path = public_path($this->upload.$size."_".$fileName);
            $imageSave->toWebp()->save($path);
        }
        return $fileName;
    }
    public function __construct()
    {
        $this-> upload = env('UPLOAD_DIR');
    }
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $item = Category::find($id);
        if($item && $request->input('name') != ''){
            if ( $request->hasFile('image') ) {
                $file = $request->file('image');
                $this->deteteImages($id);
                $item->image = $this->saveImages($file);
            }
            $item->name = $request->input('name');
            $item->save();
            return response()->json([
                'message' => 'Category updated successfully!',
                'category' => $item
            ], 200);
        }
        else
            return response()->json("Bad request", 400);
    }
    public function store(Request $request) {
        if (!file_exists(public_path($this->upload))) {
            mkdir(public_path($this->upload), 0777);
        }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $this->saveImages($file);
            $item = Category::create(['name' => $request->input('name') , 'image' => $fileName]);
            return response()->json($item, 201);
        }
        else
            return response()->json("Image file not found", 404);
    }
    public function index() {
        $data = Category::all();
        return response()->json($data)
            ->header('Content-Type', 'application/json; charset=utf-8');
    }
    public function show(int $categoryId): \Illuminate\Http\JsonResponse
    {
        $item = Category::find($categoryId);
        return response()->json($item);
    }
    public  function  destroy(int $id): \Illuminate\Http\JsonResponse
    {
        $this->deteteImages($id);
        Category::destroy($id);
        return response()->json(null, 204);
    }
}
