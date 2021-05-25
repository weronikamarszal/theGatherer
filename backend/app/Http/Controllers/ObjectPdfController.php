<?php


namespace App\Http\Controllers;

use App\Models\ObjectAttributes;
use App\Models\Objects;
use App\Models\ValueDate;
use App\Models\ValueFloat;
use App\Models\ValueInt;
use App\Models\ValueString;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class ObjectPdfController
{
    public function pdfview(Request $request, $id)
    {
        $object = Objects::find($id);
        $collectionId = $object->collection_id;
        $attributes = ObjectAttributes::where('collection_id', $collectionId)->get();
        $attributesIds = ObjectAttributes::where('collection_id', $collectionId)->pluck('id');
        $attributesValues = [];

        foreach ($attributesIds as $key => $attributeId) {
            $type = ObjectAttributes::where('id', $attributeId)->value('type');
            if ($type == 1) {
                $attributeValue = ValueInt::where('attribute_id', $attributeId)->where('object_id',$id)->value('value');
                array_push($attributesValues, $attributeValue);
            }
            if ($type == 2) {
                $attributeValue = ValueFloat::where('attribute_id', $attributeId)->where('object_id',$id)->value('value');
                array_push($attributesValues, $attributeValue);
            }
            if ($type == 3) {
                $attributeValue = ValueString::where('attribute_id', $attributeId)->where('object_id',$id)->value('value');
                array_push($attributesValues, $attributeValue);
            }
            if ($type == 4) {
                $attributeValue = ValueDate::where('attribute_id', $attributeId)->where('object_id',$id)->value('value');
                array_push($attributesValues, $attributeValue);
            }
        }

        view()->share('object',$object);
        view()->share('attributes',$attributes);
        view()->share('attributesValues', $attributesValues);

        if($request->has('download')){
            $pdf = PDF::loadView('pdfview');
            return $pdf->download('pdfview.pdf');
        }
        return view('pdfview');
    }
}
