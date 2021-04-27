<?php


namespace App\Http\Controllers;


use App\Models\Collection;
use App\Models\ObjectAttributes;
use App\Models\ValueDate;
use App\Models\ValueFloat;
use App\Models\ValueInt;
use App\Models\ValueString;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Objects;




class ObjectController extends BaseController
{
    public function getValues($obj,$objToSend){
        if($obj->valueFloats){
            foreach($obj->valueFloats as $value){
                $key=strval($value->attributeName->label);
                $objToSend[$key]=$value->value;
            }
        }

        if($obj->valueInts){
            foreach($obj->valueInts as $value){
                $key=strval($value->attributeName->label);
                $objToSend[$key]=$value->value;
            }
        }
        if($obj->valueStrings){
            foreach($obj->valueStrings as $value){
                $key=strval($value->attributeName->label);
                $objToSend[$key]=$value->value;
            }
        }
        if($obj->valueDates){
            foreach($obj->valueDates as $value){
                $key=strval($value->attributeName->label);
                $objToSend[$key]=$value->value;
            }
        }
        return $objToSend;
    }


    public function getObjects($collection_id){
        $objs=Collection::find($collection_id)->objects;
        $objectsToSend=[];
        foreach($objs as $object){
            array_push($objectsToSend,$this->getObject($object->id,false));
        }
        return json_encode($objectsToSend);
    }

    public function getObject($id,bool $returnJson){
        $obj=Objects::find($id);
        $objToSend=array('id'=>$obj->id,'collection_id'=>$obj->collection_id, 'name'=>$obj->name,
            'photo_path'=>$obj->photo_path);
        //dump($objToSend);
        if($returnJson){
            return json_encode($this->getValues($obj,$objToSend));
        }
        else{
            return $this->getValues($obj,$objToSend);
        }

    }
    // KODY TABELI:
    // 1- value_ints
    // 2- value_floats
    // 3- value_strings
    // 4- value_dates
    public function createObject(Request $request){
        // name, label, type,photo_path
        $object=new Objects;
        $values=$request->toArray();
        //dump($values['collection_id']);
        $object->collection_id=$values['collection_id'];
        $object->name=$values['name'];
        $object->photo_path=$values['photo_path'];
        //$result1=$object->save();
        dump($object->id);
        $cnt=0;
        foreach ($values as $key => $value) {
            $cnt++;
            if ($cnt<=3) continue;
            dump($key .":". $value);
            if(is_int($value)){
                $valueIntObject=new ValueInt();
                $valueIntObject->object_id=$object->id;
                $valueIntObject->attribute_id=$object->id;
                $valueIntObject->value=$value;
                $valueIntObject->save();
            }
            if(is_double($value)){
                $valueFloatObject=new ValueFloat();
                $valueFloatObject->object_id=$object->id;
                $valueFloatObject->attribute_id=$object->id;
                $valueFloatObject->value=$value;
                $valueFloatObject->save();
            }
            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$value)) {
                $valueDateObject=new ValueDate();
                $valueDateObject->object_id=$object->id;
                $valueDateObject->attribute_id=$object->id;
                $valueDateObject->value=$value;
                $valueDateObject->save();
            } else {
                $valueStringObject=new ValueString();
                $valueStringObject->object_id=$object->id;
                $valueStringObject->attribute_id=$object->id;
                $valueStringObject->value=$value;
                $valueStringObject->save();
            }

        }
//        if($result){
//            return response()->json([
//                "message"=>"Object created successfully"
//            ],201);
//        }
//        else{
//            return response()->json([
//                "message"=>"Object not created"
//            ],422);
//        }

    }

    public function createAttributes(Request $request, $id){
        //collection_id,label, type
        $attr=new ObjectAttributes;
        $attr->collection_id=$id;
        $attr->label=$request->label;
        $attr->type=$request->type;
        $result=$attr->save();
        if($result){
            return response()->json([
                "message"=>"Attribute created successfully"
            ],201);
        }
        else{
            return response()->json([
                "message"=>"Attribute not created"
            ],422);
        }
    }
}

