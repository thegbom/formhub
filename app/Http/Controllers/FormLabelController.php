<?php

namespace App\Http\Controllers;

use Log;
use App\Formhub;
use App\Formline;
use App\Language;
use Illuminate\Http\Request;

class FormLabelController extends Controller
{
    private $HEADER_NAME = "Header";
    
    public function showLabelController(Request $request, $table){
        $form=array();
        $formHub = FormHub::where('table_name', $table)->first();
        $labels = Language::where('formhub_id', $formHub->id)->orderBy('pos','asc')->orderBy('formline_id','asc')->get();
        //Log::debug(count($formLines));
        
        foreach ($labels as $column){
            $columnName="";
            if(isset($column->formline_id) && $column->formline_id!=NULL){
                $formLine = Formline::where('id',$column->formline_id)->first();
                $columnName=$formLine->column_name;
            }else{
                $formLine=NULL;
                $columnName=$this->HEADER_NAME;
            }
            //Log::debug(print_r($formLine, true));   
            if($formLine==NULL || !isset($formLine->component_type) || $formLine->component_type != /*FormHubProcessor::DO_NOT_SHOW*/6){
                //Log::debug(print_r($formLine, true));
                $form[] = ["id" => $column->id, "component_type" => 1, "label" => $columnName, "startvalue" => $column->description];
            } 
        }
        return $form;
    }    
    
    public function saveLabel(Request $request, $table) {
        $list=$request->all();
        foreach ($list as $item){
            Log::debug(print_r($item, true));
            if(!array_key_exists("pos",$item)){    
                $label = Language::find($item["id"]);
            }else{
                if($item["pos"]!=null){
                   $formHub = FormHub::where('table_name', $table)->first();
                   $label = Language::where([['formhub_id', "=", $formHub->id],['pos', "=", $pos]])->first();
                }
            }
            $label->description=$item["value"];
            $label->save();
        }
    }
    
    
    public function saveSelectLabel(Request $request, $language, $formid){
        $data=$request->all();
        Log::debug("Saving a item...");
        //Log::debug(print_r($data));
        $laguageSelect = new \App\Language_select();
        $laguageSelect->formline_id=$formid;
        $laguageSelect->language=$language;
        $laguageSelect->description=$data["descr"];
        $laguageSelect->value=$data["value"];
        $laguageSelect->save();
    }
    
    public function showSelectLabel(Request $request,$language, $formid){
        $languageProcessor = new LanguageProcessor();
        return $languageProcessor->showSelectLabel($language, $formid);
    }
    
    public function showLabel(Request $request,$lang,$formid){
        $languageProcessor = new LanguageProcessor();
        return $languageProcessor->showLabel($lang, $formid);
    }
    
    public function deleteSelectLabel(Request $request, $id){
        Log::debug("Deleting id: ".$id);
        $laguageSelect = \App\Language_select::find($id);
        $laguageSelect->delete();
    }

}
