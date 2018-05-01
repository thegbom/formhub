<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;


use Log;
/**
 * Description of LanguageProcessor
 *
 * @author Torstein
 */
class LanguageProcessor {
    public function showLabel($lang,$formid=null,$formhubid=null){
        
        if($formhubid==null){
           $laguageSQL = \App\Language::where([['formline_id',$formid],['language',$lang]])->first();
        }else{
            //dd($lang);
            $laguageSQL = \App\Language::where([['formhub_id',$formhubid],['language',$lang]])->whereNull('formline_id')->first();
        }
        //dd($laguageSQL);
        if(count($laguageSQL)>0){
           return  $laguageSQL->description;
        }else{
            Log::debug("Not found");
           return $laguageSQL;
        }
    }
    
    
    
    public function showSelectLabel($language, $formid){
        Log::debug("Getting language: ".$language." Formid: ".$formid." for language-select");
        $laguageSelectList = \App\Language_select::where([['formline_id',$formid],['language',$language]])->select('description as text', 'value', 'formline_id')->get();
        return $laguageSelectList;
    }
}
