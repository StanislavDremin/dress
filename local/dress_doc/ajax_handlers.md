### Ajax запросы и обработчики на проекте

Все кастомные аякс-запросы проходят через urlrewrite.php и роутер /rest/index.php.

В urlrewrite.php описывается парвило для пачки каких-то запросов.
На пример, мы хотим работать над созданием заказа, т.о подразумеваем, что все запросы с для этого функционала
будут обрабатываться каким-то компонентом, имеющим class.php или еще каким-то классом модуля.

Пишем правило
```php
    array(
		"CONDITION" => "/orderCreate",
		"RULE" => "",
		"ID" => "ab:order.creator",
		"PATH" => "\\Dresscode\\OrderComponent",
		"SORT" => "100600",
	),
```
В данном случае за создание заказа отвечает компонент ab:order.creator. У него класс \Dresscode\OrderComponent.
В CONDITION мы указываем через какой виртуальный урл (относительно /rest/) должны идти все запросы на этот компонент.
Таким образом, чтоб послать запрос в компонент мы должны знать или сделать метод для обработки этого запроса, и должны 
выставить урл типа 
```js
$.get('/rest/orderCreate/getDeliveryItems?location=123').then(res => {
    console.log(res.data)
}, 'json')
```
В этом случае мы посылаем запрос в ab:order.creator, а конкретно вызываем \Dresscode\OrderComponent::getDeliveryItemsAction($requestParams);
$requestParams - там будут все параметры запроса 
```php
$requestParams = [
    'location' => 123
]
```
Если отправляем в js на /rest/orderCreate/getDeliveryItems, то в \Dresscode\OrderComponent должен быть метод getDeliveryItemsAction.

Важно! Все запросы через этот механизм принимают и отдают только json. Никаких галимых кусков html.
Это значит что все request-ы должны иметь headers: {'Accept':'application/json', 'Content-type':'application/json'}

Если у нас нет компонента для обработки, а есть только модуль и его какой-то класс, то в правило urlrewrite.php втыкаем
```php
array(
    "CONDITION" => "/path_to_url",
    "RULE" => "",
    "ID" => "dresscode.main",
    "PATH" => "\\Dresscode\\Main\\MyClass",
),
```
Теперь все запросы с /rest/path_to_url будут обрабатываться классом Dresscode\Main\MyClass модуля dresscode.main
