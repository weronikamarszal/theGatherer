<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\ObjectAttributes;
use App\Models\ValueDate;
use App\Models\ValueFloat;
use App\Models\ValueInt;
use App\Models\ValueString;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Objects;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Psy\Util\Json;
use Barryvdh\DomPDF\PDF;
use App\Http\Requests;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


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


    public function getObjects($collection_id,bool $returnJson)
    {
        $objs = Collection::find($collection_id)->objects;
        $objectsToSend = [];
        foreach ($objs as $object) {
            array_push($objectsToSend, $this->getObject($object->id, false));
        }
        if ($returnJson){
            return json_encode($objectsToSend);
        }
        else {
            return $objectsToSend;
        }

    }

    public function getObject($id, bool $returnJson)
    {
        $obj = Objects::find($id);
        $objToSend = array('id' => $obj->id, 'collection_id' => $obj->collection_id, 'name' => $obj->name,
            'photo_path' => $obj->photo_path);
//         dump($objToSend);
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
        //dump($values['attributes']);
        $values['attributes'] = json_decode($values['attributes']);
        //dump($values);
        //dump($values['collection_id'])
        $object->collection_id = $values['collection_id'];
        $object->name = $values['name'];

        if ($request->has('item_image')) {
            $image = $request->file('item_image');
            $name = Str::slug($request->input('name')) . '_' . time();
            $folder = '/uploads/images/'  . $object->collection_id . '/';
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

    public function updateCollection(Request $request,$id){
        $values = $request->toArray();
//         dump($values);
        $collection=Collection::find($id);
        $collection->name=$values['name'];
        $collection->description=$values['description'];
        $collection->isPrivate=$values['isPrivate'];
        $result=$collection->save();
        if($result){
            return response()->json([
                "message" => "Collection modified successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Collection not modified"
            ], 422);
        }
    }
    public function updateObject(Request $request,$id){
        $values = $request->toArray();
        $values['attributes'] = json_decode($values['attributes']);
        $object=Objects::find($id);
        $object->name=$values['name'];
        $nAttributes = count($values['attributes']);

        if ($request->has('item_image')) {
            $image = $request->file('item_image');
            $name = Str::slug($request->input('name')) . '_' . time();
            $folder = '/uploads/images/'  . $values['collection_id'] . '/';
            $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
            $photo_path = Objects::where('id',$id) -> find('photo_path');
            if(file_exists($photo_path)) {
                File::delete($photo_path);
            }
            $this->uploadOne($image, $folder, 'public', $name);
            $object->photo_path = $filePath;
        }
        $object->save();

        $cnt=0;
        foreach ($values['attributes'] as $attribute) {
            $cnt++;
            //dump($attribute['id']);
            $attributeObject = ObjectAttributes::find($attribute->id);
            //dump($attributeObject);
            if ($attributeObject->type == ObjectAttributes::TYPE_INT) {
                ValueInt::where('object_id',$id)->where('attribute_id',$attributeObject->id)
                    ->update(['value'=>$attribute->value]);
            } else if ($attributeObject->type == ObjectAttributes::TYPE_FLOAT) {
                ValueFloat::where('object_id', $id)->where('attribute_id', $attributeObject->id)
                    ->update(['value'=>$attribute->value]);
            } else if ($attributeObject->type == ObjectAttributes::TYPE_DATE) {
                ValueDate::where('object_id', $id)->where('attribute_id', $attributeObject->id)
                    ->update(['value'=>$attribute->value]);
            } else if ($attributeObject->type == ObjectAttributes::TYPE_STRING) {
                ValueString::where('object_id', $id)->where('attribute_id', $attributeObject->id)
                    ->update(['value'=>$attribute->value]);
            }
        }
        if ($cnt == $nAttributes) {
            return response()->json([
                "message" => "Object updated successfully"
            ], 201);
        } else {
            return response()->json([
                "message" => "Object not updated"
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

    }

    public function getSorted(Request $request,$id)
    {
        $values = $request->toArray();
        //dump($values);
        $coll = Collection::find($id)->get();
        $obj = $this->getObjects($id, false);
        //dump($obj);
        //dump($filters[0]);
        $cnt=0;
        if(array_key_exists('filters',$values)){
            $filters = $values['filters'];
            foreach ($filters as $filterKey => $filterValue) {
                $to = null;
                $value=null;
                $from = null;
                if(is_array($filterValue)){
                    if (array_key_exists('from', $filterValue)) {
                        $from = $filterValue['from'];
                    }
                    if (array_key_exists('to', $filterValue)) {
                        $to = $filterValue['to'];
                    }
                }
                else{
                    $value=$filterValue;
                }
                //dump($value);
                foreach ($obj as $object) {
                    //dump($filterKey, $filterValue);
                    if ($from != null && $to != null) {
                        if ( $object[$filterKey] > $to || $object[$filterKey] < $from) {
                            unset($obj[$cnt]);
                        }
                    }
                    else if ($from != null && $to == null) {
                        if ($object[$filterKey] < $from) {
                            unset($obj[$cnt]);
                        }
                    }
                    else if ($from == null && $to != null) {
                        if ($object[$filterKey] >$to) {
                            unset($obj[$cnt]);
                        }
                    }

                    if($value!=null){
                        if(ctype_alpha($value)){
                            if(strtoupper($object[$filterKey])!=strtoupper($value)){
                                unset($obj[$cnt]);
                            }
                        }
                        else{
                            if($object[$filterKey]!=$value){
                                unset($obj[$cnt]);
                            }
                        }
                    }
                    $cnt+=1;
                }
                $cnt=0;
            }
        }
        if(key_exists('sort_by',$values)){
            $sortBy=array_column($obj,$values['sort_by']['field']);
            //dump($sortBy);
            if($values['sort_by']['dir']=='asc'){
                array_multisort($sortBy,SORT_ASC,$obj);
            }
            else{
                array_multisort($sortBy,SORT_DESC,$obj);
            }
        }
        //dump($obj);
        return array_values($obj);
    }

    public function getCollectionCsv($id){
        $collection=Collection::find($id);
        //dump($collection["name"]);
        $objects=$this->getObjects($id,false);
        //dump($objects);
        $columns=[];
        foreach($objects[0] as $key=>$value){
            array_push($columns,$key);
        }
//        foreach($objects as &$object){
//            foreach($object as $key=>$value){
//                if(is_float($value)){
//                    $object[$key]=str_replace(".",",",strval($value));
//                }
//            }
//        }
        array_splice($columns,0,2);
        array_splice($columns,1,1);
        //dump($columns);
        //dump($objects);
        $now=Carbon::now()->toDateTimeString();
        $filename=str_replace(" ","-",$collection['name'])."-".str_replace(array(" ",":"),array("_","-"),$now).'.csv';
        //dump($filename);
        //dump(getcwd());
        $file=fopen($_SERVER['DOCUMENT_ROOT']."\\reports\\".strval($filename),'w');
        fputcsv($file,$columns,';',' ');
        foreach($objects as $object){
            unset($object['id'],$object['collection_id'],$object['photo_path']);
            fputcsv($file,$object,';',' ');
        }
        $spreadsheet = new Spreadsheet();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $reader->setDelimiter(';');
        $reader->setEnclosure(' ');
        $reader->setSheetIndex(0);
        /* Load a CSV file and save as a XLS */
        $spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."\\reports\\".strval($filename));
        $filename=str_replace(".csv",".xlsx",$filename);
        $writer = new Xlsx($spreadsheet);
        $writer->save($_SERVER['DOCUMENT_ROOT']."\\reports\\".strval($filename));
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
        //header('Content-Type : text/csv');
        //header("Content-Disposition: attachment; filename=".$_SERVER['DOCUMENT_ROOT']."\\reports\\".$filename);
        //header('Content-Type: application/force-download');
        header("Content-Description: File Transfer");
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        //header('Content-Type: application/force-download');
        //header("Content-Disposition: attachment; filename=".$_SERVER['DOCUMENT_ROOT']."\\reports\\".$filename.'"');
        //readfile ($_SERVER['DOCUMENT_ROOT']."\\reports\\".strval($filename).'.xlsx');
        //exit();
        unlink($_SERVER['DOCUMENT_ROOT']."\\reports\\".str_replace('xlsx','csv',$filename));
        return response()->download($_SERVER['DOCUMENT_ROOT']."\\reports\\".$filename);
    }
}

