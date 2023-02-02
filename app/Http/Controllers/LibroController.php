<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use Carbon\Carbon;

class LibroController extends Controller{

    public function index(){
        $datosLibros = Libro::all();

        return response()->json($datosLibros);
    }
    public function guardar(Request $request){

        $datosLibro= new Libro;

        if($request->hasFile('imagen')){
            $nombreArchivoOriginal=$request->file('imagen')->getClientOriginalName();
            $nuevoNombre= Carbon::now()->timestamp."_".$nombreArchivoOriginal;
            $carpetaDestino='./upload/';
            $request->file('imagen')->move($carpetaDestino, $nuevoNombre);
        $datosLibro->titulo=$request->titulo;
        $datosLibro->imagen=ltrim($carpetaDestino,'.').$nuevoNombre;
        $datosLibro->save();
        }
        return response()->json( $nuevoNombre );
    }
    public function ver($id){

        $datosLibro =new Libro;
        $datosEncontrados= $datosLibro->find($id);
        return response()->json($datosEncontrados);
    }

    public function eliminar($id){

        $datosLibro= Libro::find($id);
        if($datosLibro){
            $rutasArchivo=base_path('public').$datosLibro->imagen;
            if(file_exists($rutasArchivo)){
                unlink($rutasArchivo);

            }
            $datosLibro->delete();

        }

        return response()->json("Registro borrado");
    }
    public function actualizar(Request $request,$id){

        $datosLibro= Libro::find($id);

        if($request->hasFile('imagen')){

            $datosLibro= Libro::find($id);
            if($datosLibro){
                $rutasArchivo=base_path('public').$datosLibro->imagen;
                if(file_exists($rutasArchivo)){
                    unlink($rutasArchivo);
    
                }
                $datosLibro->delete();
    
            }

            $nombreArchivoOriginal=$request->file('imagen')->getClientOriginalName();
            $nuevoNombre= Carbon::now()->timestamp."_".$nombreArchivoOriginal;
            $carpetaDestino='./upload/';
            $request->file('imagen')->move($carpetaDestino, $nuevoNombre);
    
        $datosLibro->imagen=ltrim($carpetaDestino,'.').$nuevoNombre;
        $datosLibro->save();
        }



        if($request->input('titulo')){

            $datosLibro->titulo=$request->input('titulo');

        }
        $datosLibro->save();


        return response()->json("Datos actualizados");

    }

}
