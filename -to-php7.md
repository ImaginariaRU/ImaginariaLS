```
mysql_real_escape_string($aFilter['topic_date_more'])
```

Заменяем на

```
$this->oDb->_performEscape($aFilter['topic_date_more'])
```

# Line ?? [Error] Removed regular expression modifier "e" used

```
$str = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $str);
```

Меняем на `preg_replace_callback($pattern, $callback, $subject)`:

// как обычно: $matches[0] -  полное вхождение шаблона
// $matches[1] - вхождение первой подмаски,
// заключенной в круглые скобки и так далее...

```
$str = preg_replace('~&#x([0-9a-f]+);~i', function($matches){
    return chr(hexdec( $matches[1] ));
}, $str);
```

