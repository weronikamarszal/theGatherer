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
        .image {
            max-width: 100%;
        }
        .itemImage {
            margin-bottom: 30px;
        }
        .attributes {
            background-color: #00DBDE;
            background-image: linear-gradient(90deg, #00DBDE 0%, #FC00FF 100%);
            height: auto;
        }
        .itemAttribute {
            background-color: #F1FFFF;
            width: 90%;
            margin: 0 auto;
            padding-left: 20px;
        }

    </style>
</head>
<body>
<div class="itemName">
    <h1>{{ $object->name }}</h1>
</div>
<div class="itemImage">
    <img class="image" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($object->photo_path))) }}">
</div>
<div class="attributes">
    @foreach($attributes as $key => $attribute)
        <div class="itemAttribute">
            <p>{{ $attribute->label }} : {{ $attributesValues[$key] }}</p>
        </div>
    @endforeach
</div>
</body>
</html>
