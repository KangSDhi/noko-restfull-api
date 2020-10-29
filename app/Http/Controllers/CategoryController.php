<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\ResponseController as Response;

class CategoryController extends Controller
{
    public function listCategory()
    {
        $query = DB::select("select * from category");
        $code = 200;
        $status = "OK";
        $message = "Data berhasil diload";
        return response()->json(Response::successResponseWithData($query, $code, $status, $message), $code);
    }

    private function validator($category_name, $category_image, $category_color){
        $data = [
            "category_name" => $category_name,
            "category_image" => $category_image,
            "category_color" => $category_color
        ];

        $rule = [
            "category_name" => "required",
            "category_image" => "required",
            "category_color" => "required"
        ];

        $message = [
            "category_name.required" => "Nama kategori Kosong!",
            "category_image.required" => "Path Gambar Kosong!",
            "category_color.required" => "Warna Kosong!"
        ];

        return \Validator::make($data, $rule, $message);
    }

    public function addCategory(Request $request)
    {
        $categoryName = $request->input('category_name');
        $categoryImage = $request->input('category_image');
        $categoryColor = $request->input('category_color');

        $validator = $this->validator($categoryName, $categoryImage, $categoryColor);

        if ($validator->fails()) {
            $code = 400;
            $status = "BAD REQUEST";
            $message = "Validasi Error!";
            return response()->json(Response::errorResponseWithData($validator->errors(), $code, $status, $message), $code);
        }else{

            DB::table('category')->insert([
                "category_name" => $categoryName,
                "image" => $categoryImage,
                "color" => $categoryColor
            ]);

            $code = 200;
            $status = "OK";
            $message = "Tambah Data Berhasil!";
            return response()->json(Response::successResponse($code, $status, $message), $code);
        }
    }

    public function updateCategory(Request $request, $id)
    {
        $categoryName = $request->input('category_name');
        $categoryImage = $request->input('category_image');
        $categoryColor = $request->input('category_color');

        $query = DB::table('category')->where('id', $id)->count();

        if ($query < 1) {
            $code = 404;
            $status = "NOT FOUND";
            $message = "Kategori Tidak Ditemukan!";
            return response()->json(Response::errorResponse($code, $status, $message), $code);
        }else{

            $validator = $this->validator($categoryName, $categoryImage, $categoryColor);

            if ($validator->fails()) {
                $code = 400;
                $status = "BAD REQUEST";
                $message = "Validasi Error!";
                return response()->json(Response::errorResponseWithData($validator->errors(), $code, $status, $message), $code);
            } else {
                DB::beginTransaction();
                try {
                    DB::table('category')
                        ->where('id', $id)
                        ->update([
                            "category_name" => $categoryName,
                            "image" => $categoryImage,
                            "color" => $categoryColor
                        ]);
                    DB::commit();
                    $success = true;
                } catch (\Exception $e) {
                    DB::rollback();
                    $success = false;
                }

                if($success){
                    $code = 200;
                    $status = "OK";
                    $message = "Data Berhasil Diperbarui";
                    return response()->json(Response::successResponse($code, $status, $message), $code);
                }else{
                    $code = 400;
                    $status = "BAD REQUEST";
                    $message = "DB Trans Error";
                    return response()->json(Response::errorResponse($code, $status, $message), $code);
                }
            }
        }
    }

    public function deleteCategory($id){
        $whereQuery = DB::table('category')->where('id', $id)->count();
        if ($whereQuery < 1) {
            $code = 404;
            $status = "NOT FOUND";
            $message = "Kategori Tidak Ditemukan!";
            return response()->json(Response::errorResponse($code, $status, $message), $code);
        } else {
            DB::beginTransaction();
            try {
                DB::table('category')->where('id', $id)->delete();
                DB::commit();
                $success = true;
            } catch (\Exception $th) {
                DB::rollback();
                $success = false;
            }

            if($success){
                $code = 200;
                $status = "OK";
                $message = "Data Berhasil Dihapus";
                return response()->json(Response::successResponse($code, $status, $message), $code);
            }else{
                $code = 400;
                $status = "BAD REQUEST";
                $message = "DB Trans Error";
                return response()->json(Response::errorResponse($code, $status, $message), $code);
            }

        }
    }
}
