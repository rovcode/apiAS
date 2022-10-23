<?php

namespace App\Http\Controllers;

use App\Models\Files\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Show list of files
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = File::all();
            return response()->json(array('data' => $data, 'success' => true), 200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
    /**
     * Register of the file in the database and folder storage/public
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $message = "";
            if (isset($request['myfile'])) {
                $sizeFile = Validator::make($request->all(), [
                    'myfile' => 'max:500000',
                ]);
                $size = $request->file("myfile")->getSize();
                if ($sizeFile->fails()) {
                    $message = 'Supera el tamaño máximo permitido.';
                } else {
                    $file = new File();
                    $file->path =  $request->file('myfile')->store('public');
                    $file->size = $size;
                    $file->save();
                    $request->file('myfile')->store('public');
                    $message = "Archivo registrado ";
                }
                return response()->json(
                    array(
                        'file' => $file,
                        'message' => $message,
                        'success' => true
                    ),
                    200
                );
            } else {
                $message = "Seleccione un archivo";
                return $message;
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Files\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Files\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Files\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = File::find($id);
            if ($data == null) {
                return response()->json(array('message' => "No existe el registro del archivo"));
            }
            $fileName = explode('/', $data->path)[1];
            if (Storage::disk('public')->exists($fileName)) {
                Storage::disk('public')->delete($fileName);
            }
            File::destroy($id);            
            return response()->json(array('message' => "Archivo eliminado", 'success' => true), 200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
