<?php


namespace App\Http\Controllers;


use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class ObjectController extends BaseController
{
    public function getObjects($collectionID)
    {
        // KODY TABELI:
        // 1- value_ints
        // 2- value_floats
        // 3- value_strings
        // 4- value_dates
        $objects = DB::table('objects')
            ->select('id', 'collection_id', 'name')
            ->where('collection_id',$collectionID)
            ->get();
        $objectAttributes = DB::table('object_attributes')
        ->select('id', 'collection_id', 'label','type')
        ->where('collection_id',$collectionID)
        ->get();
        $vals=[];
        $types=[];
        for($i=0;$i<sizeof($objectAttributes);$i++) {
            $attr = get_object_vars($objectAttributes[$i]);
            array_push($types, $attr['type']);
        }

        $counter=sizeof(array_count_values($types));
        $numberOfTypes=array_count_values($types);
        $numberOfTypes=array_keys($numberOfTypes);

        //dump($counter);
        for ($i=0;$i<sizeof($objects);$i++) {
            $obs = get_object_vars($objects[$i]);
            foreach ($numberOfTypes as $type) {

                if ($type == 1) {
                    array_push($vals, DB::table('value_ints')
                        ->join('object_attributes', 'value_ints.attribute_id', '=', 'object_attributes.id')
                        ->select('object_attributes.label', 'value')
                        ->where('collection_id', $collectionID)
                        ->where('object_id', $obs['id'])
                        ->get());
                } elseif ($type == 2) {
                    array_push($vals, DB::table('value_floats')
                        ->join('object_attributes', 'value_floats.attribute_id', '=', 'object_attributes.id')
                        ->select('object_attributes.label', 'value')
                        ->where('collection_id', $collectionID)
                        ->where('object_id', $obs['id'])
                        ->get());
                } elseif ($type  == 3) {
                    array_push($vals, DB::table('value_strings')
                        ->join('object_attributes', 'value_strings.attribute_id', '=', 'object_attributes.id')
                        ->select('object_attributes.label', 'value')
                        ->where('collection_id', $collectionID)
                        ->where('object_id', $obs['id'])
                        ->get());
                } elseif ($type==4){
                    array_push($vals, DB::table('value_dates')
                        ->join('object_attributes', 'value_dates.attribute_id', '=', 'object_attributes.id')
                        ->select('object_attributes.label', 'value')
                        ->where('collection_id', $collectionID)
                        ->where('object_id', $obs['id'])
                        ->get());
                }
            }
        }

        $response=[];
        $chunks=array_chunk($vals,sizeof($objects),true);
        for($i=0;$i<sizeof($objects);$i++){
            array_push($response,array_merge(get_object_vars($objects[$i]),$chunks[$i]));
        }
        //dump($response);
        $response=json_encode($response);
        return $response;
    }
    public function createObject(Request $request){
        $test=$request->name;
        // name, label, type
        return response()->json([
            "message"=>"Object created successfully"
        ],201);
    }
    public function createAttributes(Request $request){
        $test=$request->name;
        // name, label, type
        return response()->json([
            "message"=>"Attributes created successfully"
        ],201);
    }
}

