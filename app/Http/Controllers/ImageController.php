<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //this function shows all the Images
    public function index(){

        $image = Image::all();
        return response()->json($image);
    }

    //it get all images ids
    public function show($id){
        $image = Image::find($id);
        return response()->json($image);
    }


    public function store(Request $request)
    {

        $upload = (object) ['image' => ""];

        $page = new Image();

        if ($request->hasFile('image')) {
            $original_filename = $request->file('image')->getClientOriginalName();
            $original_filename_arr = explode('.', $original_filename);
            $file_ext = end($original_filename_arr);
            $destination_path = './images/';
            $image = 'SM-' . time() . '.' . $file_ext;

            $imagedetails = getimagesize($_FILES['image']['tmp_name']);

            $getFileSize   =  $request->file('image')->getSize();  //file size in e.g 1.34kb = 1340
            $imageFileSize =  number_format($getFileSize / 1048576,2);

            $width = $imagedetails[0];
            $height = $imagedetails[1];


            $page->fileName      = $image;
            $page->fileExtension = $file_ext;
            $page->fileSize      = $imageFileSize; //in KB
            $page->width         = $width;
            $page->height        = $height;

            $page->save();

            if ($request->file('image')->move($destination_path, $image)) {
                $upload->image = '/images/' . $image;
                return $this->responseRequestSuccess($upload);
            } else {
                return $this->responseRequestError('Cannot upload file');
            }
        } else {
            return $this->responseRequestError('File not found');
        }

    }

    protected function responseRequestSuccess($ret)
    {
        return response()->json(['status' => 'success', 'data' => $ret], 200)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

    protected function responseRequestError($message = 'Bad request', $statusCode = 200)
    {
        return response()->json(['status' => 'error', 'error' => $message], $statusCode)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }
    //updating an image
    public function update(Request $request, $id){

        $upload = (object) ['image' => ""];
        $page = Image::find($id);

        if ($request->hasFile('image')) {
            $original_filename = $request->file('image')->getClientOriginalName();
            $original_filename_arr = explode('.', $original_filename);
            $file_ext = end($original_filename_arr);
            $destination_path = './images/';
            $image = 'SM-' . time() . '.' . $file_ext;

            $imagedetails = getimagesize($_FILES['image']['tmp_name']);

            $getFileSize   =  $request->file('image')->getSize();  //file size in e.g 1.34kb = 1340
            $imageFileSize =  number_format($getFileSize / 1048576,2);

            $width = $imagedetails[0];
            $height = $imagedetails[1];

            $page->fileName = $page;
            $page->fileExtension = $file_ext;
            $page->fileSize = $imageFileSize; //in KB
            $page->width = $width;
            $page->height = $height;

            $page->save();

            if ($request->file('image')->move($destination_path, $image)) {
                $upload->image = '/images/' . $image;
                return $this->responseRequestSuccess($upload);
            } else {
                return $this->responseRequestError('Cannot upload file');
            }
        } else {
            return $this->responseRequestError('File not found');
        }
    }

    public function Softdelete(Request $request, $id){

        $image = Image::find($id)->update('deleted',0);
        // Set ALL records to a status of 0
        // $test = Image::where(['id',$id], ['deleted',1])->all();
        return $image;

        // Set the passed record to a status of what ever is passed in the Request
        $image->status = $request->deleted;
        return $image->status;
        $image->save();
        return redirect()->back()->with('message', 'Status changed!');

    }

    //delete an image
    public function delete($id){

        $image = Image::find($id);
        if($image == true){
            $image->delete();
            return response()->json("Image Deleted successfully");
        }else{

            return response()->json("Requested Image ID is Not available or Already Deleted");
        }


    }


}
