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

    public function showLabel($lang, $formid = null, $table = null) {
        Log::debug("Table: " . $table);
        $formhubid = null;
        if ($formhubid == null || $formhubid == -1) {
            $formHub = \App\FormHub::where('table_name', $table)->first();
            $formhubid = $formHub->id;
        }
        Log::debug("Formhubid: " . $formhubid);
        if ($formhubid == null) {
            $laguageSQL = \App\Language::where([['formline_id', $formid], ['language', $lang]])->first();
        } else {
            //dd($lang);
            $laguageSQL = \App\Language::where([['formhub_id', $formhubid], ['language', $lang]])->whereNull('formline_id')->first();
        }
        //dd($laguageSQL);
        if (count($laguageSQL) > 0) {
            return $laguageSQL->description;
        } else {
            Log::debug("Not found");
            return $laguageSQL;
        }
    }

    public function showSelectLabel($language, $formid = null, $table = null) {
        $id=null;
        Log::debug("formid: ".$formid);
        if ($formid == null || $formid == -1) {
            Log::debug("Has to get data from main table");
            $formHub = \App\FormHub::where('table_name', $table)->first();
            $formline = \App\Formline::where('formhub_id', $formHub->id)->get();
            $name="";
            if (count($formline) > 0) {
                foreach ($formline as $row) {
                    if ($row->component_type == FormHubProcessor::$SELECT) {
                        $name=$row->column_name;
                        $laguageSelectList = \App\Language_select::where([['formline_id', $row->id], ['language', $language]])->first();
                        //Log::debug(print_r($laguageSelectList,true));
                        if ($laguageSelectList != null) {                          
                            $formid = $row->id;
                            Log::debug("Getting language: " . $language . " Formid: " . $formid . " for language-select");
                            $laguageSelectList = \App\Language_select::where([['formline_id', $formid], ['language', $language]])->select('description as text', 'value', 'formline_id')->get();
                            if($laguageSelectList==null){
                                $id=$formid;
                                break;
                            }
                        }else{
                            $id=$row->id;
                            break;
                        }   
                    }
                }
            }
        }else{
            $formline = \App\Formline::find($formid);
            $name=$formline->column_name;
            $id=$formid;
        }
        if ($id != null) {
            Log::debug("Getting language: " . $language . " Formid: " . $formid . " for language-select");
            $laguageSelectList = \App\Language_select::where([['formline_id', $id], ['language', $language]])->select('description as text', 'value', 'formline_id')->get();
            $result=["name"=>$name,"formid"=>$id,"list"=>$laguageSelectList];
        } else {
            $laguageSelectList = null;
            $result=null;
        }
        return $result;
    }

}
