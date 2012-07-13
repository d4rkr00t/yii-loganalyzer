#Yii LogAnalyzer - Анализатор лог файлов yii

## Features:
- Легкое подключение к проекту
- Вывод сообщений из файла лога
- Фильтрация сообщений лога (удалений ненужных сообщений из выдачи)
- Фильтрация вывода лога (вывод только error, warning или info)
- Очистка файла лога

## Пример:

Выводим виджет в представлении:

```php
<?php
$this->widget('ext.loganalyzer.LogAnalyzerWidget',
    array( 'filters' => array('Текст для фильтрации','И еще одно'),
           'title' => 'Анализатор логов' // заголовок виджета
           // 'log_file_path' => 'Абсолютный путь до файла лога'
    ));  
?>
```
## Дополнительно:

Так же в расширении есть расширенный маршурт для логов, добавляющий в сообщения логера ip клиента. Подключается так:

```php
<?php
'log'=>array(
    'class'=>'CLogRouter',
    'routes'=>array(
        ....
        array(
            'class'=>'ext.yii-loganalyzer.LALogRoute',
            'levels'=>'info, error, warning',
        ),
        ...
    ),
),
?>
```

## Скриншот:

![Вывод лога](https://raw.github.com/d4rkr00t/yii-loganalyzer/master/screenshot.jpg "Вывод лога")