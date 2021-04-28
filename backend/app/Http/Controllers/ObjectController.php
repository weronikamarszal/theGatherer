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

    public function getAttributes($id){
        $attributes=Collection::find($id)->attributes;
        return $attributes;
    }
    public function createCollection(Request $request){
        //collection_id,label, type
        $collection=new Collection();
        $collection->name=$request->name;
        $collection->description=$request->description;
        $collection->isPrivate=$request->isPrivate;
        $result=$collection->save();
        if($result){
            return response()->json([
                "message"=>"Collection created successfully"
            ],201);
        }
        else{
            return response()->json([
                "message"=>"Collection not created"
            ],422);
        }
    }
    public function createObject(Request $request){
        // name, label, type,photo_path
        $object=new Objects;
        $values=$request->toArray();
        //dump($values);
        //dump($values['collection_id']);
        $object->collection_id=$values['collection_id'];
        $object->name=$values['name'];
        $object->photo_path=$values['photo_path'];
        $result=$object->save();
        //dump($object->id);
        $cnt=0;
        $nAttributes=count($values)-3;
        foreach ($values as $key => $value) {
            $cnt++;
            if ($cnt <= 3) continue;
            dump($key . ":" . $value);
            $attribute = ObjectAttributes::where('label', '=', $key)->
            where('collection_id', '=', $object->collection_id)->firstOrFail();
            dump($attribute);
            if (is_int($value)) {
                $valueIntObject = new ValueInt();
                $valueIntObject->object_id = $object->id;
                $valueIntObject->attribute_id = $attribute->id;
                $valueIntObject->value = $value;
                $valueIntObject->save();
            }
            if (is_double($value)) {
                $valueFloatObject = new ValueFloat();
                $valueFloatObject->object_id = $object->id;
                $valueFloatObject->attribute_id = $attribute->id;
                $valueFloatObject->value = $value;
                $valueFloatObject->save();
            }
            if(is_string($value)){
                if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $value)) {
                    $valueDateObject = new ValueDate();
                    $valueDateObject->object_id = $object->id;
                    $valueDateObject->attribute_id = $attribute->id;
                    $valueDateObject->value = $value;
                    $valueDateObject->save();
                } else {
                    $valueStringObject = new ValueString();
                    $valueStringObject->object_id = $object->id;
                    $valueStringObject->attribute_id = $attribute->id;
                    $valueStringObject->value = $value;
                    $valueStringObject->save();
                }
            }
        //dump($cnt);
        }
        if($result && $cnt-3==$nAttributes){
            return response()->json([
                "message"=>"Object created successfully"
            ],201);
        }
        else{
            return response()->json([
                "message"=>"Object not created"
            ],422);
        }

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

