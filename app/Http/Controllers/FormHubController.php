<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use \App\Formline;
use App\Language;
use \App\Language_select;
//use App\Http\Controllers\FormHubProcessor;
use App\Formhub;

class FormHubController extends Controller
{
    public function getTable(Request $request){
        $formHubProcessor = new FormHubProcessor();
        return $formHubProcessor->getTableList();
    }
    
    public function getColumnList(Request $request, $table){
        $formHubProcessor = new FormHubProcessor();
        return $formHubProcessor->getColumnList($table);
    }
    
    public function index()
    {
        return Formline::latest()->get();
    }
    
    public function languageSelect(){
        $list=Language_select::latest()->get();
        $ls=array();
        
        foreach ($list as $item){
            $ls[]=["text"=>$item->description, "value"=>$item->value, "formline_id"=>$item->formline_id];
        }
        return ["options"=>$ls];
        
        /*{
            selected: '1',
            options: [  
                { text: 'Input', value: '1' },
                { text: 'Select', value: '2' },
                { text: 'TextArea', value: '3' }
            ]
          }*/
    }
    
    public function storeFormLines(Request $request)
    {
        $list=$request->all();
        
        foreach($list as $item){
            Log::debug(print_r($item));
            $formLine = Formline::find($item["id"]);
            $formLine->component_type=$item['value'];        
            $formLine->save();     
            if($formLine->component_type==FormHubProcessor::$DO_NOT_SHOW){
                Log::debug("Form-id: ".$formLine->id);
                $label= Language::where("formline_id",$formLine->id)->delete();
            }
        }
        
        return 200;
    }
    public function destroy($id)
    {
        $task = Formline::findOrFail($id);
        $task->delete();
        return 204;
    }
    
    public function storeTableHub(Request $request){
        $list=$request->all();
        $formHub = new FormHub();
        foreach($list as $item){
           // Log::debug(print_r($item,true));
           if($item["id"]==1){
             $formHub->table_name=$item['value'];
             $formHub->form_name=$item['value'];
           }else if($item["id"]==2){
             $formHub->default_language=$item['value'];
           }  
        } 
        $formHub->save();
        $formLine=Formline::where('formhub_id', $formHub->id);
        Log::debug("ID: ".print_r($formLine));
        if ($formLine!=null && !isset($formLine->id)) {
            $formHubProcessor = new FormHubProcessor();
            $columnList = $formHubProcessor->getColumnList($formHub->table_name);
            Log::debug(print_r($columnList,true));
            //The first is the header for the form
            $language = new Language();
            $language->formhub_id=$formHub->id;
            $language->language=$formHub->default_language;
            $language->save();
            foreach ($columnList as $column) {
                $formLine = new Formline();
                $formLine->formhub_id = $formHub->id;
                $formLine->column_name = $column->column_name;
                $formLine->save();
                $language = new Language();
                $language->formhub_id=$formHub->id;
                $language->formline_id=$formLine->id;
                $language->language=$formHub->default_language;
                $language->save();
            }
        }
        return 200;
    }
    
    
    
    public function tableHub(){
        $formHubProcessor = new FormHubProcessor();
        return $formHubProcessor->tableHub();
    }
    
    public function generateComponentTypeForm(Request $request, $table){
        $formHubProcessor = new FormHubProcessor();
        return $formHubProcessor->generateComponentTypeForm($table);
    }
    
    public function showUserTable(Request $request, $table, $language){
        Log::debug($table." ".$language);
        $formProcessor = new FormHubProcessor();
        return $formProcessor->showUserTable($table, $language);
    }
    
    public function storeUserTable(Request $request,$table){
        $json=$request->all();
        $formProcessor = new FormHubProcessor();
        return $formProcessor->storeUserTable($json,$table);
    }
}
//curl -X POST -H 'Content-type: application/json' --data '{"text":"Hello, World!"}' https://hooks.slack.com/services/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
//https://hooks.slack.com/services/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