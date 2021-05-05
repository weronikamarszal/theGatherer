<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\ObjectAttributes;
use App\Models\ValueDate;
use App\Models\ValueFloat;
use App\Models\ValueInt;
use App\Models\ValueString;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Objects;
use App\Traits\UploadTrait;
use Illuminate\Support\Str;
use Psy\Util\Json;

class ObjectController extends BaseController
{
    use UploadTrait;

    public function getValues($obj, $objToSend)
    {
        if ($obj->valueFloats) {
            foreach ($obj->valueFloats as $value) {
                $key = strval($value->attributeName->label);
                $objToSend[$key] = $value->value;
            }
        }

        if ($obj->valueInts) {
            foreach ($obj->valueInts as $value) {
                $key = strval($value->attributeName->label);
                $objToSend[$key] = $value->value;
            }
        }
        if ($obj->valueStrings) {
            foreach ($obj->valueStrings as $value) {
                $key = strval($value->attributeName->label);
                $objToSend[$key] = $value->value;
            }
        }
        if ($obj->valueDates) {
            foreach ($obj->valueDates as $value) {
                $key = strval($value->attributeName->label);
                $objToSend[$key] = $value->value;
            }
        }
        return $objToSend;
    }


    public function getObjects($collection_id)
    {
        $objs = Collection::find($collection_id)->objects;
        $objectsToSend = [];
        foreach ($objs as $object) {
            array_push($objectsToSend, $this->getObject($object->id, false));
        }
        return json_encode($objectsToSend);
    }

    public function getObject($id, bool $returnJson)
    {
        $obj = Objects::find($id);
        $objToSend = array('id' => $obj->id, 'collection_id' => $obj->collection_id, 'name' => $obj->name,
            'photo_path' => $obj->photo_path);
        dump($objToSend);
        if ($returnJson) {
            return json_encode($this->getValues($obj, $objToSend));
        } else {
            return $this->getValues($obj, $objToSend);
        }

    }

    public function getAttributes($id)
    {
        $attributes = Collection::find($id)->attributes;
        return $attributes;
    }

    public function createCollection(Request $request)
    {
        //collection_id,label, type
        $collection = new Collection();
        $collection->name = $request->name;
        $collection->description = $request->description;
        $collection->isPrivate = $request->isPrivate;
        $result = $collection->save();
        if ($result) {
            return response()->json($collection, 201);
        } else {
            return response()->json([
                "message" => "Collection not created"
            ], 422);
        }
    }

    public function createObject(Request $request)
    {
        // name, label, type,photo_path
        $object = new Objects;
        $values = $request->toArray();
        $values['attributes'] = json_decode($values['attributes']);
        //dump($values);
        //dump($values['collection_id'])
        $object->collection_id = $values['collection_id'];
        $object->name = $values['name'];

        if ($request->has('item_image')) {
            $image = $request->file('item_image');
            $name = Str::slug($request->input('name')) . '_' . time();
            $folder = '/uploads/images/' . $object->collection_id;
            $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $name);
            $object->photo_path = $filePath;
        }
        $result = $object->save();
        $cnt = 0;
        $nAttributes = count($values['attributes']);
        //dump($nAttributes,$values['attributes']);
        foreach ($values['attributes'] as $attribute) {
            $cnt++;
            //dump($attribute['id']);
            $attributeObject = ObjectAttributes::find($attribute->id);
            //dump($attributeObject);
            if ($attributeObject->type == ObjectAttributes::TYPE_INT) {
                $valueIntObject = new ValueInt();
                $valueIntObject->object_id = $object->id;
                $valueIntObject->attribute_id = $attributeObject->id;
                $valueIntObject->value = $attribute->value;
                $valueIntObject->save();
            } else if ($attributeObject->type == ObjectAttributes::TYPE_FLOAT) {
                $valueFloatObject = new ValueFloat();
                $valueFloatObject->object_id = $object->id;
                $valueFloatObject->attribute_id = $attributeObject->id;
                $valueFloatObject->value = $attribute->value;
                $valueFloatObject->save();
            } else if ($attributeObject->type == ObjectAttributes::TYPE_DATE) {
                $valueDateObject = new ValueDate();
                $valueDateObject->object_id = $object->id;
                $valueDateObject->attribute_id = $attributeObject->id;
                $valueDateObject->value = $attribute->value;
                $valueDateObject->save();
            } else if ($attributeObject->type == ObjectAttributes::TYPE_STRING) {
                $valueStringObject = new ValueString();
                $valueStringObject->object_id = $object->id;
                $valueStringObject->attribute_id = $attributeObject->id;
                $valueStringObject->value = $attribute->value;
                $valueStringObject->save();
            }
        }
        //dump($cnt);
        if ($result && $cnt == $nAttributes) {
            return response()->json([
                "message" => "Object created successfully"
            ], 201);
        } else {
            return response()->json([
                "message" => "Object not created"
            ], 422);
        }

    }

    public function createAttributes(Request $request, $id)
    {
        //collection_id,label, type
        $values = $request->toArray();
        $insertSuccess = 0;
        foreach ($values as $attribute) {
//             dump($attribute);
            $attr = new ObjectAttributes;
            $attr->collection_id = $id;
            $attr->label = $attribute['label'];
            $attr->type = $attribute['type'];
            $result = $attr->save();
            if ($result) {
                $insertSuccess++;
            }
        }
        if ($insertSuccess == count($values)) {
            return response()->json([
                "message" => "Attributes created successfully"
            ], 201);
        } else {
            return response()->json([
                "message" => "Attributes not created"
            ], 422);
        }
    }

    public function notNullnotEmpty($var){
        if($var!=null && $var!=''){
            return true;
        }
        else return false;
    }
    public function updateCollection(Request $request,$id){
        $values = $request->toArray();
        //dump($values);
        //dump(array_filter($values,array($this,"notNullnotEmpty")));
        $cnt=0;
        if ($values['name']!=null && $values['name']!=''){
            Collection::where('id',$id)->update(['name'=>$values['name']]);
            $cnt++;
        }
        if($values['description']!=null && $values['description']!=''){
            Collection::where('id',$id)->update(['description'=>$values['description']]);
            $cnt++;
        }
        if($values['isPrivate']!=null && $values['isPrivate']!=''){
            Collection::where('id',$id)->update(['isPrivate'=>$values['isPrivate']]);
            $cnt++;
        }
        if($cnt==count(array_filter($values,array($this,"notNullnotEmpty")))){
            return response()->json([
                "message" => "Collection modified successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Collection not modified"
            ], 422);
        }
    }

    public function deleteObject(Request $request, $id)
    {
        //deleting values of the object
        ValueDate::where('object_id',$id)->delete();
        ValueFloat::where('object_id',$id)->delete();
        ValueInt::where('object_id',$id)->delete();
        ValueString::where('object_id',$id)->delete();

        //deleting photo of the object
        $photo_path = Objects::where('id',$id) ->find('photo_path');
        if(file_exists($photo_path)) {
            File::delete($photo_path);
        }

        //deleting empty object
        Objects::where('id', $id)->delete();

        //sending response
        return response()->json([
            "message" => "Item deleted successfully"
        ], 200);
    }

    public function deleteCollection(Request $request, $id)
    {
        //deleting values of the objects
        $objects = Objects::where('collection_id', $id)->get();
        foreach($objects as $object){
            /** @var $object Objects */
            foreach($object->valueStrings as $valueString){
                /** @var  $valueString ValueString */
                $valueString-> delete();
            }
            /** @var $object Objects */
            foreach($object->valueFloats as $valueFloat){
                /** @var  $valueFloat ValueFloat */
                $valueFloat->delete();
            }
            /** @var $object Objects */
            foreach($object->valueInts as $valueInt){
                /** @var  $valueInt ValueInt */
                $valueInt->delete();
            }
            /** @var $object Objects */
            foreach($object->valueDates as $valueDate){
                /** @var  $valueDate ValueDate */
                $valueDate->delete();
            }
        }

        //deleting photos from collection
        $photo_path = Objects::where('collection_id',$id) -> find('photo_path');
        if(file_exists($photo_path)) {
            File::delete($photo_path);
        }

        //deleting object attributes from collection
        ObjectAttributes::where('collection_id', $id)->delete();

        //deleting objects from collection
        Objects::where('collection_id', $id)->delete();

        //deleting empty collection
        Collection::where('id', $id)->delete();

        //sending response
        return response()->json([
            "message" => "Object deleted successfully"
        ], 200);



        //        image uploading while editing

//        $values->collection_id = $values['collection_id'];
//        $values->name = $values['name'];
//
//        if ($request->has('item_image')) {
//            $image = $request->file('item_image');
//            $name = Str::slug($request->input('name')) . '_' . time();
//            $folder = '/uploads/images/'  . $values->collection_id;
//            $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
//            $photo_path = Objects::where('id',$id) -> find('photo_path');
//            if(file_exists($photo_path)) {
//                File::delete($photo_path);
//            }
//            $this->uploadOne($image, $folder, 'public', $name);
//            $values->photo_path = $filePath;
//        }
    }
}

