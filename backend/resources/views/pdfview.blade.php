<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <style>
        body {
            font-family: sans-serif;
        }
        .itemName {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="itemName">
    <h1>{{ $object->name }}</h1>
</div>
<div class="itemImage">
    <img src="http://localhost:3000"{{$object->photo_path}}>
</div>
@foreach($attributes as $key => $attribute)
    <div class="itemAttribute">
        <p>{{ $attribute->label }} : {{ $attributesValues[$key] }}</p>
    </div>
@endforeach
</body>
</html>
