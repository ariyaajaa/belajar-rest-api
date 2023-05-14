<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Log;
use App\Models\Recipe;
use App\Models\Tool;
use App\Models\Ingredients;

class AdminController extends Controller
{

    public function dashboard(){

        $totalRecipes = Recipe::count();
        $totalPublishRecipes = Recipe::where('status_resep','publish')->count();
        $popularRecipe = DB::table('resep')
                            ->select('judul',DB::raw('count(idresep_view) as jumlah'))
                            ->leftJoin('resep_view','resep_idresep','=','resep_view.resep_idresep')
                            ->groupBy('judul')
                            ->orderBy(DB::raw('count(idresep_view)'), 'desc')
                            ->limit(10)
                            ->get();
        return response()->json([
            "data" => [
                "msg" => 'dashboard monitoring',
                "totalRecipes" => $totalRecipes,
                "totalPublishRecipe" => $totalPublishRecipes,
                "popularRecipe" => $popularRecipe
            ]
        ], 200);

    }

    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'nama' => 'required',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:8',
            'confirmation_password' => 'required|same:password',
            'role' => 'required|in:admin,user',
            'status' => 'required|in:aktif,non-aktif',
            'email_validate' => 'required|email'
        ]);

        if($validator->fails()){
            return messageError($validator->messages()->toArray());
        }

        $user = $validator->validated();

        User::create($user);

        return response()->json([
            "data" => [
                'msg' => 'berhasil login',
                'nama' => $user['nama'],
                'email' => $user['email'],
                'role' => $user['role']
            ]
        ], 200);
    }

    public function show_register(){
        $users = User::where('role','user')->get();

        return response()->json([
            "data" => [
                'msg' => "user registrasi",
                'data' => $users
            ]
            ], 200);
    }

    public function show_register_by_id($id){
        $user = User::find($id);
        

        return response()->json([
            "data" => [
                'msg' => "user id: {$id}",
                'data' => $user
            ]
            ], 200);
    }

    public function update_register(Request $request, $id){
        $user = User::find($id);

        if($user) {
            $validator = Validator::make($request->all(),[
                'nama' => 'required',
                'password' => 'required|min:8',
                'confirmation_password' => 'same:password',
                'role' => 'required|in:admin,user',
                'status' => 'required|in:aktif,non-aktif',
                'email_validate' => 'required|email'
            ]);

            if($validator->fails()){
                return messageError($validator->messages()->toArray());
            }
    
            $data = $validator->validated();

            User::where('id',$id)->update($data);

            return response()->json([
                "data" => [
                    'msg' => "user dengan id: {$id} berhasil diupdate",
                    'nama' => $data['nama'],
                    'email' => $user['email'],
                    'role' => $data['role']
                ]
            ], 200);
        }

        return response()->json([
            "data" => [
                'msg' => "user id {$id} tidak ditemukan"
            ]
        ], 422);

    }

    public function delete_register($id){
        $user = User::find($id);

        if($user){
            $user->delete();

            return response()->json([
                "data" => [
                    'msg' => "user dengan id: {$id}, berhasil dihapus"
                ]
            ], 200);
        }

        return response()->json([
            "data" => [
                'msg' => "user dengan id: {$id}, tidak ditemukan"
            ]
        ], 422);
    
    }

    public function activation_account($id) {
        $user = User::find($id);

        if($user){
            User::where('id',$id)->update(['status' => 'aktif']);

            return response()->json([
                "data" => [
                    'msg' => "user dengan id: {$id}, berhasil diaktifkan"
                ]
            ], 200);
        }

        return response()->json([
            "data" => [
                'msg' => "user dengan id: {$id}, tidak ditemukan"
            ]
        ], 422);
    }

    public function deactivation_account($id) {
        $user = User::find($id);

        if($user){
            User::where('id',$id)->update(['status' => 'non-aktif']);

            return response()->json([
                "data" => [
                    'msg' => "user dengan id: {$id}, berhasil dinonaktifkan"
                ]
            ], 200);
        }

        return response()->json([
            "data" => [
                'msg' => "user dengan id: {$id}, tidak ditemukan"
            ]
        ], 422);
    }

    public function create_recipe(Request $request) {
        $validator = Validator::make($request->all(),[
            'judul' => 'required|max:255',
            'gambar' => 'required|mimes:png,jpg,jpeg|max:2048',
            'cara_pembuatan' => 'required',
            'video' => 'required',
            'user_email' => 'required',
            'bahan' => 'required',
            'alat' => 'required',
            
        ]);

        if($validator->fails()){
            return messageError($validator->messages()->toArray());
        }

        $thumbnail = $request->file('gambar');

        $filename = now()->timestamp."_".$request->gambar->getClientOriginalName();
        $thumbnail->move('uploads',$filename);

        $recipeData = $validator->validated();

        $recipe = Recipe::create([
            'judul' => $recipeData['judul'],
            'gambar' => 'uploads/'.$filename,
            'cara_pembuatan' => $recipeData['cara_pembuatan'],
            'video' => $recipeData['video'],
            'user_email' => $recipeData['user_email'],
            'status_resep' => 'submit',
        ]);

        foreach(json_decode($request->bahan) as $bahan){
            Ingredients::create([
                'nama' => $bahan->nama,
                'satuan' => $bahan->satuan,
                'banyak' => $bahan->banyak,
                'keterangan' => $bahan->keterangan,
                'resep_idresep' => $recipe->id,
            ]);
        }

        foreach(json_decode($request->alat) as $alat){
            Tool::create([
                'nama_alat' => $alat->nama,
                'keterangan' => $alat->keterangan,
                'resep_idresep' => $recipe->id,
            ]);
        }

        return response()->json([
            "data" => [
                "msg" => "Resep berhasil disimpan",
                "resep" => $recipeData['judul']
            ]
        ], 200);
    }

    public function update_recipe(Request $request, $id) {
        $validator = Validator::make($request->all(),[
            'judul' => 'required|max:255',
            'gambar' => 'required|mimes:png,jpg,jpeg|max:2048',
            'cara_pembuatan' => 'required',
            'video' => 'required',
            'user_email' => 'required',
            'bahan' => 'required',
            'alat' => 'required',
            
        ]);

        if($validator->fails()){
            return messageError($validator->messages()->toArray());
        }

        $thumbnail = $request->file('gambar');

        $filename = now()->timestamp."_".$request->gambar->getClientOriginalName();
        $thumbnail->move('uploads',$filename);

        $recipeData = $validator->validated();

        Recipe::where('idresep', $id)->update([
            'judul' => $recipeData['judul'],
            'gambar' => 'uploads/'.$filename,
            'cara_pembuatan' => $recipeData['cara_pembuatan'],
            'video' => $recipeData['video'],
            'user_email' => $recipeData['user_email'],
            'status_resep' => 'submit',
        ]);

        Ingredients::where('resep_idresep', $id)->delete();
        Tool::where('resep_idresep', $id)->delete();

        foreach(json_decode($request->bahan) as $bahan){
            Ingredients::create([
                'nama' => $bahan->nama,
                'satuan' => $bahan->satuan,
                'banyak' => $bahan->banyak,
                'keterangan' => $bahan->keterangan,
                'resep_idresep' => $id,
            ]);
        }

        foreach(json_decode($request->alat) as $alat){
            Tool::create([
                'nama_alat' => $alat->nama,
                'keterangan' => $alat->keterangan,
                'resep_idresep' => $id,
            ]);
        }

        return response()->json([
            "data" => [
                "msg" => "Resep berhasil disunting",
                "resep" => $recipeData['judul']
            ]
        ], 200);
    }

    public function delete_recipe($id) {
        Tool::where('resep_idresep', $id)->delete();
        Ingredients::where('resep_idresep', $id)->delete();
        Recipe::where('idresep', $id)->delete();

        return response()->json([
            "data" => [
                "msg" => "Resep berhasil dihapus",
                "resep_id" => $id
            ]
        ], 200);
    }

    public function publish_recipe($id){

        $recipe = Recipe::where('idresep', $id)->get();

        if($recipe) {

            Recipe::where('idresep', $id)->update(['status_resep' => 'publish']);

            Log::create([
                'module' => 'publish resep',
                'action' => 'publish resep dengan id: '.$id,
                'useraccess' => 'Administrator'
            ]);

            return response()->json([
                "data" => [
                    'msg' => 'resep dengan id: '.$id.' berhasil di publish'
                ]
            ], 200);
        }

        return response()->json([
            "data" => [
                'msg' => 'resep dengan id: '.$id.' tidak ditemukan'
            ]
        ], 422);

    }

    public function unpublish_recipe($id){

        $recipe = Recipe::where('idresep', $id)->get();

        if($recipe) {

            Recipe::where('idresep', $id)->update(['status_resep' => 'unpublished']);

            Log::create([
                'module' => 'publish resep',
                'action' => 'unpublish resep dengan id: '.$id,
                'useraccess' => 'Administrator'
            ]);

            return response()->json([
                "data" => [
                    'msg' => 'resep dengan id: '.$id.' berhasil di unpublish'
                ]
            ], 200);
        }

        return response()->json([
            "data" => [
                'msg' => 'resep dengan id: '.$id.' tidak ditemukan'
            ]
        ], 422);

    }

}
