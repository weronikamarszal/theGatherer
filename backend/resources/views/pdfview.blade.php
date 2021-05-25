<div class="itemName">
    <h1>{{ $object->name }}</h1>
    {{ $attributes }}
    {{$attributesValues}}
</div>
<div class="itemImage">
    <img src="{{ $object->photo_path }}" alt="item image">
</div>
@foreach($attributes as $key => $attribute)
    <div class="itemAttribute">
        <p>{{ $attribute->label }} : </p>
    </div>
@endforeach
@foreach($attributesValues as $key => $attributeValue)
    <div class="itemAttribute">
        <p>{{ $attributeValue }} : </p>
    </div>
@endforeach
