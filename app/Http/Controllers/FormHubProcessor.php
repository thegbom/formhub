<?php

namespace App\Http\Controllers;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use DB;
use Illuminate\Support\Facades\Log;
use App\Formhub;
use App\Formline;

/**
 * Description of FormHubProcessor
 *
 * @author Torstein
 */
class FormHubProcessor {
    
    public static $DO_NOT_SHOW=6;

    public function getTableList() {
        $list = NULL;
        if (env("DB_CONNECTION") == "mysql") {
            $list = $this->getMysqlTableList();
        } else if (env("DB_CONNECTION") == "mysql") {
            $list = $this->getPsqlTableList();
        }
        return $list;
    }

    public function getColumnList($table) {
        $list = NULL;
        if (env("DB_CONNECTION") == "mysql") {
            $list = $this->getMysqlColumnList($table);
        } else if (env("DB_CONNECTION") == "mysql") {
            $list = $this->getPsqlTableList($table);
        }
        return $list;
    }

    private function getMysqlTableList() {
        //$sql="";
        $result = DB::select('SELECT table_name FROM information_schema.tables where table_schema= :dbname', ['dbname' => env('DB_DATABASE')]);
        //$result =  DB::table('information_schema.tables')->where("table_schema","=","'".env('DB_DATABASE')."'")->get();
        //dd($result);
        return $result;
    }

    private function getPsqlTableList() {
        return NULL;
    }

    private function getMysqlColumnList($table) {
        //$sql="SELECT column_name, data_type FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$table."' AND table_schema='".env('DB_DATABASE')."'";
        $result = DB::select('SELECT column_name, data_type FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = :table AND table_schema= :dbname', ['dbname' => env('DB_DATABASE'), "table" => $table]);
        return $result;
    }

    private function getPsqlColumnList($table) {
        return NULL;
    }

    public function tableHub() {
        $tables = array();
        $tables[] = ["text" => "", "value" => ""];
        foreach ($this->getTableList() as $tableItem) {
            $tables[] = ["text" => $tableItem->table_name, "value" => $tableItem->table_name];
        }
        return [
            ["id" => 1, "component_type" => 2, "options" => $tables, "label" => "Select table", "startvalue" => ""],
            ["id" => 2, "component_type" => 2, "options" => $this->countries(), "label" => "Select language", "startvalue" => ""],
        ];
    }

    public function countries() {
        $types = [
            ["text" => "Norway", "value" => "NO"],
            ["text" => "Sweden", "value" => "SE"],
            ["text" => "Denmark", "value" => "DK"],
            ["text" => "Finland", "value" => "FI"]/* ,
                  ["text" => "Germany", "value" => "DE"],
                  ["text" => "Spain", "value" => "ES"],
                  ["text" => "Austria	AT"],
                  ["text" => "Bosnia and Herzegovina", "value" => "BA"],
                  ["text" => "Belgium", "value" => "BE"],
                  ["text" => "Bulgaria", "value" => "BG"],
                  ["text" => "Belarus", "value" => "BY"],
                  ["text" => "Switzerland", "value" => "CH"],
                  ["text" => "France", "value" => "FR"],
                  ["text" => "United Kingdom of Great Britain and Northern Ireland", "value" => "GB"],
                  ["text" => "Croatia", "value" => "HR"],
                  ["text" => "Hungary", "value" => "HU"],
                  ["text" => "Ireland", "value" => "IE"],
                  ["text" => "Iceland", "value" => "IS"],
                  ["text" => "Italy", "value" => "IT"],
                  ["text" => "Lithuania", "value" => "LT"],
                  ["text" => "Luxembourg", "value" => "LU"],
                  ["text" => "Latvia", "value" => "LV"],
                  ["text" => "Monaco", "value" => "MC"],
                  ["text" => "Montenegro", "value" => "ME"],
                  ["text" => "Portugal", "value" => "PT"],
                  ["text" => "Romania", "value" => "RO"],
                  ["text" => "Serbia", "value" => "RS"],
                  ["text" => "Slovenia", "value" => "SI"],
                  ["text" => "Slovakia", "value" => "SK"],
                  ["text" => "Turkey", "value" => "TR"],
                  ["text" => "Ukraine", "value" => "UA"], */
        ];
        return $types;
    }

    public function generateComponentTypeForm($table) {
        $form = array();

        $columns = $this->getFormLines($table);
        Log::debug(count($columns));
        if ($columns != null) {
            foreach ($columns as $column) {
                //Log::debug(print_r($column, true));
                $form[] = ["id" => $column->id, "component_type" => 2, "options" => $this->componentTypes(), "label" => $column->column_name, "startvalue" => $column->component_type];
            }
        }
        return $form;
    }

    public function getFormLines($table) {
        $formHub = FormHub::where('table_name', $table)->first();
        //print($table."<br/>");
        //var_dump($formHub);
        $formLines = Formline::where('formhub_id', $formHub->id)->get();
        return $formLines;
    }

    public function componentTypes() {
        $types = [
            ["text" => "", "value" => 0],
            ["text" => "Input", "value" => 1],
            ["text" => "Select", "value" => 2],
            ["text" => "Radio buttons", "value" => 3],
            ["text" => "Checkbox", "value" => 4],
            ["text" => "Read only", "value" => 5],
            ["text" => "Do not show", "value" => 6],
        ];
        return $types;
    }
    
    public function showUserTable($table, $language){
        Log::debug($table." ".$language);
        $form=array();
        $sql = "SELECT form.post_url, form.get_url, form.redirect_url, form.id as formhub_id, ";
        $sql .= "formline.column_name, formline.component_type, formline.id ";
        $sql .= "FROM formhubs form, formlines formline ";
        $sql .= "WHERE table_name = :table_name ";
        $sql .= "AND form.id = formline.formhub_id ";
        $sql .= "ORDER by id";
        $formlines = DB::select($sql,['table_name'=>$table]);
        $lineCount=0;
        $lines=array();
        $languageSQL = new LanguageProcessor();
        foreach($formlines as $line){
            if($lineCount==0){
                $label=$languageSQL->showLabel($language, null, $line->formhub_id);  
                $form[]=["header"=>["table"=>$table, "language"=>$language, "post_url"=>$line->post_url, "get_url"=>$line->get_url, "label"=>$label]];
            }
            $label = $languageSQL->showLabel($language, $line->id, null);
            Log::debug("Component_type: " . $line->component_type);
            if ($line->component_type == 2) {
                $options = $languageSQL->showSelectLabel($language, $line->id);
                $lines[] = ["id" => $line->id, "component_type" => 2, "options" => $options, "label" => $label, "startvalue" => ""];
            } else {
                $lines[] = ["id" => $line->id, "component_type" => $line->component_type, "label" => $label, "startvalue" => ""];
            }
            $lineCount++;
        }
        $form[]=["lines"=>$lines];
        return $form;
    }
    
    public function storeUserTable($json, $table){
        Log::debug(print_r($json,true));
        $formHub = FormHub::where('table_name', $table)->first();
        Log::debug("FormHubProcessor::Saving table name: ".$formHub->table_name);
        $tableID=null;
        $valueArray = array();
        $columnArray = array();
        foreach ($json as $line){
            $formline = Formline::where("id",$line["id"])->first();
            //Log::debug(print_r($formline,true));
            if($formline->component_type==FormHubProcessor::$DO_NOT_SHOW && $formline->column_name=="id"){  
               $tableID=$line["startvalue"];
            }else{
                if($formline->component_type!=FormHubProcessor::$DO_NOT_SHOW){
                   $valueArray[]=$line["startvalue"];
                   $columnArray[]=$formline->column_name;
                }
            }   
        }
        if($tableID==null){
            $columnString="";
            $questionString="";
            foreach ($columnArray as $name){
                if($columnString!=""){
                    $columnString.=",";
                    $questionString.=",";
                }
                $columnString.=$name;
                $questionString.="?";
            }
            $sql="insert into ".$table." (".$columnString.") values(".$questionString.")";
            Log::debug("FormHubProcessor::Insert-SQL: ".$sql);
            DB::insert($sql,$valueArray);
        }else{
            $valueArray[]=$tableID;
            $updateString="";
            foreach ($olumnArray as $name){
                if($updateString!=""){
                    $updateString.=",";
                }
                $updateString.=$name.=" = ?";
            }
            $sql="update ".table." SET ".$updateString." WHERE id=?";
            DB::update($sql,$valueArray);
        }
        
    }
}
