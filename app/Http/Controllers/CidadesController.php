<?php

namespace App\Http\Controllers;

use App\DataTables\CidadeDataTable;
use Illuminate\Http\Request;
use App\Models\Cidade;
use App\Models\Bairro;
use Illuminate\Support\Facades\Validator;
use Flash;

class CidadesController extends Controller
{
    
    
    public function index(CidadeDataTable $cidadeDataTable){
        return $cidadeDataTable->render('cidades.index');
    }
    public function create(){
        return view('cidades.create');
    }
    public function store(Request $request){

        $validation = Validator::make($request->all(),[
            'cidade' => 'required|max:255',
            'uf' => 'required|size:2',            
            'bairros.*' => 'required'
        ],[
            'cidade.*' => 'Digite o nome da cidade corretamente',
            'uf.*' => 'Informe o estado corretamente',
            'bairros.*.*' => 'Digite o nome do bairro corretamente'            
        ]);
        if(!$validation->fails()){
            $cidade = Cidade::create([
                'cidade' => $request['cidade'],
                'uf' => $request['uf'],
                'active' => 1
            ]);                        
            if($cidade->save()){
                //bairros                
                foreach($request['bairros']??[] as $bairro){
                    $bairro = Bairro::create([
                        'nome' => $bairro,        
                        'cidade_id' => $cidade->id,
                        'active' => 1    
                    ]);

                    
                    $bairro->save();
                }

                Flash::success("Cidade adicionada");
                return redirect(route('cidades.index'));
            }
            Flash::error("Ocorreu um erro ao armazenar a cidade");
            return redirect(route('cidades.create'));
        }
        
        
        Flash::error($validation->errors()->first());
        return redirect(route('cidades.create'));

    }
    public function edit(Cidade $cidade){   
        $bairros = Bairro::where('cidade_id','=',$cidade->id)->get();    
        return view('cidades.edit',['cidade' => $cidade,'bairrosPrevius' => $bairros]);
    }


    public function update($id,Request $request){        
        $cidade = Cidade::where('id',$id)->firstOrFail();

        $validation = Validator::make($request->all(),[
            'cidade' => 'required|max:255',
            'uf' => 'required|size:2',            
        ],[
            'cidade.*' => 'Digite o nome da cidade corretamente',
            'uf.*' => 'Informe o estado corretamente'            
        ]);
        if(!$validation->fails()){
            $cidade->cidade = $request['cidade'];
            $cidade->uf = $request['uf'];                

            if($cidade->save()){

                Bairro::where('cidade_id','=',$cidade->id)->delete();
                
                foreach($request['bairros']??[] as $bairro){
                    $bairro = Bairro::create([
                        'nome' => $bairro,        
                        'cidade_id' => $cidade->id,
                        'active' => 1    
                    ]);                    
                    $bairro->save();
                }

                Flash::success("Cidade atualizada com sucesso");
                return redirect(route('cidades.index'));
            }

            Flash::error("Ocorreu um erro ao atualizar a cidade");
            return redirect(route('cidades.index'));
        }

        Flash::error($validation->errors()->first());
        return redirect(route('cidades.create'));
    }


    public function destroy($id)
    {
        $cidade = Cidade::where('id',$id)->firstOrFail();

        $cidade->active = 0;
        $cidade->save();
        Flash::success("Cidade desativada com sucesso");

        return redirect(route('cidades.index'));
    }


    

}
