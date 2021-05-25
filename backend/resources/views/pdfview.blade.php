<div class="itemName">
    <h1>{{ $object->name }}</h1>
</div>
<div class="itemImage">
    <img src="{{ $object->photo_path }}" alt="item image">
</div>
@foreach($attributes as $key => $attribute)
    <div class="itemAttribute">
        <p>{{ $attribute->label }} : {{ $attributesValues[$key] }}</p>
    </div>
@endforeach


