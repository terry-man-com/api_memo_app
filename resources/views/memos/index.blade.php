<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>memo index</title>
</head>
<body>
    <h1>タイトル一覧</h1>
    <ul>
        @foreach ($memos as $memo)
            <li><a href="{{ route('memos.show', $memo->id) }}">{{ $memo->title }}</a></li>
        @endforeach
    </ul>

    {{-- 新規登録画面へジャンプする --}}
    <button onclick='location.href="{{ route("memos.create") }}"'>登録する</button>
</body>
</html>