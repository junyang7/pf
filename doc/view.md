## include
----
```
@includeS $header @includeE
```

## comment
----
```
@commentS 注释 @commentE
```

## php
----
```
@phpS $data_list = [0, 1, 2, 3, 4, 5, ] @phpE
```

## scalar
----
```
@phpS
    $string = 'Hello World!';
    $int = 1;
    $bool = true;
    $double = 3.1415926;
@phpE

@scalarS $string @scalarE
@scalarS $int @scalarE
@scalarS $bool @scalarE
@scalarS $double @scalarE
```

## escape
----
```
@phpS $html = '<div>Hello World!</div>' @phpE
@escapeS $html @escapeE
```

## json
----
```
@phpS $data = ['name' => 'guoguo', 'age' => 30, ]; @phpE
@jsonS $data @jsonE
```

## foreach
----
```
@phpS $data_list = [0, 1, 2, 3, 4, 5, ] @phpE
@foreachS
    $data_list as $k => $v
@foreachE
    @scalarS $k @scalarE
    @scalarS $v @scalarE
@endForeach
```

## for
----
```
@phpS $data_list = [0, 1, 2, 3, 4, 5, ]; @phpE
@forS $i = 0; $i < count($data_list); $i ++ @forE
    @scalarS $i @scalarE
    @scalarS $data_list[$i] @scalarE
@endFor
```

## while
----
```
@phpS
    $data_list = [0, 1, 2, 3, 4, 5, ];
    $i = 0; 
@phpE

@whileS $i < count($data_list) @whileE
    @scalarS $data_list[$i] @scalarE
    @phpS $i++ @phpE
@endWhile
```

## if
----
```
@phpS $i = random_int(1,3) @phpE
@ifS $i == 1 @ifE
    @scalarS 1 @scalarE
@elseIfS $i == 2 @elseIfE
    @scalarS 2 @scalarE
@else
    @scalarS 3 @scalarE
@endIf
```

## while
----
```
@phpS $i = random_int(1,3) @phpE
@switchS $i @switchE
    @caseS 1 @caseE
        1
        @break
    @caseS 2 @caseE
        2
        @break
    @default
        3
        @break
@endSwitch
```
